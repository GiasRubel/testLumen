<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DirectoryUser extends Model
{
    protected $hidden = ['pivot','status','user_id','created_at','updated_at'];
    protected $fillable = ['user_id','name','contact','email'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function businessTypes()
    {
        return $this->belongsToMany(BusinessType::class,'directory_user_type','user_id','business_type_id');
    }

    public function businessCompanies()
    {
        return $this->belongsToMany(BusinessCompanie::class,'business_directory','directory_user_id','company_id')->withTimestamps();
    }

    public function businessUsers()
    {
        return $this->hasMany(BusinessDirectory::class,'directory_user_id');
    }

    public function businessClients()
    {
        return $this->hasManyThrough(BusinessDirectoryClient::class,BusinessDirectory::class, 'directory_user_id','business_directory_id');
    }
}
