<?php

namespace App;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Model;

class Accessor extends Model
{
    protected $hidden = ['status','summary','created_at','updated_at','deleted_at'];
    protected $fillable = ['title','service_id','category_id','type_id','code','status','summary'];

    public function serviceRules()
    {
       return $this->morphMany(ServiceRule::class,'rulesable');
    }

    /*public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function type()
    {
        return $this->belongsTo(ServiceType::class);
    }*/
    public function services()
    {
        return $this->belongsToMany( Service::class, 'accessor_service', 'accessor_id', 'service_id' )
            ->withPivot( ['type_id', 'category_id'] );
    }

    public function types()
    {
        return $this->belongsToMany( ServiceType::class, 'accessor_service', 'accessor_id', 'type_id' )
            ->withPivot( ['service_id', 'category_id'] );
    }

    public function categories()
    {
        return $this->belongsToMany( ServiceCategory::class, 'accessor_service', 'accessor_id', 'category_id' )
            ->withPivot( ['type_id', 'service_id'] );
    }

    public function users() //font-end relation
    {
        return $this->belongsToMany(User::class,'accessor_user','accessor_id','user_id');
    }

    public function packages()
    {
        return $this->hasmany(Package::class);
    }

    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }
}
