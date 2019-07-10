<?php

namespace App;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $hidden = ['status','order','summary','created_at','updated_at','deleted_at'];
    protected $fillable = ['title', 'type_id', 'category_id', 'accessor_id','status','order', 'start', 'end', 'max_allow','price','summary'];

    public function serviceRules()
    {
        return $this->morphMany(ServiceRule::class,'rulesable');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class,'package_service','package_id','service_id');
    }

    public function displays()
    {
        return $this->belongsToMany(ServiceDisplay::class,'display_package','package_id','display_id')->withPivot(['number_of_ad']);
    }

    public function types()
    {
        return $this->belongsToMany(ServiceType::class,'package_service','package_id','type_id');
    }

    public function categories()
    {
        return $this->belongsToMany(ServiceCategory::class,'package_service','package_id','category_id');
    }

    public function accessors()
    {
        return $this->belongsToMany(Accessor::class,'accessor_package','package_id','accessor_id');
    }

    public function users()  //font-end relation
    {
        return $this->belongsToMany(User::class,'user_package','package_id','user_id');
    }

    public function products()    //font-end relation
    {
        return $this->hasMany(Product::class,'package_id');
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class,'feature_package','package_id','feature_id')->withPivot('value');
    }

    public static function updating($callback)
    {
        static::registerModelEvent('', $callback);
    }

    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }
}
