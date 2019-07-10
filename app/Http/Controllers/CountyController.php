<?php

namespace App\Http\Controllers;

use App\County;
use App\Filters\CountyFilter;

class CountyController extends ApiController
{
    /**
     * City list
     * @return \Illuminate\Http\JsonResponse
     */
    public function __construct(CountyFilter $filters)
    {
        parent::__construct($filters);
    }

    public function counties()
    {
        $counties = County::whereStatus(3)
            ->filter($this->filter)
            ->get();
        return $this->showAll($counties);
    }

}
