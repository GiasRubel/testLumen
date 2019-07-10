<?php

namespace App;

use App\Filters\QueryFilter;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password','role_id','provider_id','provide_by','status','remember_token','verification_token','created_at','updated_at','deleted_at'
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function oauthClients()
    {
        return $this->belongsToMany(OauthAccessToken::class,'user_id');
    }


    public function comments()
    {
        return $this->belongsTo('App\Comment', 'username', 'created_by');
    }

    public function comment()
    {
        return $this->hasMany('App\Comment');
    }

    public function likes()
    {
        return $this->hasMany('App\Like');
    }

    public function products()
    {
        return $this->hasMany(Product::class,'user_id');
    }

    public function DirectoryUser()
    {
        return $this->hasMany(DirectoryUser::class,'user_id');
    }

    public function likePoints()
    {
        return $this->likes->sum('point');
    }

    public function commentPoints()
    {
        return $this->comment()->sum('point');
    }

    public function accessors() //font-end relation
    {
        return $this->belongsToMany(Accessor::class,'accessor_user','user_id','accessor_id');
    }

    public function packages() //font-end relation
    {
        return $this->belongsToMany(Package::class,'user_package','user_id','package_id')->withPivot(['start_at','end_at','status','renew_at']);
    }

    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }

    public function joinContests()
    {
        return $this->hasMany('App\ContestRegistration');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

}
