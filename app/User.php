<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function post()
    {
        return $this->hasMany(Post::class);
    }

    public function publish(Post $post)
    {
        // create a new post using the request data and save to database
        $this->post()->save($post);
    }

    public function publicProfile()
    {
        return $this->belongsToMany('App\User', 'user_friend', 'user_id', 'public_id')->withTimestamps();
    }

    public function friends()
    {
        return $this->belongsToMany('App\User', 'user_friend', 'user_id', 'friend_id')->withTimestamps();
    }

    public function friendRequestsReceived()
    {
        return $this->belongsToMany('App\User', 'friend_requests', 'receiver_id', 'sender_id')->withTimestamps();
    }

    public function friendRequestsSent()
    {
        return $this->belongsToMany('App\User', 'friend_requests', 'sender_id', 'receiver_id')->withTimestamps();
    }

    public function recommendedMovies()
    {
        return $this->belongsToMany('App\User', 'recommends', 'recommender_id', 'recomendee_id')->withTimestamps();
    }
}
