<?php

namespace App;

use App\Traits\FlashMessage;
use App\Traits\SetTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessCompanie extends Model
{

    protected $hidden = ['pivot','created_at','updated_at','deleted_at'];
    protected $fillable = ['business_type_id','location_id','title','url','member_range'];

    public function businessType()
    {
        return $this->belongsTo(BusinessType::class);
    }

    public function directoryUsers()
    {
        return $this->belongsToMany(DirectoryUser::class,'business_directory','company_id')->withPivot('company_id','directory_user_id','location_id')->withTimestamps();
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
