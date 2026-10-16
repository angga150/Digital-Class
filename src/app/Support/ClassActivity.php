<?php

namespace App\Support;

use App\Models\ClassMember;
use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Support\Facades\DB;

class ClassActivity
{
    public static function liked(Post $post, int $actor): void
    {
        self::record($post, $actor, 'like', 'like:'.$post->id.':'.$actor, [$post->user_id]);
    }

    public static function commented(Post $post, PostComment $comment): void
    {
        self::record($post, $comment->user_id, 'comment', 'comment:'.$comment->id, [$post->user_id], $comment->id);
    }

    public static function announced(Post $post): void
    {
        $recipients = ClassMember::where('class_id', $post->class_id)->pluck('user_id')->all();
        self::record($post, $post->user_id, 'announcement', 'announcement:'.$post->id, $recipients);
    }

    private static function record(Post $post, int $actor, string $kind, string $key, array $recipients, ?int $commentId = null): void
    {
        // Called inside the original action's transaction. Only current classmates receive rows.
        $recipients = ClassMember::where('class_id', $post->class_id)->whereIn('user_id', $recipients)->where('user_id', '!=', $actor)->pluck('user_id');
        $now = now();
        $rows = $recipients->map(fn ($recipient) => [
            'class_id' => $post->class_id, 'user_id' => $recipient, 'actor_id' => $actor,
            'post_id' => $post->id, 'comment_id' => $commentId, 'kind' => $kind, 'event_key' => $key,
            'created_at' => $now, 'updated_at' => $now,
        ]);
        // Explicit recipient IDs are derived above, not accepted from clients. No copied content or names.
        foreach ($rows->chunk(50) as $chunk) {
            DB::table('class_notifications')->insertOrIgnore($chunk->all());
        }
    }
}
