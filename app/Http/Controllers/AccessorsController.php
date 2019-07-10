<?php

namespace App\Http\Controllers;

use App\Accessor;
use App\ServiceCategory;

class AccessorsController extends ApiController
{
    /**
     * Accessor list depend on category
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $accessors = Accessor::whereStatus(3)
            ->filter($this->filter)
            ->get();
        return $this->showAll($accessors);
    }

}
