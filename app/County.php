<?php

namespace App;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    protected $fillable = ['state_id','status', 'title', 'lat_lng_area'];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function country()
    {
        return $this->state->country();
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function locations()
    {
        return $this->hasManyThrough(Location::class,City::class,'county_id','city_id','id','id');
    }

    public static function creating($callback)
    {
        static::registerModelEvent('',$callback);
    }

    public static function updating($callback)
    {
        static::registerModelEvent('',$callback);
    }

    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }
}
