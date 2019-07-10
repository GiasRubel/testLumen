<?php

namespace App;

use App\Filters\QueryFilter;
use App\Traits\FlashMessage;
use App\Traits\SetTimestamps;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $hidden = ['status','created_at','updated_at'];
    protected $fillable = ['country_id','status', 'title', 'lat_lng_area'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function counties()
    {
        return $this->hasMany(County::class);
    }

    public function cities()
    {
        return $this->hasManyThrough(City::class, County::class, 'state_id', 'county_id', 'id', 'id');
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
