<?php


namespace App\Http\Controllers;


use App\Package;
use Illuminate\Support\Facades\Auth;

class UserPackageController extends ApiController
{
    /**
     * Auth user current package with validity of package and package feature.
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function index()
    {
        $packages = Auth::user()->packages()->with('features')->get();
        return $this->showAll($packages);
    }
}