<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Vote extends Model
{
	protected $fillable = ['vote'];
	use SoftDeletes;
    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
            ->where('user_id', '=', $this->getAttribute('user_id'))
            ->where('votable_id', '=', $this->getAttribute('votable_id'))
            ->where('votable_type', '=', $this->getAttribute('votable_type'));
        return $query;
    }

	protected $dates=[
		'created_at',
		'deleted_at',
		'updated_at'
	];
    public function votable()
    {
    	return $this->morphTo();
    }
}
