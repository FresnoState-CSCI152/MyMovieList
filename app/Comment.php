<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Votable;

class Comment extends Model
{
	protected $fillable = ['body', 'user_id', 'vote_count'];
	use Votable;

    public function post()
    {
    	return $this->belongsTo(Post::class);
    }

    public function user() // $comment->user->name (to get user associated with comment)
    {
    	return $this->belongsTo(User::class);
    }    

    public function replies()
    {
        return $this->hasMany(Comment::class,'post_id');
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }
}
