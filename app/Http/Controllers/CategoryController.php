<?php

namespace App\Http\Controllers;

use App\ServiceCategory;

class CategoryController extends ApiController
{

    /**
     * category list depend on service and type or type is null
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $category = ServiceCategory::whereStatus(3)
            ->filter($this->filter)
            ->get();
        return $this->showAll($category);
    }
}
