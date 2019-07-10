<?php

namespace App;

use App\Traits\SetTimestamps;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    protected $fillable = ['user_id', 'first_name', 'last_name', 'display_name', 'image', 'date_of_birth', 'gender', 'nid', 'sid', 'contact', 'present_address', 'permanent_address', 'summary', 'description', 'favourite_music', 'tv_shows', 'favourite_places_to_shop', 'happy_place', 'for_fun'];
    protected $hidden = ['present_address', 'date_of_birth', 'permanent_address', 'summary', 'summary','favorite_quote', 'deleted_at', 'description', 'htmlized_summary', 'htmlized_description', 'favourite_music', 'tv_shows', 'favourite_places_to_shop', 'happy_place', 'for_fun', 'nid', 'sid'];

    public function __construct(array $attributes = [])
    {
        parent::__construct( $attributes );
    }

    public function user()
    {
        return $this->belongsTo( User::class );
    }

    public function location()
    {
        return $this->belongsTo( Location::class );
    }

    /*    public function setDateOfBirthAttribute()
        {
            date_default_timezone_set(getCurrentTimeZone());
            $this->attributes['date_of_birth'] = new Carbon(request('date_of_birth'));
            $this->attributes['date_of_birth'] = $this->attributes['date_of_birth']->setTimezone('UTC');
        }

        public function getDateOfBirthAttribute($value)
        {
            //return Carbon::parse($this->attributes['date_of_birth'])->format('Y-m-d');
            return Carbon::createFromTimestamp(strtotime($value))
                ->setTimezone(getCurrentTimeZone())
                ->toDateString();
        }*/


}
