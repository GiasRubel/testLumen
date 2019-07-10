<?php

namespace App\Traits;

trait Displayable
{
//    protected static function boot()
//    {
//        parent::boot();
//
//        static::saving(function($model)
//        {
//            if (auth()->check()) {
//                $model->status = auth()->user()->isAdmin();
//            }
//        });
//    }

    public function scopeAuthor($query)
    {
        if (!auth()->user()->isAdmin() AND !$this->LocalAdmin()) {
            return $query->where('created_by', auth()->user()->username);
        }
        return $query;
    }

    public function isAuthor()
    {
        if (auth()->user()->isAdmin() OR  $this->LocalAdmin())
            return true;
        else
            return $this->created_by == auth()->user()->username ? true : false;
    }

    public function scopeStatus($query, $status = 3)
    {
        $query->where('status', $status);
    }

    public function LocalAdmin()
    {
        if(strpos(strtolower(auth()->user()->role->name), 'admin')!=false){
            return true;
        }
    }
}
