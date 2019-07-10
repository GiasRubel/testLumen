<?php


namespace App\Filters;


class StateFilter extends LocationFilter
{
    public function country()
    {
        return $this->builder->whereHas('country',function ($country) {
           $country->whereTitle(str_replace( '-', ' ', $this->request->country ));
        });
    }

    public function available($relation=true)
    {
        return $this->builder->withCount('cities');
    }
}