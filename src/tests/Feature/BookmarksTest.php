<?php

namespace Tests\Feature;

use App\Livewire\Stories\Feed;
use App\Models\ClassMember;
use App\Models\ClassModel;
use App\Models\Post;
use App\Models\PostBookmark;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BookmarksTest extends TestCase
{
    use RefreshDatabase;

    private function user(?int $classId = null, bool $verified = true): User
    {
        $classId ??= ClassModel::create(['name' => 'Kelas', 'code' => uniqid(), 'join_code' => uniqid(), 'is_active' => true])->id;
        $user = User::factory()->create(['email_verified_at' => $verified ? now() : null]);
        ClassMember::create(['user_id' => $user->id, 'class_id' => $classId, 'role' => 'member']);

        return $user;
    }

    private function story(User $user): Post
    {
        return Post::create(['class_id' => $user->classMemberships()->withoutGlobalScopes()->first()->class_id, 'user_id' => $user->id, 'content' => 'Baca ulang cerita ini', 'type' => 'discussion']);
    }

    public function test_save_is_idempotent_private_and_can_be_removed(): void
    {
        $owner = $this->user();
        $post = $this->story($owner);
        $viewer = $this->user($post->class_id);
        Livewire::actingAs($viewer)->test(Feed::class)->call('setBookmark', $post->id, true)->call('setBookmark', $post->id, true)->set('filter', 'saved')->assertSee($post->content);
        $this->assertDatabaseCount('post_bookmarks', 1);
        $this->get('/feed?filter=saved')->assertOk()->assertSee($post->content);
        Livewire::actingAs($owner)->test(Feed::class)->set('filter', 'saved')->assertDontSee($post->content)->assertSee('Belum ada cerita tersimpan.');
        $this->assertSame(0, PostBookmark::count());
        Livewire::test(Feed::class)->call('setBookmark', $post->id, false);
        $this->assertDatabaseCount('post_bookmarks', 1);
        Livewire::actingAs($viewer)->test(Feed::class)->call('setBookmark', $post->id, false)->set('filter', 'saved')->assertDontSee($post->content);
        $this->assertDatabaseCount('post_bookmarks', 0);
    }

    public function test_deleted_posts_disappear_from_saved_list_and_permalink(): void
    {
        $user = $this->user();
        $post = $this->story($user);
        Livewire::actingAs($user)->test(Feed::class)->call('setBookmark', $post->id, true);
        $post->delete();
        Livewire::test(Feed::class)->set('filter', 'saved')->assertDontSee($post->content);
        $this->get('/feed/'.$post->id)->assertNotFound();
        $this->assertDatabaseCount('post_bookmarks', 1);
        $this->assertSame(0, PostBookmark::count());
    }

    public function test_cross_class_and_unverified_actions_fail(): void
    {
        $owner = $this->user();
        $post = $this->story($owner);
        $unverified = $this->user($post->class_id, false);
        Livewire::actingAs($unverified)->test(Feed::class)->call('setBookmark', $post->id, true)->assertForbidden();
        $this->actingAs($this->user())->get('/feed/'.$post->id)->assertNotFound();
        try {
            Livewire::test(Feed::class)->call('setBookmark', $post->id, true);
            $this->fail('Cross-class post must be missing.');
        } catch (ModelNotFoundException $exception) {
            $this->assertSame(Post::class, $exception->getModel());
        }
        $this->assertDatabaseCount('post_bookmarks', 0);
    }

    public function test_permalink_focuses_post_even_when_older_than_first_page_and_deletes_cleanly(): void
    {
        $user = $this->user();
        $old = $this->story($user);
        for ($i = 0; $i < 12; $i++) {
            $this->story($user)->update(['content' => 'Cerita lain '.$i]);
        }
        $this->actingAs($user)->get('/feed/'.$old->id)->assertOk()->assertSee($old->content)->assertDontSee('Cerita lain');
        Livewire::test(Feed::class, ['postId' => $old->id])->call('deletePost', $old->id)->assertRedirect(route('feed'));
    }
}
