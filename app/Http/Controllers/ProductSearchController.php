<?php

namespace App\Http\Controllers;

use App\Filters\ProductFilter;
use App\Product;
use Illuminate\Http\Request;

class ProductSearchController extends ApiController
{
    public function __construct(ProductFilter $filters)
    {
        parent::__construct( $filters );
    }

    public function result(Request $request)
    {
        $products = $this->productCollection();
        $productsall = $products->paginate( 10 );

        $custom = collect( $this->getMaxMinPrice( $request ) );
        $product = $custom->merge( $productsall );
        return $this->showAll( $product );
    }


    public function maxMinProducts(Request $request)
    {
        $maxMin = collect( $this->getMaxMinPrice( $request ) );
        return $this->showAll( $maxMin );
    }

    /**
     * @return mixed
     */
    private function productCollection()
    {
        $products = Product::with( ['service' => function ($service) {
            $service->select( 'id', 'title' );
        }, 'type' => function ($type) {
            $type->select( 'id', 'title' );
        }, 'category' => function ($category) {
            $category->select( 'id', 'title' );
        }] )->withCount('views')
            ->filter( $this->filter )->whereStatus( 3 );
        return $products;
    }

    private function getMaxMinPrice(Request $request)
    {
        $default_currency = $request->query( 'currency' ) ?? 'USD';
        $min_bd_price = $this->productCollection()->where( 'currency', 'BDT' )->min( 'price' );
        $min_usd_price = $this->productCollection()->where( 'currency', 'USD' )->min( 'price' );
        $max_bd_price = $this->productCollection()->where( 'currency', 'BDT' )->max( 'price' );
        $max_usd_price = $this->productCollection()->where( 'currency', 'USD' )->max( 'price' );

        if ($default_currency == 'USD') {
            $min = ($data = $min_bd_price / 80) > $min_usd_price ? $min_usd_price : $data;
            $max = ($data = $max_bd_price / 80) > $max_usd_price ? $data : $max_usd_price;
        } else {
            $min = ($data = $min_usd_price * 80) > $min_bd_price ? $min_bd_price : $data;
            $max = ($data = $max_usd_price * 80) > $max_bd_price ? $data : $max_bd_price;
        }
        return ['min' => $min, 'max' => $max];
    }
}
