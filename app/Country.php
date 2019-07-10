<?php

namespace App;

use App\Traits\FlashMessage;
use App\Traits\SetTimestamps;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    protected $hidden = ['currency_id','status','created_at','updated_at'];
    protected $fillable = ['currency_id','status', 'title', 'sort_title', 'lat_lng_area'];


    public function states()
    {
        return $this->hasMany(State::class,'country_id');
    }

    public function counties()
    {
        return $this->hasManyThrough(County::class, State::class, 'country_id', 'state_id', 'id', 'id');
    }

    public static function creating($callback)
    {
        static::registerModelEvent('',$callback);
    }

    public static function updating($callback)
    {
        static::registerModelEvent('',$callback);
    }
}
