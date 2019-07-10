<?php

namespace App\Http\Controllers;

use App\Filters\ProductFilter;
use App\Package;
use Illuminate\Http\Request;

class PackageController extends ApiController
{
    /**
     * package list with type,category,service filter
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function index()
    {
        $packages = Package::whereStatus(3)
            ->with('features')
            ->filter($this->filter)
            ->get();
        return $this->showAll($packages);
    }
}
