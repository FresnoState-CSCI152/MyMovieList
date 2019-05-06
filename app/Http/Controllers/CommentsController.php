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

        // iterate through list of comments that belong to the current post
        // when a match is found, then proceed to update the current vote
        foreach ($post->comments as $c)
        {
            if ($c->id == request('id'))
            {
                $comment = $c;
                $image = $c->votable;
                // attempt to submit a vote
                if ($comment->submitVote(request('voteValue'), $user, request('id'), 'App\Comment'))
                {
                    // when vote is updated successfully, calculate current amount of votes for comment
                    $comment->vote_count =  $comment->countVotes(request('id'), 'App\Comment');
                    $comment->save();
                }
               
            }
        }

        return 0;
    }

    public function deleteComment($id)
    {
        Comment::where('id', '=', request('id'))->delete();
    }

    public function editComment(Request $request)
    {
        $user = Auth::user();
        $comment = Comment::where('id', '=', request('id'))->where('user_id', '=', $user->id);
        $comment->body = $request('body');
        $comment->save();
    }
}
