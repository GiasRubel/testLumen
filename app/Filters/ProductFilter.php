<?php


namespace App\Filters;


use App\City;
use App\Country;
use App\Location;
use App\State;

class ProductFilter extends LocationFilter
{
    public $locationsId = [];

    public function price($range)
    {
        $amount = explode( '-', $range );
        $this->builder->whereBetween( 'price', [$amount[0], $amount[1]] );

    }

    public function q()
    {
        return $this->builder->where( function ($query) {
            $search = $this->request->q;
            $query->where( "title", "LIKE", "%$search%" )
                ->orWhere( "price", "LIKE", "%$search%" )
                ->orWhere( "address", "LIKE", "%$search%" );
        } );
    }

    public function country()
    {
        $locationIds = Location::whereHas( 'city', function ($query) {
            $query->whereHas( 'county.state', function ($query) {
                $query->whereCountryId( Country::whereTitle( str_replace( '-', ' ', $this->request->country ) )->first()->id );
            } );
        } )->pluck( 'id' )->toArray();

        return $this->builder->whereIn( 'location_id', $locationIds );
    }

    public function state_title()
    {
        $locationsIds = Location::whereHas( 'city.county', function ($query) {
            $query->whereStateId( State::select( 'id' )->whereTitle( str_replace( '-', ' ', $this->request->state_title ) )->first()->id );
        } )->latest( 'updated_at' )->pluck( 'id' )->toArray();
        return $this->builder->whereIn( 'location_id', $locationsIds );
    }

    public function city_title()
    {
        $cityId = City::select( 'id' )->whereTitle( str_replace( '-', ' ', $this->request->city_title ) )
            ->first()->id;
        $locationsIds = Location::whereCityId( $cityId )
            ->latest( 'updated_at' )
            ->pluck( 'id' )
            ->toArray();
        return $this->builder->whereIn( 'location_id', $locationsIds );
    }

    public function area()
    {
        $locationsIds = Location::whereArea( $this->request->area )
            ->latest( 'updated_at' )
            ->pluck( 'id' )
            ->toArray();
        echo ' Area Print ';
        print_r( $locationsIds );
        return count( $locationsIds ) ? $this->builder->whereIn( 'location_id', $locationsIds ) : true;
    }

    public function city($ids)
    {
        $ids = explode( ',', $ids );
        $locationIds = Location::whereHas( 'city', function ($query) use ($ids) {
            $query->whereIn( 'id', $ids );
        } )->pluck( 'id' )->toArray();

        return $this->builder->whereIn( 'location_id', $locationIds );
    }


    public function location()
    {
        $latlng = explode( ',', $this->request->location );
        $lat = $latlng[0];
        $lng = $latlng[1];
        $radius = $this->radius();
        $unit = $this->unit();
        $unit = ($unit === "km") ? 6378.10 : 3963.17;
        $lat = (float)$lat;
        $lng = (float)$lng;
        $radius = (double)$radius;
        $locationId = Location::having( 'distance', '<=', $radius )
            ->selectRaw( ("id,
                            ($unit * ACOS(COS(RADIANS($lat))
                                * COS(RADIANS(lat))
                                * COS(RADIANS(lng) - RADIANS($lng))
                                + SIN(RADIANS($lat))
                                * SIN(RADIANS(lat)))) AS distance")
            )->orderBy( 'distance', 'asc' )->pluck( 'id' )->toArray();
        $this->builder->whereIn( 'location_id', $locationId );
    }

    /* public function orderBY()      //orderBy now working when query builder with whereIn() used
     {
         dd($this->request->orderBy);
         $data = explode(',',$this->request->orderBy);
         $this->builder->orderByRaw(\DB::raw('FIELD(":fields", ":values")', ['fields'=>$data[0],'values' => $data[1]]));
     }*/


}