<?php


namespace App\Http\Controllers;


use App\Filters\ProductFilter;
use App\Location;
use App\Product;
use App\Service;
use App\State;

class ServiceProductController extends ApiController
{

    public function __construct(ProductFilter $filters)
    {
        parent::__construct( $filters );
    }

    /**
     * Product list depend on Service
     * @param $service_title
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function index($service_title)
    {
        $service = str_replace( '-', '', $service_title );
        $products = Product::whereHas( 'service', function ($query) use ($service) {
            $query->whereTitle( $service );
        } )
            ->withCount( 'views' )
            ->with( ['type' => function ($type) {
                $type->select( 'id', 'title' );
            }, 'category' => function ($category) {
                $category->select( 'id', 'title' );
            }, 'location'] )
            ->filter( $this->filter )
            ->whereStatus( 3 )
            ->get();

        return $this->showAll( $products );
    }

    /**
     * Categories wise product count depend on service and state
     * @param $state_title
     * @return \Illuminate\Http\JsonResponse
     */
    public function totalProductDependOnState($state_title)
    {
        $location_id = Location::whereHas( 'city.county.state', function ($query) use ($state_title) {
            $query->whereTitle( str_replace( '-', ' ', $state_title ) );
        } )->pluck( 'id' );

        $services = Service::whereStatus( 3 )->with( ['categories' => function ($query) use ($location_id) {
            $query->whereStatus( 3 )->withCount( ['products' => function ($product) use ($location_id) {
                return $product->whereStatus( 3 )->whereIn( 'location_id', $location_id );
            }] );
        }] )->get();


        return $this->showAll( $services );
    }
}