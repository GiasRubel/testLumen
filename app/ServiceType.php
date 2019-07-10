<?php

namespace App;

use App\Filters\QueryFilter;
use App\Traits\SetTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceType extends Model
{
    protected $hidden = ['pivot','status','order','code','summary','description','htmlized_description','meta_tag','meta_description', 'created_by', 'updated_by', 'deleted_by','created_at','updated_at','deleted_at'];
    protected $fillable = ['title', 'status','order', 'service_id', 'code', 'summary', 'description','htmlized_description', 'created_by', 'updated_by', 'deleted_by'];


    public function relatedTypes()
    {
        return $this->belongsToMany(ServiceType::class,'related_type','type_id','related_id');
    }

    public function serviceRules()
    {
        return $this->morphMany(ServiceRule::class,'rulesable');
    }

    public function categories()
    {
        return $this->belongsToMany(ServiceCategory::class,'category_type','type_id','category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class,'type_id');
    }

    public function fields()
    {
        return $this->belongsToMany(Field::class,'field_service','type_id','field_id')->withPivot('is_required','placeholder','status','order')->withTimestamps();
    }

    public function service()
    {
        return $this->belongsTo( Service::class );
    }

    public function options()
    {
        return $this->hasMany( FieldOption::class ,'type_id');
    }

    public function accessors()
    {
        return $this->belongsToMany(Accessor::class,'accessor_service');
    }

    public function packages()
    {
        return $this->hasmany( Package::class,'type_id');
    }

    public function serviceDisplays()
    {
        return $this->hasMany(ServiceDisplay::class,'type_id');
    }

    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }
}
