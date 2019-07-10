<?php

namespace App\Http\Controllers;

use App\City;
use App\Filters\LocationFilter;
use App\Location;

class CityController extends ApiController
{
    /**
     * City list
     * @return \Illuminate\Http\JsonResponse
     */
    public function __construct(LocationFilter $filters)
    {
        parent::__construct($filters);
    }

    public function cities()
    {
        $cities = City::whereStatus(3)
            ->filter($this->filter)
            ->get();
        return $this->showAll($cities);
    }

    /**
     * Area list depend on city
     * @param $city_title
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function area($city_title)
    {
        $title = str_replace('-',' ', $city_title);
        $area_title = Location::select('area')
            ->orderBy('area', 'asc')
            ->groupBy('area')
            ->whereHas('city', function ($city) use($title){
                $city->whereTitle($title);
            })->get();

        return $this->showAll($area_title->makeHidden(['state','location_city','country']));
    }

}
