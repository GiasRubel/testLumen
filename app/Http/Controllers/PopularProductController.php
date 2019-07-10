<?php

namespace App\Http\Controllers;

use App\Filters\ProductFilter;
use App\Product;

class PopularProductController extends ApiController
{
    public function __construct(ProductFilter $filters)
    {
        parent::__construct($filters);
    }

    public function popularProducts()
    {
        $products = Product::whereStatus(3)
            ->with(['service'=>function($query){
                $query->select('id','title');
            }])
            ->withCount('views')
            ->orderBy('views_count','desc')
            ->filter($this->filter)
            ->get();
        return $this->showAll($products);
    }
}
