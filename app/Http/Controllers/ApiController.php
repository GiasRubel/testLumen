<?php

namespace App\Http\Controllers;

use App\Filters\DataFilter;
use App\Filters\ServiceFilter;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;

class ApiController extends Controller
{
    use ApiResponse;

    protected $filter;

    public function __construct(ServiceFilter $filters)
    {
        $this->filter=$filters;
    }
}

