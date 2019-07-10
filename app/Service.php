<?php

namespace App;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $hidden = ['code','status','order','summary','created_at','updated_at','deleted_at','created_by', 'updated_by', 'deleted_by', 'htmlized_description', 'description', 'meta_tag','meta_description'];
    protected $fillable = ['title', 'code', 'status','order','summary','image','bootstrap_class','created_by', 'updated_by', 'deleted_by'];

    public function serviceRules()
    {
       return $this->morphMany(ServiceRule::class,'rulesable');
    }

    public function relatedService()
    {
        return $this->belongsToMany(Service::class,'related_service','service_id','related_id')->withPivot('order');
    }

    public function products()
    {
        return $this->hasMany(Product::class,'service_id');
    }

    public function categories()
    {
        return $this->hasMany( ServiceCategory::class,'service_id');
    }

    public function types()
    {
        return $this->hasMany( ServiceType::class,'service_id');
    }

    public function options()
    {
        return $this->hasMany( FieldOption::class );
    }

    public function accessors()
    {
        return $this->belongsToMany(Accessor::class,'accessor_service');
    }

    public function serviceFields()
    {
        return $this->belongsToMany( Field::class, 'field_service', 'service_id', 'field_id' )->withPivot( ['group_id','category_id', 'type_id', 'is_required', 'placeholder'] )->withTimestamps();
    }

    public function fieldGroups()
    {
        return $this->belongsToMany( FieldGroup::class, 'field_service', 'service_id', 'group_id' )->withPivot( ['group_id','category_id', 'type_id', 'is_required', 'placeholder'] )->withTimestamps();
    }

    public function packages()
    {
        return $this->hasmany( Package::class,'service_id');
    }

    public function serviceDisplay()
    {
        return $this->hasMany(ServiceDisplay::class,'service_id');
    }

    public function businessTypes()
    {
        return $this->belongsToMany(BusinessType::class,'service_related_directory_type','service_id','directory_type_id');
    }

    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }

}
