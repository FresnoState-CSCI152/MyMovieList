<?php

namespace App;
use App\Policies\PostPolicy;

class Post extends Model
{
    protected $fillable = ['user_id', 'title', 'body', 'vote_count'];
    use Votable;

    public function comments()
    {
    	return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
    }

    public function user() // $post->user->name (to get user associated with post)
    {
    	return $this->belongsTo(User::class);
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }
    
}
