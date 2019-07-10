<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $hidden = ['messageable_type','updated_at'];
    protected $fillable = ['messageable_id','messageable_type','sender_id','receiver_id','parent_id','message'];

    public function messageable()
    {
        return $this->morphTo();
    }

    public function messageReplies()
    {
        return $this->hasMany(Message::class,'parent_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','sender_id', 'id');
    }


    public function product()
    {
        return $this->belongsTo(Product::class,'messageable_id');
    }
}
