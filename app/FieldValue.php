<?php

namespace App;

use App\Traits\SetTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\InteractsWithTime;

class FieldValue extends Model
{
    protected $table = 'field_values';
    protected $casts = ['options'=>'array'];
    protected $hidden = ['pivot','created_at','updated_at'];
    protected $fillable = ['field_id', 'field_option_id', 'valuable_id', 'valuable_type', 'value', 'option'];

    public function valuable()
    {
        return $this->morphTo();
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function option()
    {
        return $this->belongsTo(FieldOption::class);
    }

    public function options()
    {
        return $this->belongsToMany(FieldOption::class,'value_option','value_id','option_id');
    }

}
