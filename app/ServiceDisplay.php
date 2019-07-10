<?php

namespace App;

use App\Filters\QueryFilter;
use App\Traits\FlashMessage;
use App\Traits\SetTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceDisplay extends Model
{
    protected $visible = ['id','title','pages', 'position','total_ad','price'];
    protected $fillable = ['title', 'service_id', 'type_id', 'category_id', 'status','order', 'pages', 'position','total_ad','price'];

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class,'display_package','display_id','package_id')->withPivot(['number_of_ad']);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class,'product_custom_display','display_id','product_id');
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
