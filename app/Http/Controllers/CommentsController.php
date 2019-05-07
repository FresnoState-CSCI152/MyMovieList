<?php

namespace App\Http\Controllers;

use App\Post;
use App\Comment;
use Illuminate\Http\Request;
use Auth;

class CommentsController extends Controller
{
    public function store(Request $request)
    {
    	//$this->validate(request(), ['body' => 'required|min:2']);

    	// add a comment to a post
    	//$post->addComment(request('body'));
        $comment = new Comment;
        $comment->body = $request->body;
        $comment->user()->associate($request->user());
        $post = Post::find($request->post_id);
        $post->comments()->save($comment);
    	return back();
    }

    public function replyStore(Request $request)
    {
        $reply = new Comment();
        $reply->body = $request->body;
        $reply->user()->associate($request->user());
        $reply->parent_id = $request->comment_id;
        $post = Post::find($request->post_id);
        $post->comments()->save($reply);
        return back();
    }
    public function votePost(Post $post, $id)
    {
        $user = Auth::user();

        $comment_id = request('id');
        $vote = request('voteValue');

        $comment = Comment::find($comment_id);
        if ($comment->submitVote($vote, $user, $comment_id, 'App\Comment'))
        {
            $comment->vote_count = $comment->countVotes($comment_id, 'App\Comment');
            $comment->save();
            return 20;
        }

        return 0;
    }

    public function deleteComment($id)
    {
        Comment::where('id', '=', request('id'))->delete();
        return 1;
    }

    public function editComment(Request $request)
    {
        $user = Auth::user();
        $comment = Comment::where('id', '=', request('id'))->where('user_id', '=', $user->id);
        $comment->body = $request('body');
        $comment->save();
    }
}
