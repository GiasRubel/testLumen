<?php

namespace App;

use App\Traits\FlashMessage;
use App\Traits\SetTimestamps;
use Illuminate\Database\Eloquent\Model;

class ServiceContact extends Model
{
    protected $hidden = ['pivot','created_at','updated_at'];
    protected $fillable = ['name','email','address','contact'];

    public function products()
    {
        return $this->belongsToMany(Product::class,'contact_product','contact_id','product_id');
    }

    public static function creating($callback)
    {
        static::registerModelEvent( '', $callback );
    }

    public static function updating($callback)
    {
        static::registerModelEvent( '', $callback );
    }
}
