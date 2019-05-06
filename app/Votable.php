<?php

namespace App;
use App\Post;
use Illuminate\Database\Eloquent\Model;
use Auth;

trait Votable
{

	public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    // check if current user has voted before
	public function checkVoted(User $user, $id, $type)
	{
		return $this->votes()->where('user_id', $user->id)->exists();
    }

    // search through table for possible matches
    // a match will be when a vote contains correct user_is, correct comment_id and correct type
    public function findVote(User $user, $id, $type)
    {
    	return Vote::where('user_id', '=', $user->id)->where('votable_id', '=', $id)->where('votable_type', $type)->first();
    }

    // search table for all votes for current post/comment
    // use special keys votable_id and votable_type to find exact match
    public function countVotes($id, $type)
    {
    	$totalVotes = 0;

        $currentVotes = Vote::where('votable_id', '=', $id)->where('votable_type', $type)->get();
    	foreach($currentVotes as $v)
    	{
    		$totalVotes += $v->vote;
    	}

    	return $totalVotes;
    }

    public function submitVote($voteType, $user, $id, $type)
    {
        // if user has not voted before, then create a new vote with the users id
    	if(!$this->checkVoted($user, $id, $type))
    	{
    		$newVote = new Vote();
    		$newVote->user_id = $user->id;
    		$newVote->vote = $voteType;
    		$this->votes()->save($newVote);
    		return true;
    	}
    	else
    	{
            // user has voted before, so go through table and update previous vote
            // if user uses same vote twice, then the value is made neutral
    		$oldVote = $this->findVote($user, $id, $type);
    		if ($voteType == $oldVote->vote)
    		{
    			$oldVote->vote = 0;
    		}
    		else
    		{
    			$oldVote->vote = $voteType;
    		}

    		$oldVote->save();
      		return true;
    	}
    	return false;
    }

}