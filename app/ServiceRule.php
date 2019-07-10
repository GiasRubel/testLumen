<?php

namespace App;

use App\Traits\FlashMessage;
use App\Traits\SetTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceRule extends Model
{
    protected $fillable = ['title','summary','status','order','description','htmlized_description','rulesable_id','rulesable_type','condition'];

    public function rulesable()
    {
        return $this->morphTo();
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
