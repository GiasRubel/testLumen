<?php

namespace App;

use App\Traits\SetTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Like extends Model
{
    use SetTimestamps;

    protected $fillable = ['like', 'likeable_type', 'likeable_id', 'user_id','ip', 'point'];

    public function likeable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        /*return $this->belongsTo('App\User', 'created_by', 'username');*/
        return $this->belongsTo(User::class);
    }

    public static function boot()
    {
        parent::boot();
        static::created(function ($count) {
            $counter = Counter::where('countable_id', $count->likeable_id)->where('countable_type', $count->likeable_type)->get();
            if ($counter->count() == 1) {
                $count=DB::table('counters')->where('countable_id', $count->likeable_id)->where('countable_type', $count->likeable_type)->increment('like');
                if ($count) {
                    return 'Success';
                } else {
                    return 'fail';
                }
            } else {
                $like['countable_id'] = $count->likeable_id;
                $like['countable_type'] = $count->likeable_type;
                $like['like'] = 1;
                $count = Counter::create($like);
                if ($count) {
                    return 'Success';
                } else {
                    return 'fail';
                }
            }
        });
    }
}
