<?php

namespace App;

use App\Traits\SetTimestamps;
use Illuminate\Database\Eloquent\Model;

class AddDisplay extends Model
{
    protected $fillable = ['title','module_id','module_type','pages'];
}
