<?php

namespace App;

use App\Filters\QueryFilter;
use App\Traits\SetTimestamps;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id','ip','viewable_id','viewable_type','read_at'];

    public function viewable()
    {
        return $this->morphTo();
    }

/*    public function setReadAtAttribute()
    {
        date_default_timezone_set(getCurrentTimeZone());
        $this->attributes['read_at'] = new Carbon();
        $this->attributes['read_at'] = $this->attributes['read_at']->setTimezone('UTC');
    }

    public function getReadAtAttribute($value)
    {
        return Carbon::createFromTimestamp($value)
            ->setTimezone(getCurrentTimeZone())
            ->toDateTimeString();
    }*/

    public static function boot()
    {
        parent::boot();
        static::created(function ($count){
           $counter = Counter::where('countable_id',$count->viewable_id)->where('countable_type',$count->viewable_type)->get();
           if ($counter->count() == 1){
               $count = Counter::where('countable_id',$count->viewable_id)->where('countable_type',$count->viewable_type)->increment('view');
               if ($count){
                   return 'success';
               }else{
                   return 'fail';
               }
           }else{
               $view['countable_id'] = $count->viewable_id;
               $view['countable_type'] = $count->viewable_type;
               $view['view'] = 1;
               Counter::create($view);
           }
        });

    }

    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }
}
