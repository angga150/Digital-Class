<?php

namespace Tests\Feature;

use App\Livewire\Announcements\Featured;
use App\Livewire\Announcements\Index;
use App\Livewire\Stories\Feed;
use App\Models\ClassMember;
use App\Models\ClassModel;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AnnouncementsTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role = 'admin', ?int $classId = null, bool $verified = true): User
    {
        $classId ??= ClassModel::create(['name' => 'Kelas '.uniqid(), 'code' => uniqid(), 'join_code' => uniqid(), 'is_active' => true])->id;
        $user = User::factory()->create(['email_verified_at' => $verified ? now() : null]);
        ClassMember::create(['user_id' => $user->id, 'class_id' => $classId, 'role' => $role]);

        return $user;
    }

    private function announcement(User $user, array $attributes = []): Post
    {
        return Post::create(array_merge([
            'class_id' => $user->classMemberships()->withoutGlobalScopes()->first()->class_id,
            'user_id' => $user->id, 'type' => 'announcement', 'content' => 'Kabar kelas hari ini',
        ], $attributes));
    }

    private function missing(callable $callback): void
    {
        try {
            $callback();
            $this->fail('Post should not be found.');
        } catch (ModelNotFoundException $exception) {
            $this->assertSame(Post::class, $exception->getModel());
        }
    }

    public function test_admin_can_publish_edit_and_pin_with_persistence_across_pages(): void
    {
        $user = $this->user();
        Livewire::actingAs($user)->test(Index::class)->call('create')->set('body', '  Kumpul besok di aula  ')->set('pinned', true)
            ->call('save')->assertHasNoErrors()->assertSet('formOpen', false)->assertSee('Kumpul besok di aula');
        $post = Post::firstOrFail();
        $this->assertSame('announcement', $post->type);
        $this->assertSame($user->id, $post->user_id);
        $this->assertSame('Kumpul besok di aula', $post->content);
        $this->assertTrue($post->is_pinned);
        Livewire::test(Index::class)->call('edit', $post->id)->set('body', 'Pindah ke taman')->call('save')->assertHasNoErrors();
        $this->get('/')->assertOk()->assertSee('Pindah ke taman');
        $this->get('/announcements')->assertOk()->assertSee('Pindah ke taman');
        $this->get('/announcements/'.$post->id)->assertOk()->assertSee('Pindah ke taman');
        $this->get('/feed')->assertOk()->assertSee('Pengumuman kelas')->assertSee('Pindah ke taman');
    }

    public function test_only_verified_admin_can_mutate_but_members_can_read(): void
    {
        $admin = $this->user();
        $post = $this->announcement($admin);
        foreach ([$this->user('member', $post->class_id), $this->user('admin', $post->class_id, false)] as $user) {
            Livewire::actingAs($user)->test(Index::class)->call('create')->assertForbidden();
            Livewire::test(Index::class)->set('body', 'Injected')->call('save')->assertForbidden();
            Livewire::test(Index::class)->call('edit', $post->id)->assertForbidden();
            Livewire::test(Index::class)->call('pin', $post->id, true)->assertForbidden();
            Livewire::test(Index::class)->call('delete', $post->id)->assertForbidden();
            $this->get('/announcements/'.$post->id)->assertOk()->assertSee('Kabar kelas hari ini');
        }
        $this->assertDatabaseCount('posts', 1);
    }

    public function test_blank_and_long_content_are_rejected(): void
    {
        $component = Livewire::actingAs($this->user())->test(Index::class)->call('create');
        $component->set('body', '   ')->call('save')->assertHasErrors('body');
        $component->set('body', str_repeat('x', 2001))->call('save')->assertHasErrors('body');
        $this->assertDatabaseCount('posts', 0);
    }

    public function test_banner_prioritizes_pins_and_unpin_is_idempotent(): void
    {
        $user = $this->user();
        $old = $this->announcement($user, ['content' => 'Penting lama', 'is_pinned' => true, 'created_at' => now()->subDays(2)]);
        $new = $this->announcement($user, ['content' => 'Terbaru biasa']);
        $this->actingAs($user);
        Livewire::test(Featured::class)->assertSee('Penting lama')->assertDontSee('Terbaru biasa');
        Livewire::test(Index::class)->set('filter', 'pinned')->assertSee('Penting lama')->assertDontSee('Terbaru biasa')
            ->call('pin', $old->id, false)->call('pin', $old->id, false);
        $this->assertFalse($old->fresh()->is_pinned);
        Livewire::test(Featured::class)->assertSee('Terbaru biasa')->assertDontSee('Penting lama');
        Livewire::test(Index::class)->call('pin', $new->id, true);
        $this->assertTrue($new->fresh()->is_pinned);
    }

    public function test_deletion_hides_banner_detail_feed_but_preserves_post_and_comments(): void
    {
        $user = $this->user();
        $post = $this->announcement($user);
        $this->actingAs($user);
        $post->comments()->create(['user_id' => $user->id, 'content' => 'Komentar lama']);
        $stale = Livewire::test(Index::class)->call('edit', $post->id);
        Livewire::test(Index::class)->call('delete', $post->id);
        $this->assertSoftDeleted('posts', ['id' => $post->id]);
        $this->assertDatabaseHas('post_comments', ['post_id' => $post->id, 'content' => 'Komentar lama']);
        $this->get('/announcements/'.$post->id)->assertNotFound();
        Livewire::test(Featured::class)->assertSee('Belum ada pengumuman.');
        Livewire::test(Feed::class)->assertDontSee('Kabar kelas hari ini');
        $this->missing(fn () => $stale->set('body', 'Pulihkan diam-diam')->call('save'));
    }

    public function test_cross_class_ids_and_non_announcement_posts_are_rejected(): void
    {
        $owner = $this->user();
        $other = $this->announcement($owner, ['content' => 'Rahasia kelas lain']);
        $viewer = $this->user();
        $story = $this->announcement($viewer, ['type' => 'discussion', 'content' => 'Cerita biasa']);
        $this->actingAs($viewer);
        $this->get('/announcements/'.$other->id)->assertNotFound();
        $this->get('/announcements/'.$story->id)->assertNotFound();
        Livewire::test(Index::class)->assertDontSee('Rahasia kelas lain')->assertDontSee('Cerita biasa');
        foreach ([$other, $story] as $post) {
            $this->missing(fn () => Livewire::test(Index::class)->call('edit', $post->id));
            $this->missing(fn () => Livewire::test(Index::class)->call('pin', $post->id, true));
            $this->missing(fn () => Livewire::test(Index::class)->call('delete', $post->id));
        }
        Livewire::test(Featured::class)->assertSee('Belum ada pengumuman.');
    }

    public function test_demoted_author_cannot_change_announcements_through_feed(): void
    {
        $user = $this->user();
        $post = $this->announcement($user);
        $this->actingAs($user);
        $board = Livewire::test(Index::class)->call('edit', $post->id);
        $feed = Livewire::test(Feed::class)->call('edit', $post->id);
        ClassMember::withoutGlobalScopes()->where('user_id', $user->id)->update(['role' => 'member']);
        $board->set('body', 'No longer admin')->call('save')->assertForbidden();
        $feed->set('editBody', 'No longer admin')->call('saveEdit')->assertForbidden();
        Livewire::test(Feed::class)->call('deletePost', $post->id)->assertForbidden();
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'content' => 'Kabar kelas hari ini', 'deleted_at' => null]);
    }

    public function test_another_admin_can_manage_and_inactive_class_rechecks_permissions(): void
    {
        $owner = $this->user();
        $post = $this->announcement($owner);
        $admin = $this->user('admin', $post->class_id);
        $component = Livewire::actingAs($admin)->test(Index::class)->call('edit', $post->id)->set('body', 'Diperbarui pengurus lain')->call('save')->assertHasNoErrors();
        $this->assertSame($owner->id, $post->fresh()->user_id);
        $component->call('edit', $post->id);
        ClassModel::whereKey($post->class_id)->update(['is_active' => false]);
        $component->call('save')->assertForbidden();
    }

    public function test_login_escaping_and_author_privacy(): void
    {
        $this->get('/announcements')->assertRedirect('/login');
        $owner = $this->user();
        $owner->profile()->create(['nickname' => 'Nickname Rahasia', 'profile_visibility' => 'private']);
        $post = $this->announcement($owner, ['content' => '<script>alert(1)</script>']);
        $member = $this->user('member', $post->class_id);
        $this->actingAs($member)->get('/announcements/'.$post->id)->assertOk()->assertSee('&lt;script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false)->assertDontSee('Nickname Rahasia')->assertDontSee($owner->email);
        Livewire::test(Featured::class)->assertDontSee('<script>alert(1)</script>', false);
    }
}
