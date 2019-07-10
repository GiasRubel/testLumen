<?php

namespace App;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = ['title', 'input_name', 'type', 'summary', 'css_class_name', 'icon', 'placeholder', 'is_required', 'status', 'order'];
    protected $hidden = ['pivot','created_at','updated_at'];
    protected $inputTypes = [
        'radio' => 'Radio',
        'select' => 'Select',
        'checkbox' => 'Checkbox',
        'text' => 'Text',
        'number' => 'Number',
        'color' => 'Color',
        'date' => 'Date',
        'datetime-local' => 'Datetime Local',
        'email' => 'Email',
        'month' => 'Month',
        'range' => 'Range',
        'search' => 'Search',
        'tel' => 'Telephone',
        'time' => 'Time',
        'url' => 'Url',
        'week' => 'Week'
    ];

    public function serviceRules()
    {
        return $this->morphMany(ServiceRule::class, 'rulesable');
    }

    public function types()
    {
        return $this->belongsToMany(ServiceType::class, 'field_service', 'field_id', 'type_id')->withPivot('service_id','category_id','type_id','group_id','field_id','is_required', 'placeholder', 'status', 'order')->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany(ServiceCategory::class, 'field_service', 'field_id', 'category_id')->withPivot('service_id','category_id','type_id','group_id','field_id','is_required', 'placeholder', 'status', 'order')->withTimestamps();
    }

    public function fieldGroups()
    {
        return $this->belongsToMany(FieldGroup::class, 'field_service', 'field_id', 'group_id')->withPivot('service_id','category_id','type_id','is_required', 'placeholder', 'status', 'order')->withTimestamps();
    }

    public function getInputTypes()
    {
        return $this->inputTypes;
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function options()
    {
        return $this->hasMany(FieldOption::class, 'field_id');
    }

    public function values()
    {
        return $this->hasMany(FieldValue::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'field_service', 'field_id', 'service_id', '')->withPivot('service_id','category_id','type_id','group_id','field_id','is_required', 'placeholder', 'status', 'order')->withTimestamps();
    }

    public static function creating($callback)
    {
        static::registerModelEvent('', $callback);
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
