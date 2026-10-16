<?php

namespace Tests\Feature;

use App\Livewire\Announcements\Index as Announcements;
use App\Livewire\Notifications\Bell;
use App\Livewire\Notifications\Index;
use App\Livewire\Stories\Feed;
use App\Models\ClassMember;
use App\Models\ClassModel;
use App\Models\ClassNotification;
use App\Models\Post;
use App\Models\User;
use App\Support\ClassActivity;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    private function user(?int $classId = null, string $role = 'member'): User
    {
        $classId ??= ClassModel::create(['name' => 'Kelas', 'code' => uniqid(), 'join_code' => uniqid(), 'is_active' => true])->id;
        $user = User::factory()->create();
        ClassMember::create(['user_id' => $user->id, 'class_id' => $classId, 'role' => $role]);

        return $user;
    }

    private function story(User $user): Post
    {
        return Post::create(['user_id' => $user->id, 'class_id' => $user->classMemberships()->withoutGlobalScopes()->first()->class_id, 'content' => 'Cerita untuk teman', 'type' => 'discussion']);
    }

    private function like(User $actor, Post $post): void
    {
        Livewire::actingAs($actor)->test(Feed::class)->call('setLike', $post->id, true)->assertHasNoErrors();
    }

    public function test_like_notifies_owner_once_and_unlike_hides_it(): void
    {
        $owner = $this->user();
        $post = $this->story($owner);
        $actor = $this->user($post->class_id);
        $this->like($actor, $post);
        $this->like($actor, $post);
        $this->assertDatabaseCount('class_notifications', 1);
        Livewire::actingAs($owner)->test(Index::class)->assertSee('Seseorang menyukai ceritamu')->assertSee('1 belum dibaca.');
        Livewire::test(Bell::class)->assertSee('Notifikasi, 1 belum dibaca');
        Livewire::actingAs($actor)->test(Index::class)->assertDontSee('Seseorang menyukai ceritamu');
        Livewire::test(Feed::class)->call('setLike', $post->id, false);
        Livewire::actingAs($owner)->test(Index::class)->assertDontSee('Seseorang menyukai ceritamu')->assertSee('0 belum dibaca.');
        $this->like($actor, $post);
        $this->assertDatabaseCount('class_notifications', 1);
    }

    public function test_comment_notification_opens_comments_and_disappears_when_deleted(): void
    {
        $owner = $this->user();
        $post = $this->story($owner);
        $actor = $this->user($post->class_id);
        Livewire::actingAs($actor)->test(Feed::class)->call('openComments', $post->id)->set('commentBody', 'Komentar baru yang baik')->call('addComment')->assertHasNoErrors();
        $this->actingAs($owner);
        $notification = ClassNotification::firstOrFail();
        Livewire::test(Index::class)->call('open', $notification->id)->assertRedirect(route('feed.show', ['postId' => $post->id, 'comments' => 1]));
        $this->assertNotNull($notification->fresh()->read_at);
        $this->get('/feed/'.$post->id.'?comments=1')->assertOk()->assertSee('Komentar baru yang baik');
        $post->comments()->first()->delete();
        Livewire::test(Index::class)->assertDontSee('Ada komentar baru di ceritamu');
        $this->assertDatabaseCount('class_notifications', 1);
    }

    public function test_self_actions_and_bookmarks_do_not_notify(): void
    {
        $user = $this->user();
        $post = $this->story($user);
        $this->like($user, $post);
        Livewire::test(Feed::class)->call('setBookmark', $post->id, true)->call('openComments', $post->id)->set('commentBody', 'Tambahan sendiri')->call('addComment');
        $this->assertDatabaseCount('class_notifications', 0);
    }

    public function test_new_announcement_notifies_classmates_without_duplicate_edit_notifications(): void
    {
        $admin = $this->user(role: 'admin');
        $classId = $admin->classMemberships()->withoutGlobalScopes()->first()->class_id;
        $member = $this->user($classId);
        $other = $this->user();
        Livewire::actingAs($admin)->test(Announcements::class)->call('create')->set('body', 'Kabar baru untuk kelas')->call('save')->assertHasNoErrors();
        $post = Post::firstOrFail();
        ClassActivity::announced($post);
        Livewire::test(Announcements::class)->call('edit', $post->id)->set('body', 'Kabar diperbarui')->call('save');
        $this->assertDatabaseCount('class_notifications', 1);
        Livewire::actingAs($member)->test(Index::class)->assertSee('Pengumuman baru untuk kelasmu')->assertSee('Kabar diperbarui');
        Livewire::actingAs($other)->test(Index::class)->assertDontSee('Kabar diperbarui');
        $this->assertSame(0, ClassNotification::count());
    }

    public function test_read_actions_are_private_and_bulk_read_only_affects_current_recipient(): void
    {
        $owner = $this->user();
        $post = $this->story($owner);
        $actor = $this->user($post->class_id);
        $this->like($actor, $post);
        $id = DB::table('class_notifications')->where('user_id', $owner->id)->value('id');
        try {
            Livewire::test(Index::class)->call('read', $id, true);
            $this->fail('Another user cannot mark this notification read.');
        } catch (ModelNotFoundException $exception) {
            $this->assertSame(ClassNotification::class, $exception->getModel());
        }
        Livewire::test(Index::class)->call('readAll');
        $this->assertDatabaseHas('class_notifications', ['id' => $id, 'read_at' => null]);
        Livewire::actingAs($owner)->test(Index::class)->call('readAll')->assertSee('0 belum dibaca.')
            ->set('filter', 'unread')->assertDontSee('Seseorang menyukai ceritamu')
            ->call('read', $id, false)->assertSee('Seseorang menyukai ceritamu');
    }

    public function test_deleted_post_and_removed_membership_hide_notifications(): void
    {
        $owner = $this->user();
        $post = $this->story($owner);
        $actor = $this->user($post->class_id);
        $this->like($actor, $post);
        $this->actingAs($owner);
        $this->assertSame(1, ClassNotification::count());
        $post->delete();
        $this->assertSame(0, ClassNotification::count());
        Livewire::test(Bell::class)->assertSee('Notifikasi, 0 belum dibaca');
        $post->restore();
        ClassMember::withoutGlobalScopes()->where('user_id', $owner->id)->delete();
        $this->assertSame(0, ClassNotification::count());
        $this->get('/notifications')->assertRedirect('/join');
    }

    public function test_actor_private_data_is_not_copied_or_rendered_and_content_is_escaped(): void
    {
        $owner = $this->user();
        $post = $this->story($owner);
        $post->update(['content' => '<script>alert(1)</script>']);
        $actor = $this->user($post->class_id);
        $actor->profile()->create(['nickname' => 'NamaRahasia', 'profile_visibility' => 'private']);
        $this->like($actor, $post);
        $row = (array) DB::table('class_notifications')->first();
        $this->assertArrayNotHasKey('content', $row);
        $this->assertArrayNotHasKey('name', $row);
        $this->actingAs($owner)->get('/notifications')->assertOk()->assertSee('&lt;script&gt;', false)->assertDontSee('<script>alert(1)</script>', false)->assertDontSee('NamaRahasia')->assertDontSee($actor->email);
    }
}
