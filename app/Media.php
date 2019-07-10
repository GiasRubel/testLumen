<?php

namespace App;

use App\Traits\SetTimestamps;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'medias';
    protected $visible = ['id','title','file','primary'];
    protected $fillable = ['mediable_id', 'mediable_type', 'file', 'slug', 'title', 'original_name', 'file_name', 'primary', 'permission', 'status', 'type', 'size', 'height', 'width', 'x_distance', 'y_distance', 'summary', 'html_summary', 'description', 'html_description', 'meta_tag', 'meta_description'];

    public function mediable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
