<?php

namespace App\Http\Controllers;

use App\City;
use App\Filters\ProductFilter;
use App\Location;
use App\Product;
use App\State;
use Illuminate\Http\Request;

class FeatureProductController extends ApiController
{
    public function __construct(ProductFilter $filters)
    {
        parent::__construct( $filters );
    }

    public function products(Request $request)
    {
        /* \DB::enableQueryLog();
         $limit = $request->query()['take'] ?? '20';
         $cityTitle = $request->query()['city'] ?? 'dhaka';
         $stateTitle = $request->query()['state'] ?? 'dhaka';
         $city = City::whereTitle( str_replace( '-', ' ', $cityTitle ) )->first();
         $state = State::whereTitle( str_replace( '-', ' ', $stateTitle ) )->first();
         if (!is_null( $city ) && $this->checkLimit( $this->productsDependOnCity( $city ), $limit )) {
             $products = $this->productsDependOnCity( $city );
         } elseif (!is_null( $state ) && $this->checkLimit( $this->stateDependOnProducts( $state ), $limit )) {
             $products = $this->stateDependOnProducts( $state );
         } else {
             $products = Product::whereStatus(3)->with( ['service' => function ($service) {
                 $service->select( 'id', 'title' );
             }] )->filter( $this->filter )->get()->makeVisible( ['created_at', 'updated_at'] );
         }
         echo "<pre>";
         print_r(\DB::getQueryLog());
         dd();*/
        $products = Product::whereStatus( 3 )->with( ['service' => function ($service) {
            $service->select( 'id', 'title' );
        }, 'type'] )->withCount( 'views' )->filter( $this->filter );

        return $request->query( 'paginate' ) ? $this->pagination( $products->paginate( $request->query( 'paginate' ) ) ) : $this->showAll( $products->get() );

    }

    /**
     * @param $city
     * @return mixed
     */
    private function productsDependOnCity(City $city)
    {
        $products = Location::whereHas( 'city', function ($query) use ($city) {
            $query->whereId( $city->id );
        } )
            ->whereHas( 'products' )
            ->with( ['products', 'products.service' => function ($service) {
                $service->select( 'services.id', 'services.title' );
            }] )
            ->filter( $this->filter )
            ->get()
            ->pluck( 'products' )
            ->collapse();
        return $products;
    }

    /**
     * @param $state
     * @return mixed
     */
    private function stateDependOnProducts(State $state)
    {
        $products = $state->cities()
            ->whereHas( 'locations.products' )
            ->with( ['products', 'products.service' => function ($service) {
                $service->select( 'services.id', 'title' );
            }] )
            ->filter( $this->filter )
            ->get()
            ->pluck( 'products' )
            ->collapse();
        return $products;
    }

    /**
     * @param $products
     * @param $limit
     * @return bool
     */
    private function checkLimit($products, $limit): bool
    {
        return $products->count() >= $limit;
    }


}
