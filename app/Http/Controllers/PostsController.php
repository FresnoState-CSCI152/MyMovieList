<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostsController extends Controller
{
    // constructor function
    public function __construct()
    {
        // must be logged in in order to create post
        $this->middleware('auth')->except(['index', 'show']);
            // except any guest can view all posts (index)
            // and any guest can view a single post (show)
    }

    public function index()
    {
        $posts = Post::latest()->get();

    	return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
    	return view('posts.show', compact('post'));
    }

    public function create()
    {
    	return view('posts.create');
    }

    public function store()
    {
        $this->validate(request(), [
            'title' => 'required',
            'body' => 'required'
        ]);

        auth()->user()->publish(
            new Post(request(['title', 'body']))
        );

        // redirect to home page
        return redirect('discussion');
    }

    public function edit(Post $post)
    {        
        return view ('posts.edit',compact('post'));
    }

    public function update(Post $post)
    {
        $post->title = request('title');
        $post->body = request('body');
        $post->save();

        return redirect('discussion');
    }
}
