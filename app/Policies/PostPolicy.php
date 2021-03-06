<?php

namespace App\Policies;

use App\User;
use App\Post;
use App\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function edit(User $user, Post $post)
    {
        return $user->id === $post->user_id;
    }

    public function deleteComment(User $user, Comment $comment)
    {
        return $user->id === $comment->user_id;
    }

    public function editComment(User $user, Comment $comment)
    {
        return $user->id === $comment->user->id;
    }
}
