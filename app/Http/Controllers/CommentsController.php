<?php

namespace App\Http\Controllers;

use App\Post;
use App\Comment;
use Illuminate\Http\Request;
use Auth;

class CommentsController extends Controller
{
    public function store(Post $post)
    {
    	$this->validate(request(), ['body' => 'required|min:2']);

    	// add a comment to a post
    	$post->addComment(request('body'));

    	return back();
    }

    public function votePost(Post $post, $id)
    {
        $user = Auth::user();
        $comment;

        foreach ($post->comments as $c)
        {
            if ($c->id == request('id'))
            {
                $comment = $c;
                $image = $c->votable;
                if ($comment->submitVote(request('voteValue'), $user, request('id'), 'App\Comment'))
                {
                    $comment->vote_count =  $comment->countVotes(request('id'), 'App\Comment');
                    $comment->save();
                }
               
            }
        }

        return 1000;
    }
}
