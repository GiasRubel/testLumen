<?php

namespace App;


use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Location extends Model
{

    protected $appends = ['location_city','country','state', 'county_title'];
    protected $hidden = ['status','city_id','created_at','updated_at'];
    protected $fillable = ['status', 'place_name', 'city_id', 'lat', 'lng','postal_code', 'floor_num', 'flat_no', 'apartment_name', 'road_no', 'road_name', 'residential_area', 'area'];

    public function user()
    {
        return $this->hasOne(Profile::class,'location_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class,'location_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->hasManyThrough(Location::class,City::class,'state_id','city_id','id','id');
    }

    public function getStateAttribute($value)
    {
       return $this->getState()->title;
    }

    public function getLocationCityAttribute($value)
    {
       return $this->getCity()->title;
    }

    public function getCountryAttribute($value)
    {
       return $this->getState()->country()->first()->title;
    }

    public function getCountyTitleAttribute()
    {
       return $this->getCity()->county()->first()->title;
    }

    public function getCity()
    {
        return $this->city()->first();
    }

    public function getState()
    {
        return $this->getCity()->state()->first();
    }

    public static function getByDistance($lat, $lng, $unit='km',$radius=100)
    {
        $unit = ($unit === "km") ? 6378.10 : 3963.17;
        $lat = (float) $lat;
        $lng = (float) $lng;
        $radius = (double) $radius;
        $locations = Location::having('lat','<=',$radius)
            ->select(DB::raw("*,
                            ($unit * ACOS(COS(RADIANS($lat))
                                * COS(RADIANS(lat))
                                * COS(RADIANS($lng) - RADIANS(lng))
                                + SIN(RADIANS($lat))
                                * SIN(RADIANS(lat)))) AS distance")
            )->orderBy('distance','asc');

        return $locations;
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
