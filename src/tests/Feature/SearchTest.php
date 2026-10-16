<?php

namespace Tests\Feature;

use App\Livewire\Search\Index;
use App\Models\ClassMember;
use App\Models\ClassModel;
use App\Models\Event;
use App\Models\Poll;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    private function user(?int $classId = null): User
    {
        $classId ??= ClassModel::create(['name' => 'Kelas', 'code' => uniqid(), 'join_code' => uniqid(), 'is_active' => true])->id;
        $user = User::factory()->create(['name' => 'Nara Wijaya']);
        ClassMember::create(['class_id' => $classId, 'user_id' => $user->id, 'role' => 'member']);

        return $user;
    }

    private function data(User $user): array
    {
        $class = $user->classMemberships()->withoutGlobalScopes()->first()->class_id;
        $post = Post::create(['class_id' => $class, 'user_id' => $user->id, 'content' => 'Piknik bersama', 'type' => 'discussion']);
        $notice = Post::create(['class_id' => $class, 'user_id' => $user->id, 'content' => 'Piknik resmi', 'type' => 'announcement']);
        $event = Event::create(['class_id' => $class, 'created_by' => $user->id, 'title' => 'Piknik sore', 'event_date' => now()->addDay(), 'ends_at' => now()->addDays(2)]);
        $poll = Poll::create(['class_id' => $class, 'created_by' => $user->id, 'question' => 'Piknik di mana?', 'result_visibility' => 'admin_only', 'total_votes' => 9797]);

        return compact('class', 'post', 'notice', 'event', 'poll');
    }

    public function test_real_results_link_to_each_scoped_detail(): void
    {
        $user = $this->user();
        $data = $this->data($user);
        $component = Livewire::actingAs($user)->test(Index::class)->set('q', 'piknik')->call('search')->assertSee('Piknik bersama')->assertDontSee('Piknik resmi');
        $component->set('category', 'announcements')->assertSee('Piknik resmi')->assertSee(route('announcements.show', $data['notice']));
        $component->set('category', 'events')->assertSee('Piknik sore')->assertSee(route('events.show', $data['event']));
        $component->set('category', 'polls')->assertSee('Piknik di mana?')->assertDontSee('9797')->assertSee(route('polls.show', $data['poll']->id));
        $this->get('/polls/'.$data['poll']->id)->assertOk()->assertDontSee('Hasil suara')->assertDontSee('9797');
        $this->get('/search?q=piknik')->assertOk()->assertSee('Piknik bersama');
        $this->get('/')->assertOk()->assertSee('name="q"', false);
    }

    public function test_private_fields_emails_and_birthdays_are_not_searchable(): void
    {
        $user = $this->user();
        $data = $this->data($user);
        $owner = $this->user($data['class']);
        $owner->profile()->create(['nickname' => 'SandiRahasia', 'bio' => 'HobiRahasia', 'profile_visibility' => 'private', 'birth_date' => '1991-05-12']);
        $component = Livewire::actingAs($user)->test(Index::class)->set('category', 'members');
        foreach (['SandiRahasia', 'HobiRahasia', $owner->email, '1991-05-12'] as $q) {
            $component->set('q', $q)->call('search')->assertSee('0 hasil pada kategori ini');
        }
        $component->set('q', 'Nara')->call('search')->assertSee('Nara Wijaya')->assertDontSee('SandiRahasia');
    }

    public function test_cross_class_deleted_and_inactive_data_are_not_returned(): void
    {
        $other = $this->user();
        $this->data($other);
        $user = $this->user();
        $component = Livewire::actingAs($user)->test(Index::class)->set('q', 'Piknik');
        foreach (['stories', 'announcements', 'events', 'polls'] as $category) {
            $component->set('category', $category)->assertSee('0 hasil pada kategori ini');
        }
        $data = $this->data($user);
        $data['post']->delete();
        $data['event']->delete();
        $component->set('category', 'stories')->assertSee('0 hasil pada kategori ini');
        $component->set('category', 'events')->assertSee('0 hasil pada kategori ini');
        ClassModel::whereKey($data['class'])->update(['is_active' => false]);
        $component->call('search')->assertForbidden();
    }

    public function test_literal_wildcards_validation_pagination_and_escaped_content(): void
    {
        $user = $this->user();
        $data = $this->data($user);
        $data['post']->update(['content' => 'Diskon 10% <script>alert(1)</script>']);
        $component = Livewire::actingAs($user)->test(Index::class)->set('q', 'x')->call('search')->assertHasErrors('q');
        $component->set('q', str_repeat('x', 101))->call('search')->assertHasErrors('q');
        $component->set('q', '10%')->call('search')->assertHasNoErrors()->assertSee('1 hasil pada kategori ini')->assertDontSee('<script>alert(1)</script>', false);
        $component->set('q', '10_')->call('search')->assertSee('0 hasil pada kategori ini');
        for ($i = 0; $i < 11; $i++) {
            Post::create(['class_id' => $data['class'], 'user_id' => $user->id, 'content' => 'Tes pagination '.$i]);
        }
        $component->set('q', 'pagination')->call('search')->assertSee('11 hasil pada kategori ini')->assertViewHas('cards', fn ($cards) => $cards->count() === 10);
        $component->call('nextPage')->assertViewHas('cards', fn ($cards) => $cards->count() === 1);
        $component->set('category', 'events')->assertSet('paginators.page', 1);
    }

    public function test_guest_is_redirected(): void
    {
        $this->get('/search?q=piknik')->assertRedirect('/login');
    }
}
