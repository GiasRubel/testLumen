<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    protected $table='charts';

    public $timestamps = false;

    protected $fillable = ['chartable_id','chartable_type','is_repeated','start_date','end_date','start_time','end_time','days'];

    public function chartable()
    {
        return $this->morphTo();
    }
}
