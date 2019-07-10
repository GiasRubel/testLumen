<?php

namespace App;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{

    protected $hidden = ['state_id','status','created_at','updated_at'];
    protected $fillable = ['state_id','status', 'title', 'lat_lng_area'];

    public function country()
    {
        return $this->county->state->country();
    }

    public function state()
    {
        return $this->county->state();
    }

    public function locations()
    {
        return $this->hasMany(Location::class, 'city_id');
    }

    public function county()
    {
        return $this->belongsTo(County::class);
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class,Location::class,'city_id','location_id');
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
