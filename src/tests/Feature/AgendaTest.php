<?php

namespace Tests\Feature;

use App\Livewire\Agenda\Detail;
use App\Livewire\Agenda\Index;
use App\Livewire\Agenda\NextEvent;
use App\Models\ClassMember;
use App\Models\ClassModel;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

class AgendaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->travelTo(Carbon::parse('2026-10-15 08:00:00', 'UTC'));
    }

    private function user(string $role = 'admin', ?ClassModel $class = null, bool $verified = true): User
    {
        $class ??= ClassModel::create(['name' => 'Kelas '.uniqid(), 'code' => uniqid(), 'join_code' => uniqid(), 'is_active' => true]);
        $user = User::factory()->create(['email_verified_at' => $verified ? now() : null]);
        ClassMember::create(['class_id' => $class->id, 'user_id' => $user->id, 'role' => $role]);

        return $user;
    }

    private function classroom(User $user): ClassModel
    {
        return $user->classMemberships()->withoutGlobalScopes()->first()->classModel;
    }

    private function event(User $user, array $attributes = []): Event
    {
        return Event::create(array_merge([
            'class_id' => $this->classroom($user)->id, 'created_by' => $user->id,
            'title' => 'Piknik kelas', 'event_date' => now()->addDay(),
            'ends_at' => now()->addDay()->addHours(2), 'type' => 'social',
        ], $attributes));
    }

    private function missing(callable $action): void
    {
        try {
            $action();
            $this->fail('Cross-class or deleted event should be missing.');
        } catch (ModelNotFoundException $exception) {
            $this->assertNotEmpty($exception->getModel());
        }
    }

    public function test_admin_can_create_event_with_wib_input_stored_as_utc(): void
    {
        $user = $this->user();
        Livewire::actingAs($user)->test(Index::class)->call('create')
            ->set('title', 'Diskusi bareng')->set('starts', '2026-10-16T09:00')
            ->set('ends', '2026-10-16T11:00')->set('link', 'https://example.com/meeting')
            ->call('save')->assertHasNoErrors()->assertSet('formOpen', false)->assertSee('Diskusi bareng');
        $event = Event::firstOrFail();
        $this->assertSame('2026-10-16 02:00:00', $event->event_date->format('Y-m-d H:i:s'));
        $this->assertSame('2026-10-16 04:00:00', $event->ends_at->format('Y-m-d H:i:s'));
        $this->assertSame($user->id, $event->created_by);
        $this->get('/events')->assertOk()->assertSee('Diskusi bareng');
        $this->get('/events/'.$event->id)->assertOk()->assertSee('09:00 WIB')->assertSee('Lokasi menyusul');
        $this->get('/')->assertOk()->assertSee('Diskusi bareng');
    }

    public function test_only_verified_admin_can_create_update_cancel_or_delete(): void
    {
        $admin = $this->user();
        $event = $this->event($admin);
        $member = $this->user('member', $this->classroom($admin));
        Livewire::actingAs($member)->test(Index::class)->call('create')->assertForbidden();
        Livewire::actingAs($member)->test(Index::class)->call('save')->assertForbidden();
        Livewire::actingAs($member)->test(Index::class)->call('edit', $event->id)->assertForbidden();
        Livewire::actingAs($member)->test(Index::class)->call('cancel', $event->id)->assertForbidden();
        Livewire::actingAs($member)->test(Index::class)->call('delete', $event->id)->assertForbidden();
        $unverified = $this->user('admin', $this->classroom($admin), false);
        Livewire::actingAs($unverified)->test(Index::class)->call('create')->assertForbidden();
        $this->assertDatabaseHas('events', ['id' => $event->id, 'cancelled_at' => null, 'deleted_at' => null]);
    }

    public function test_invalid_dates_past_wib_dates_and_unsafe_links_are_rejected(): void
    {
        $form = Livewire::actingAs($this->user())->test(Index::class)->call('create')->set('title', 'Tes');
        $form->set('starts', '2026-02-30T10:00')->set('ends', '2026-10-16T11:00')->call('save')->assertHasErrors('starts');
        $form->set('starts', '2026-10-15T14:59')->call('save')->assertHasErrors('starts');
        $form->set('starts', '2026-10-16T12:00')->call('save')->assertHasErrors('ends');
        $form->set('ends', '2026-10-16T13:00')->set('link', 'javascript:alert(1)')->call('save')->assertHasErrors('link');
        $form->set('link', 'https://example.com/'.str_repeat('a', 260))->call('save')->assertHasErrors('link');
        $this->assertDatabaseCount('events', 0);
    }

    public function test_edit_round_trips_wib_and_rechecks_permission(): void
    {
        $admin = $this->user();
        $event = $this->event($admin);
        Livewire::actingAs($admin)->test(Index::class)->call('edit', $event->id)
            ->assertSet('starts', '2026-10-16T15:00')->set('title', 'Judul baru')->call('save')->assertHasNoErrors();
        $this->assertSame('2026-10-16 08:00:00', $event->fresh()->event_date->format('Y-m-d H:i:s'));
        $form = Livewire::actingAs($admin)->test(Index::class)->call('edit', $event->id)->set('title', 'Tidak boleh');
        ClassMember::where('user_id', $admin->id)->update(['role' => 'member']);
        $form->call('save')->assertForbidden();
        $this->assertSame('Judul baru', $event->fresh()->title);
    }

    public function test_timestamp_range_is_validated_before_database_write(): void
    {
        $form = Livewire::actingAs($this->user())->test(Index::class)->call('create')->set('title', 'Agenda batas tanggal');
        $form->set('starts', '2038-01-19T10:13')->set('ends', '2038-01-19T10:15')->call('save')->assertHasErrors('ends');
        $this->assertDatabaseCount('events', 0);
        $form->set('starts', '2039-01-19T10:13')->set('ends', '2039-01-19T10:15')->call('save')->assertHasErrors(['starts', 'ends']);
        $this->assertDatabaseCount('events', 0);
        $form->set('starts', '2038-01-19T10:13')->set('ends', '2038-01-19T10:14')->call('save')->assertHasNoErrors();
        $this->assertSame('2038-01-19 03:14:00', Event::firstOrFail()->ends_at->format('Y-m-d H:i:s'));
    }

    public function test_status_and_countdown_change_at_exact_start_and_end(): void
    {
        $user = $this->user();
        $this->actingAs($user);
        $event = $this->event($user, ['event_date' => now()->addMinutes(90), 'ends_at' => now()->addHours(3)]);
        $this->assertSame('upcoming', $event->status);
        $this->assertSame('1 jam 30 menit lagi', $event->countdown);
        $this->travel(90)->minutes();
        $this->assertSame('ongoing', $event->fresh()->status);
        $this->assertSame(1, Event::forStatus('ongoing')->count());
        $this->travel(90)->minutes();
        $this->assertSame('completed', $event->fresh()->status);
        $this->assertSame(1, Event::forStatus('completed')->count());
        $this->assertSame(0, Event::forStatus('upcoming')->count());
    }

    public function test_filters_cover_cancelled_and_legacy_events_without_end_times(): void
    {
        $user = $this->user();
        $this->event($user, ['title' => 'Agenda lama', 'event_date' => now()->subDay(), 'ends_at' => null]);
        $this->event($user, ['title' => 'Sudah batal', 'cancelled_at' => now()]);
        Livewire::actingAs($user)->test(Index::class)->assertDontSee('Agenda lama')->assertDontSee('Sudah batal')
            ->set('filter', 'completed')->assertSee('Agenda lama')->assertDontSee('Sudah batal')
            ->set('filter', 'cancelled')->assertSee('Sudah batal')->assertDontSee('Agenda lama');
    }

    public function test_next_event_widget_ignores_cancelled_past_and_other_class_events(): void
    {
        $user = $this->user();
        $this->event($user, ['title' => 'Lewat', 'event_date' => now()->subDay(), 'ends_at' => now()->subHours(20)]);
        $this->event($user, ['title' => 'Batal', 'event_date' => now()->addHour(), 'cancelled_at' => now()]);
        $this->event($this->user(), ['title' => 'Kelas lain', 'event_date' => now()->addHour()]);
        $nearest = $this->event($user, ['title' => 'Yang terdekat']);
        Livewire::actingAs($user)->test(NextEvent::class)->assertSee('Yang terdekat')->assertDontSee('Kelas lain')->assertDontSee('Lewat')->assertDontSee('Batal');
        $nearest->delete();
        Livewire::actingAs($user)->test(NextEvent::class)->assertSee('Belum ada agenda mendatang');
    }

    public function test_rsvp_is_idempotent_and_only_counts_going(): void
    {
        $admin = $this->user();
        $event = $this->event($admin);
        $member = $this->user('member', $this->classroom($admin));
        $detail = Livewire::actingAs($member)->test(Detail::class, ['event' => $event]);
        $detail->call('respond', 'going')->call('respond', 'going')->assertHasNoErrors()->assertSee('1 teman konfirmasi hadir');
        $this->assertDatabaseCount('event_rsvps', 1);
        $this->assertSame(1, $event->fresh()->rsvp_count);
        $detail->call('respond', 'maybe')->assertSee('0 teman konfirmasi hadir');
        $this->assertDatabaseHas('event_rsvps', ['event_id' => $event->id, 'user_id' => $member->id, 'status' => 'maybe']);
        $detail->call('respond', 'not_going')->assertHasNoErrors();
        $detail->call('respond', 'clear')->call('respond', 'clear');
        $this->assertDatabaseCount('event_rsvps', 0);
        $this->assertSame(0, $event->fresh()->rsvp_count);
    }

    public function test_rsvp_rejects_invalid_status_and_unverified_accounts(): void
    {
        $user = $this->user();
        $event = $this->event($user);
        Livewire::actingAs($user)->test(Detail::class, ['event' => $event])->call('respond', 'admin')->assertHasErrors('choice');
        $unverified = $this->user('member', $this->classroom($user), false);
        $this->actingAs($unverified)->get('/events/'.$event->id)->assertOk()->assertSee('Verifikasi email');
        Livewire::actingAs($unverified)->test(Detail::class, ['event' => $event])->call('respond', 'going')->assertForbidden();
        $this->assertDatabaseCount('event_rsvps', 0);
    }

    public function test_rsvp_closes_on_start_and_cancellation_even_on_stale_page(): void
    {
        $admin = $this->user();
        $event = $this->event($admin);
        $detail = Livewire::actingAs($admin)->test(Detail::class, ['event' => $event]);
        $event->update(['cancelled_at' => now()]);
        $detail->call('respond', 'going')->assertForbidden();
        $event->update(['cancelled_at' => null, 'event_date' => now(), 'ends_at' => now()->addHour()]);
        Livewire::actingAs($admin)->test(Detail::class, ['event' => $event])->call('respond', 'going')->assertForbidden();
        $this->assertDatabaseCount('event_rsvps', 0);
    }

    public function test_cancelling_retains_event_and_rsvps_but_delete_is_soft(): void
    {
        $admin = $this->user();
        $event = $this->event($admin);
        Livewire::actingAs($admin)->test(Detail::class, ['event' => $event])->call('respond', 'going');
        Livewire::actingAs($admin)->test(Index::class)->call('cancel', $event->id)->set('filter', 'cancelled')->assertSee('Piknik kelas');
        $this->assertNotNull($event->fresh()->cancelled_at);
        $this->assertDatabaseCount('event_rsvps', 1);
        $this->get('/events/'.$event->id)->assertOk()->assertSee('Kegiatan ini dibatalkan');
        Livewire::actingAs($admin)->test(Index::class)->call('delete', $event->id);
        $this->assertSoftDeleted('events', ['id' => $event->id]);
        $this->get('/events/'.$event->id)->assertNotFound();
        $this->assertDatabaseCount('event_rsvps', 1);
    }

    public function test_cross_class_route_and_actions_are_inaccessible(): void
    {
        $owner = $this->user();
        $event = $this->event($owner, ['title' => 'Agenda rahasia']);
        $other = $this->user();
        $this->actingAs($other)->get('/events/'.$event->id)->assertNotFound();
        Livewire::actingAs($other)->test(Index::class)->assertDontSee('Agenda rahasia');
        $this->missing(fn () => Livewire::actingAs($other)->test(Index::class)->call('edit', $event->id));
        $this->missing(fn () => Livewire::actingAs($other)->test(Index::class)->call('cancel', $event->id));
        $this->missing(fn () => Livewire::actingAs($other)->test(Index::class)->call('delete', $event->id));
        Livewire::actingAs($other)->test(Detail::class, ['event' => $event])->assertForbidden();
    }

    public function test_description_is_escaped_and_unsafe_legacy_link_not_rendered(): void
    {
        $user = $this->user();
        $event = $this->event($user, ['description' => '<script>alert("xss")</script>', 'link' => 'javascript:alert(1)']);
        $this->actingAs($user)->get('/events/'.$event->id)->assertOk()
            ->assertDontSee('<script>alert("xss")</script>', false)->assertSee('&lt;script&gt;', false)
            ->assertDontSee('href="javascript:', false)->assertDontSee('Buka tautan kegiatan');
    }
}
