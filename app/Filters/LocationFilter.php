<?php

namespace App\Filters;

use Illuminate\Support\Facades\DB;

class LocationFilter extends ServiceFilter
{
    public function lat()
    {
        return $this->request->lat;
    }

    public function radius()
    {
        return $this->request->has( 'radius' ) ? $this->request->radius : 10;
    }

    public function unit()
    {
        return $this->request->has( 'unit' ) ? $this->request->unit : 'km';
    }

    public function lng()
    {
        $lat = (float)$this->lat();
        $radius = $this->radius() ?? 100;
        $unit = $this->unit() ?? "km";
        $unit = ($unit === "km") ? 6378.10 : 3963.17;
        $lng = (float)$this->request->lng;
        $radius = (double)$radius;
        return $this->builder->having( 'distance', '<=', $radius )
            ->select( DB::raw( "*,
                            ($unit * ACOS(COS(RADIANS($lat))
                                * COS(RADIANS(lat))
                                * COS(RADIANS($lng) - RADIANS(lng))
                                + SIN(RADIANS($lat))
                                * SIN(RADIANS(lat)))) AS distance" )
            )->orderBy( 'distance', 'asc' );
    }

    public function country()
    {
        return $this->builder->whereHas( 'county.state.country', function ($country) {
            $country->whereTitle( str_replace( '-', ' ', $this->request->country ) );
        } );
    }

    public function state()
    {
        return $this->builder->whereHas( 'county.state', function ($state) {
            $state->whereTitle( str_replace( '-', ' ', $this->request->state ) );
        } );
    }

    public function  available($relation)
    {
        return $this->builder->whereHas($relation)->withcount($relation);
    }
}