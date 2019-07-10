<?php

namespace App\Http\Controllers;

use App\Service;

class ServiceController extends ApiController
{
    /**
     * Active service list
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $services = Service::whereStatus(3)
            ->filter($this->filter)
            ->get();

        return $this->showAll($services);
    }


    /**
     * return service with her category list
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function serviceWithCategory()
    {
        $services = Service::whereStatus(3)->with('categories')
            ->filter($this->filter)
            ->get();
        return $this->showAll($services);
    }


}
