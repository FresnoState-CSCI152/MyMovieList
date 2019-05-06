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

	public function checkVoted(User $user, $id, $type)
	{
		return $this->votes()->where('user_id', $user->id)->exists();
    }

    public function findVote(User $user, $id, $type)
    {
    	return Vote::where('user_id', '=', $user->id)->where('votable_id', '=', $id)->where('votable_type', $type)->first();
    }

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