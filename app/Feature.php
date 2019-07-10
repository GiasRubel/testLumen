<?php

namespace App;

use App\Traits\FlashMessage;
use App\Traits\SetTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feature extends Model
{
    protected $hidden = ['status','created_at','updated_at','deleted_at'];
    protected $fillable = ['title', 'status'];

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'feature_package', 'feature_id', 'package_id')->withPivot('value');
    }

    public static function creating($callback)
    {
        static::registerModelEvent('', $callback);
    }

    public static function updating($callback)
    {
        static::registerModelEvent('', $callback);
    }

}
