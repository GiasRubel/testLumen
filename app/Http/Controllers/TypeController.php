<?php

namespace App\Http\Controllers;

use App\ServiceType;

class TypeController extends ApiController
{

    /**
     * type depend on service and category
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $types = ServiceType::whereStatus(3)
            ->filter($this->filter)
            ->get();
        return $this->showAll($types);
    }
}
