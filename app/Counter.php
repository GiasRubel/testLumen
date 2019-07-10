<?php

namespace App;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    protected $fillable = ['countable_id', 'countable_type', 'like', 'comment', 'view'];

    public $timestamps = false;

    public function countable()
    {
        return $this->morphTo();
    }

    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }

}
