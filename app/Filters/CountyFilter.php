<?php


namespace App\Filters;


class CountyFilter extends LocationFilter
{
    public function country()
    {
        return $this->builder->whereHas('state.country',function ($country) {
           $country->whereTitle(str_replace( '-', ' ', $this->request->country ));
        });
    }

    public function available($relation=true)
    {
        return $this->builder->withCount('locations');
    }

    public function state()
    {
        return $this->builder->whereHas( 'state', function ($state) {
            $state->whereTitle( str_replace( '-', ' ', $this->request->state ) );
        } );
    }
}