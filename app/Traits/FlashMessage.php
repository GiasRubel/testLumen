<?php

namespace App\Traits;

use App\Filters\QueryFilter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

trait FlashMessage
{

    protected static function boot()
    {
        parent::boot();
        //date_default_timezone_set(getCurrentTimeZone());
        static::creating(function ($model) {
            $model->created_by = auth()->check() ? auth()->user()->username : 'admin';
        });
        static::created(function ($model) {
            Session::flash('alert', 'create_success');
        });
        static::updating(function ($model) {
            $model->updated_by = auth()->user()->username??$model->updated_by;
        });
        static::updated(function ($model) {
            Session::flash('alert', 'update_success');
        });

        static::deleted(function ($model) {
            Session::flash('alert', 'delete_success');
        });
        static::saving(function ($model) {
            if (Input::get('htmlized_summary') AND !is_null(Input::get('htmlized_summary')))
                $model->summary = strip_tags(Input::get('htmlized_summary'));
            if (Input::get('htmlized_description'))
                $model->description = strip_tags(Input::get('htmlized_description'));
        });
    }

    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }
}