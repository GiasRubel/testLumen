<?php

namespace App\Http\Controllers;

use App\Filters\ProductFilter;
use App\Product;
use Illuminate\Support\Facades\Auth;

class HomeProductController extends ApiController
{

    public function __construct(ProductFilter $filters)
    {
        parent::__construct($filters);
    }

    /**
     * @return All Products list
     */
    public function index()
    {
        $products = Product::whereStatus(3)
            ->with(['service'=>function($query){
                $query->select('id','title');
            }])
            ->filter($this->filter)
            ->get();
        return $this->showAll($products);
    }

}
