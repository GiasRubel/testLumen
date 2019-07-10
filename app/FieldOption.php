<?php

namespace App;

use App\Traits\FlashMessage;
use App\Traits\SetTimestamps;
use Illuminate\Database\Eloquent\Model;

class FieldOption extends Model
{

    protected $hidden = ['pivot','created_at','updated_at'];
    protected $fillable = ['field_id', 'service_id', 'category_id', 'type_id', 'option'];

    //protected $appends = ['field_name','service_name','type_name','category_name'];

    public function serviceRules()
    {
        $this->morphMany(ServiceRule::class,'rulesable');
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function type()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function values()
    {
        return $this->hasMany(FieldValue::class);
    }

    public function getFieldNameAttribute($value)
    {
        return $this->field()->firstOrFail()->title;
    }
    public function getServiceNameAttribute($value)
    {
        return $this->service()->firstOrFail()->title;
    }
    public function getTypeNameAttribute($value)
    {
        return $this->type()->firstOrFail()->title;
    }
    public function getcategoryNameAttribute($value)
    {
        return $this->category()->firstOrFail()->title;
    }

    public static function updating($callback)
    {
        static::registerModelEvent('',$callback);
    }

    public static function creating($callback)
    {
        static::registerModelEvent('',$callback);
    }
}
