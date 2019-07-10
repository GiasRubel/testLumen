<?php

namespace App\Http\Controllers;

use App\ServiceType;

class ServiceTypeCategoryController extends ApiController
{
    public function serviceWithTypeHerCategory($title)
    {
       $types = ServiceType::whereHas('service', function ($service) use($title){
           $service->whereTitle(str_replace('-',' ', $title));
       })
           ->withCount('products')
           ->with(['categories'=> function($category){
               $category->where('status',3)->select('id','title','service_id')->withCount('products');
           }])
           ->filter($this->filter)
           ->get();

        return $this->showAll($types);
    }
}
