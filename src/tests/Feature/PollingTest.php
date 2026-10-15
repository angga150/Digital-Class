<?php

namespace Tests\Feature;

use App\Livewire\Polling\Ballot;
use App\Livewire\Polling\Featured;
use App\Livewire\Polling\Index;
use App\Models\ClassMember;
use App\Models\ClassModel;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;
use Tests\TestCase;

class PollingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->travelTo(Carbon::parse('2026-10-16 08:00:00', 'UTC'));
    }

    private function user(string $role = 'admin', ?int $classId = null, bool $verified = true): User
    {
        $classId ??= ClassModel::create(['name' => 'Kelas '.uniqid(), 'code' => uniqid(), 'join_code' => uniqid(), 'is_active' => true])->id;
        $user = User::factory()->create(['email_verified_at' => $verified ? now() : null]);
        ClassMember::create(['class_id' => $classId, 'user_id' => $user->id, 'role' => $role]);

        return $user;
    }

    private function poll(User $user, array $attributes = []): Poll
    {
        $poll = Poll::create(array_merge([
            'class_id' => $user->classMemberships()->withoutGlobalScopes()->first()->class_id,
            'created_by' => $user->id, 'question' => 'Ke mana kita?', 'ends_at' => now()->addDay(),
            'is_anonymous' => true, 'result_visibility' => 'after_vote',
        ], $attributes));
        $poll->options()->createMany([['option_text' => 'Taman', 'order' => 0], ['option_text' => 'Kantin', 'order' => 1]]);

        return $poll;
    }

    public function test_admin_can_publish_anonymous_single_choice_poll_with_wib_deadline(): void
    {
        $user = $this->user();
        Livewire::actingAs($user)->test(Index::class)->call('create')->set('question', 'Tempat piknik?')
            ->set('options', ['Danau', 'Pantai'])->set('ends', '2026-10-17T09:00')
            ->call('save')->assertHasNoErrors()->assertSet('formOpen', false)->assertSee('Tempat piknik?');
        $poll = Poll::firstOrFail();
        $this->assertSame('2026-10-17 02:00:00', $poll->ends_at->format('Y-m-d H:i:s'));
        $this->assertTrue($poll->is_anonymous);
        $this->assertFalse($poll->allow_multiple);
        $this->assertSame(2, $poll->options()->count());
        $this->get('/polls')->assertOk()->assertSee('Tempat piknik?')->assertSee('09:00 WIB');
        $this->get('/')->assertOk()->assertSee('Tempat piknik?')->assertDontSee('Hasil simulasi');
    }

    public function test_form_rejects_duplicate_empty_excess_options_and_invalid_deadlines(): void
    {
        $form = Livewire::actingAs($this->user())->test(Index::class)->call('create')->set('question', 'Tes')
            ->set('ends', '2026-10-17T09:00');
        $form->set('options', [' Taman ', 'taman'])->call('save')->assertHasErrors('options.0');
        $form->set('options', ['A', '   '])->call('save')->assertHasErrors('options.1');
        $form->set('options', ['A'])->call('save')->assertHasErrors('options');
        $form->set('options', ['A', 'B', 'C', 'D', 'E', 'F', 'G'])->call('save')->assertHasErrors('options');
        $form->set('options', ['A', 'B'])->set('ends', '2026-02-30T09:00')->call('save')->assertHasErrors('ends');
        $form->set('ends', '2026-10-16T15:00')->call('save')->assertHasErrors('ends');
        $form->set('ends', '2038-01-19T10:15')->call('save')->assertHasErrors('ends');
        $form->set('ends', '2038-01-19T10:14')->set('visibility', 'invalid')->call('save')->assertHasErrors('visibility');
        $this->assertDatabaseCount('polls', 0);
        $form->set('visibility', 'closed')->call('save')->assertHasNoErrors();
        $this->assertDatabaseCount('polls', 1);
    }

    public function test_only_verified_admin_can_publish_and_close_and_role_is_rechecked(): void
    {
        $admin = $this->user();
        $poll = $this->poll($admin);
        $member = $this->user('member', $poll->class_id);
        Livewire::actingAs($member)->test(Index::class)->call('create')->assertForbidden();
        Livewire::actingAs($member)->test(Index::class)->call('save')->assertForbidden();
        Livewire::actingAs($member)->test(Ballot::class, ['pollId' => $poll->id])->call('close')->assertForbidden();
        $unverified = $this->user('admin', $poll->class_id, false);
        Livewire::actingAs($unverified)->test(Index::class)->call('create')->assertForbidden();
        Livewire::actingAs($unverified)->test(Ballot::class, ['pollId' => $poll->id])->call('close')->assertForbidden();
        $form = Livewire::actingAs($admin)->test(Index::class)->call('create');
        ClassMember::withoutGlobalScopes()->where('user_id', $admin->id)->update(['role' => 'member']);
        $form->call('save')->assertForbidden();
    }

    public function test_vote_persists_once_and_cannot_be_changed_or_counted_twice(): void
    {
        $user = $this->user();
        $poll = $this->poll($user);
        $this->actingAs($user);
        [$first, $second] = $poll->options()->get()->all();
        $component = Livewire::test(Ballot::class, ['pollId' => $poll->id]);
        $component->call('vote')->assertHasErrors('choice');
        $component->set('choice', $first->id)->call('vote')->assertHasNoErrors()->assertSee('1 suara')->assertSee('100%');
        $component->set('choice', $second->id)->call('vote')->assertHasErrors('choice');
        $this->assertDatabaseCount('poll_votes', 1);
        $this->assertDatabaseHas('poll_votes', ['poll_id' => $poll->id, 'user_id' => $user->id, 'poll_option_id' => $first->id]);
        $this->assertSame(1, $poll->fresh()->total_votes);
        Livewire::test(Ballot::class, ['pollId' => $poll->id])->assertSee('Suaramu tersimpan:')->assertSee('Taman')->assertDontSee('Kirim suara');
    }

    public function test_unique_constraint_rejects_duplicate_direct_writes(): void
    {
        $user = $this->user();
        $poll = $this->poll($user);
        $this->actingAs($user);
        $row = ['poll_id' => $poll->id, 'user_id' => $user->id, 'poll_option_id' => $poll->options()->first()->id];
        PollVote::create($row);
        $this->expectException(QueryException::class);
        PollVote::create($row);
    }

    public function test_other_poll_option_and_unverified_vote_are_rejected(): void
    {
        $user = $this->user();
        $poll = $this->poll($user);
        $other = $this->poll($user);
        $this->actingAs($user);
        $option = $other->options()->first()->id;
        Livewire::test(Ballot::class, ['pollId' => $poll->id])->set('choice', $option)->call('vote')->assertHasErrors('choice');
        $unverified = $this->user('member', $poll->class_id, false);
        Livewire::actingAs($unverified)->test(Ballot::class, ['pollId' => $poll->id])->assertSee('Verifikasi email')->set('choice', $option)->call('vote')->assertForbidden();
        $this->assertDatabaseCount('poll_votes', 0);
    }

    public function test_exact_deadline_and_manual_close_reject_stale_ballots(): void
    {
        $user = $this->user();
        $poll = $this->poll($user);
        $this->actingAs($user);
        $component = Livewire::test(Ballot::class, ['pollId' => $poll->id])->set('choice', $poll->options()->first()->id);
        $this->travelTo($poll->ends_at);
        $component->call('vote')->assertForbidden();
        $second = $this->poll($user);
        $stale = Livewire::test(Ballot::class, ['pollId' => $second->id])->set('choice', $second->options()->first()->id);
        Livewire::test(Ballot::class, ['pollId' => $second->id])->call('close')->assertSee('Polling ditutup.');
        $stale->call('vote')->assertForbidden();
        $this->assertDatabaseCount('poll_votes', 0);
        $this->assertFalse($second->fresh()->is_active);
    }

    public function test_results_visibility_is_enforced_without_loading_voter_identities(): void
    {
        $admin = $this->user();
        $poll = $this->poll($admin);
        $this->actingAs($admin);
        $option = $poll->options()->first();
        $poll->votes()->create(['user_id' => $admin->id, 'poll_option_id' => $option->id]);
        $member = $this->user('member', $poll->class_id);
        $this->actingAs($member);
        Livewire::test(Ballot::class, ['pollId' => $poll->id])->assertDontSee('Hasil suara')->assertDontSee('1 suara')
            ->set('choice', $option->id)->call('vote')->assertSee('Hasil suara')->assertSee('2 suara')
            ->assertDontSee($admin->email)->assertDontSee($admin->name);
        $poll->update(['result_visibility' => 'admin_only']);
        Livewire::test(Ballot::class, ['pollId' => $poll->id])->assertDontSee('Hasil suara')->assertDontSee('2 suara');
        Livewire::actingAs($admin)->test(Ballot::class, ['pollId' => $poll->id])->assertSee('2 suara')->assertDontSee($member->name)->assertDontSee($member->email);
        $poll->update(['result_visibility' => 'closed']);
        Livewire::test(Ballot::class, ['pollId' => $poll->id])->assertDontSee('Hasil suara')->call('close')->assertSee('2 suara');
        $poll->update(['result_visibility' => 'after_vote']);
        $newMember = $this->user('member', $poll->class_id);
        Livewire::actingAs($newMember)->test(Ballot::class, ['pollId' => $poll->id])->assertSee('2 suara');
    }

    public function test_results_ignore_seed_counters_and_handle_zero_votes(): void
    {
        $user = $this->user();
        $poll = $this->poll($user, ['total_votes' => 99, 'result_visibility' => 'all']);
        $this->actingAs($user);
        $poll->options()->update(['votes_count' => 99]);
        Livewire::test(Ballot::class, ['pollId' => $poll->id])->assertSee('0 suara')->assertSee('0%')->assertDontSee('99 suara');
    }

    public function test_class_scopes_and_inactive_membership_block_access(): void
    {
        $owner = $this->user();
        $poll = $this->poll($owner, ['question' => 'Rahasia kelas lain']);
        $this->actingAs($owner);
        $poll->votes()->create(['user_id' => $owner->id, 'poll_option_id' => $poll->options()->first()->id]);
        $outsider = $this->user();
        $this->actingAs($outsider);
        $this->get('/polls')->assertOk()->assertDontSee('Rahasia kelas lain');
        $this->assertSame(0, Poll::count());
        $this->assertSame(0, PollOption::count());
        $this->assertSame(0, PollVote::count());
        $this->assertFalse(Gate::allows('viewResults', $poll));
        try {
            Livewire::test(Ballot::class, ['pollId' => $poll->id]);
            $this->fail('Cross-class poll must not be found.');
        } catch (ModelNotFoundException $exception) {
            $this->assertSame(Poll::class, $exception->getModel());
        }
        $this->actingAs($owner);
        $form = Livewire::test(Index::class)->call('create');
        ClassModel::whereKey($poll->class_id)->update(['is_active' => false]);
        $form->call('save')->assertForbidden();
    }

    public function test_widget_filters_and_escaped_content(): void
    {
        $user = $this->user();
        $this->poll($user, ['question' => 'Sudah lewat', 'ends_at' => now()]);
        $this->poll($user, ['question' => 'Tutup manual', 'is_active' => false]);
        $this->poll($user, ['question' => 'Pilihan ganda lama', 'allow_multiple' => true]);
        $poll = $this->poll($user, ['question' => '<script>alert(1)</script>', 'description' => '<img src=x onerror=alert(2)>']);
        $other = $this->user();
        $this->poll($other, ['question' => 'Beda kelas']);
        $this->actingAs($user);
        Livewire::test(Featured::class)->assertDontSee('Sudah lewat')->assertDontSee('Tutup manual')->assertDontSee('Beda kelas')->assertDontSee('Pilihan ganda lama');
        $this->get('/polls')->assertOk()->assertSee('&lt;script&gt;', false)->assertDontSee('<script>alert(1)</script>', false)->assertDontSee('<img src=x', false);
        Livewire::test(Index::class)->set('filter', 'closed')->assertSee('Sudah lewat')->assertSee('Tutup manual')->assertSee('Pilihan ganda lama');
        $poll->update(['is_active' => false]);
        Livewire::test(Featured::class)->assertSee('Belum ada polling aktif.');
    }
}
