<?php

namespace App;

use App\Traits\FlashMessage;
use App\Traits\SetTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessType extends Model
{

    protected $hidden = ['parent_id','status','created_at','updated_at','deleted_at'];
    protected $fillable = ['parent_id','title','status'];

    public function subTypes()
    {
        return $this->hasMany(BusinessType::class,'parent_id');
    }

    public function businessCompanies()
    {
        return $this->hasMany('App\BusinessCompanie','business_type_id');
    }

    public function businessType()
    {
        return BusinessType::where(['status' => 3, 'parent_id' => null]);
    }

    public function directoryUsers()
    {
        return $this->belongsToMany(DirectoryUser::class,'directory_user_type','business_type_id','user_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class,'service_related_directory_type','directory_type_id','service_id');
    }
}
