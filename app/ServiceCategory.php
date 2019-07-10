<?php

namespace App;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $hidden = ['pivot','status','order','code','summary','description','htmlized_description','meta_tag','meta_description', 'created_by', 'updated_by', 'deleted_by'];
    protected $fillable = ['title','input_alias', 'status','order','code', 'service_id', 'summary','description','htmlized_description','meta_tag','meta_description', 'created_by', 'updated_by', 'deleted_by'];

    public function serviceRules()
    {
        return $this->morphMany(ServiceRule::class,'rulesable');
    }

    public function types()
    {
        return $this->belongsToMany(ServiceType::class,'category_type','category_id','type_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class,'category_id');
    }

    public function fields()
    {
        return $this->belongsToMany(Field::class,'field_service','category_id','field_id')->withPivot('is_required','placeholder','status','order')->withTimestamps();
    }


    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function options()
    {
        return $this->hasMany(FieldOption::class,'category_id');
    }

    public function accessors()
    {
        return $this->belongsToMany(Accessor::class,'accessor_service');
    }

    public function packages()
    {
        return $this->hasmany(Package::class,'category_id');
    }

    public function serviceDisplays()
    {
        return $this->hasMany(ServiceDisplay::class,'category_id');
    }

    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }
}
