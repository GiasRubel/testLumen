<?php

namespace App;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Model;

class FieldGroup extends Model
{
    protected $hidden = ['pivot','status','order','created_at','updated_at','deleted_at'];
    protected $fillable = ['title','status','order'];

    public function fields()
    {
        return $this->belongsToMany(Field::class,'field_service','group_id','field_id')->withPivot('type_id','category_id','service_id','is_required','placeholder','status','order')->withTimestamps();
    }
    public static function creating($callback)
    {
        static::registerModelEvent( '', $callback );
    }

    public static function updating($callback)
    {
        static::registerModelEvent( '', $callback );
    }

    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }
}
