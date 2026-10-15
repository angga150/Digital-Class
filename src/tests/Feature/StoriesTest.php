<?php

namespace Tests\Feature;

use App\Livewire\Stories\Feed;
use App\Models\ClassMember;
use App\Models\ClassModel;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StoriesTest extends TestCase
{
    use RefreshDatabase;

    private function assertMissingStory(callable $action): void
    {
        try {
            $action();
            $this->fail('An inaccessible story must not be found.');
        } catch (ModelNotFoundException $exception) {
            $this->assertNotEmpty($exception->getModel());
        }
    }

    private function member(?ClassModel $class = null, string $role = 'member', bool $verified = true): User
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

    private function story(User $user, string $content = 'Cerita pertama'): Post
    {
        return Post::create(['class_id' => $this->classroom($user)->id, 'user_id' => $user->id, 'content' => $content, 'type' => 'discussion']);
    }

    public function test_story_persists_and_dashboard_displays_it(): void
    {
        $user = $this->member();
        Livewire::actingAs($user)->test(Feed::class)->set('body', 'Belajar bareng sore ini')
            ->call('publish')->assertHasNoErrors()->assertSet('body', '')->assertSee('Belajar bareng sore ini');
        $this->assertDatabaseHas('posts', ['user_id' => $user->id, 'content' => 'Belajar bareng sore ini']);
        $this->get('/')->assertOk()->assertSee('Belajar bareng sore ini');
        $this->get('/feed')->assertOk()->assertSee('Belajar bareng sore ini');
    }

    public function test_blank_and_overlong_stories_cannot_be_saved(): void
    {
        Livewire::actingAs($this->member())->test(Feed::class)->set('body', '   ')->call('publish')->assertHasErrors('body')
            ->set('body', str_repeat('x', 2001))->call('publish')->assertHasErrors('body');
        $this->assertDatabaseCount('posts', 0);
    }

    public function test_unverified_user_can_read_but_cannot_publish_like_or_comment(): void
    {
        $user = $this->member(verified: false);
        $post = $this->story($user);
        Livewire::actingAs($user)->test(Feed::class)->assertSee('Cerita pertama')->set('body', 'No')->call('publish')->assertForbidden();
        Livewire::actingAs($user)->test(Feed::class)->call('setLike', $post->id, true)->assertForbidden();
        Livewire::actingAs($user)->test(Feed::class)->call('openComments', $post->id)->set('commentBody', 'No')->call('addComment')->assertForbidden();
        $this->assertDatabaseCount('post_likes', 0);
        $this->assertDatabaseCount('post_comments', 0);
    }

    public function test_like_requests_are_idempotent_and_counts_remain_correct(): void
    {
        $user = $this->member();
        $post = $this->story($user);
        $feed = Livewire::actingAs($user)->test(Feed::class);
        $feed->call('setLike', $post->id, true)->call('setLike', $post->id, true)->assertHasNoErrors();
        $this->assertDatabaseCount('post_likes', 1);
        $this->assertSame(1, $post->fresh()->likes_count);
        $feed->call('setLike', $post->id, false)->call('setLike', $post->id, false);
        $this->assertDatabaseCount('post_likes', 0);
        $this->assertSame(0, $post->fresh()->likes_count);
    }

    public function test_only_owner_can_edit_and_other_members_cannot_delete(): void
    {
        $owner = $this->member();
        $other = $this->member($this->classroom($owner));
        $post = $this->story($owner);
        Livewire::actingAs($other)->test(Feed::class)->call('edit', $post->id)->assertForbidden();
        Livewire::actingAs($other)->test(Feed::class)->call('deletePost', $post->id)->assertForbidden();
        Livewire::actingAs($owner)->test(Feed::class)->call('edit', $post->id)->set('editBody', 'Sudah diubah')->call('saveEdit')->assertHasNoErrors();
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'content' => 'Sudah diubah', 'deleted_at' => null]);
    }

    public function test_owner_and_admin_can_soft_delete_posts(): void
    {
        $owner = $this->member();
        $post = $this->story($owner);
        Livewire::actingAs($owner)->test(Feed::class)->call('deletePost', $post->id)->assertDontSee('Cerita pertama');
        $this->assertSoftDeleted('posts', ['id' => $post->id]);
        $post = $this->story($owner);
        $admin = $this->member($this->classroom($owner), 'admin');
        Livewire::actingAs($admin)->test(Feed::class)->call('deletePost', $post->id);
        $this->assertSoftDeleted('posts', ['id' => $post->id]);
    }

    public function test_cross_class_ids_are_rejected_for_every_action(): void
    {
        $owner = $this->member();
        $other = $this->member();
        $post = $this->story($owner, 'Rahasia kelas lain');
        $comment = PostComment::create(['post_id' => $post->id, 'user_id' => $owner->id, 'content' => 'Komentar rahasia']);
        $this->assertMissingStory(fn () => Livewire::actingAs($other)->test(Feed::class)->assertDontSee('Rahasia kelas lain')->call('edit', $post->id));
        $this->assertMissingStory(fn () => Livewire::actingAs($other)->test(Feed::class)->call('setLike', $post->id, true));
        $this->assertMissingStory(fn () => Livewire::actingAs($other)->test(Feed::class)->call('openComments', $post->id));
        $this->assertMissingStory(fn () => Livewire::actingAs($other)->test(Feed::class)->call('deletePost', $post->id));
        $this->assertMissingStory(fn () => Livewire::actingAs($other)->test(Feed::class)->call('deleteComment', $comment->id));
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'deleted_at' => null]);
    }

    public function test_comments_persist_and_are_deleted_only_by_owner_or_admin(): void
    {
        $owner = $this->member();
        $other = $this->member($this->classroom($owner));
        $post = $this->story($owner);
        $feed = Livewire::actingAs($owner)->test(Feed::class)->call('openComments', $post->id)
            ->set('commentBody', 'Semangat ya!')->call('addComment')->assertSee('Semangat ya!');
        $comment = PostComment::firstOrFail();
        $this->assertSame(1, $post->fresh()->comments_count);
        Livewire::actingAs($other)->test(Feed::class)->call('deleteComment', $comment->id)->assertForbidden();
        Livewire::actingAs($owner)->test(Feed::class)->call('deleteComment', $comment->id)->assertHasNoErrors();
        $this->assertSoftDeleted('post_comments', ['id' => $comment->id]);
        $this->assertSame(0, $post->fresh()->comments_count);
    }

    public function test_deleted_post_rejects_stale_comment_form(): void
    {
        $user = $this->member();
        $post = $this->story($user);
        $feed = Livewire::actingAs($user)->test(Feed::class)->call('openComments', $post->id)->set('commentBody', 'Terlambat');
        $post->delete();
        $this->assertMissingStory(fn () => $feed->call('addComment'));
        $this->assertDatabaseCount('post_comments', 0);
    }

    public function test_feed_does_not_leak_private_profile_and_escapes_content(): void
    {
        $owner = $this->member();
        $owner->profile()->create(['nickname' => 'NicknamePrivat', 'profile_visibility' => 'private']);
        $post = $this->story($owner, '<script>alert("xss")</script>');
        $other = $this->member($this->classroom($owner));
        $this->actingAs($other)->get('/feed')->assertOk()->assertDontSee('NicknamePrivat')->assertDontSee('<script>alert("xss")</script>', false)->assertSee('&lt;script&gt;', false);
    }

    public function test_load_more_and_mine_filter(): void
    {
        $user = $this->member();
        for ($i = 1; $i <= 12; $i++) {
            $this->story($user, 'Cerita nomor '.$i);
        }
        $other = $this->member($this->classroom($user));
        $this->story($other, 'Cerita teman lain');
        Livewire::actingAs($user)->test(Feed::class)->assertSee('Cerita teman lain')->assertDontSee('Cerita nomor 1</p>', false)
            ->call('loadMore')->assertSee('Cerita nomor 1')
            ->set('filter', 'mine')->assertDontSee('Cerita teman lain');
    }

    public function test_publish_is_rate_limited(): void
    {
        $feed = Livewire::actingAs($this->member())->test(Feed::class);
        for ($i = 0; $i < 5; $i++) {
            $feed->set('body', 'Cerita '.$i)->call('publish')->assertHasNoErrors();
        }
        $feed->set('body', 'Keenam')->call('publish')->assertHasErrors('body');
        $this->assertDatabaseCount('posts', 5);
    }
}
