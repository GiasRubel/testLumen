<?php

namespace App\Http\Controllers;

use App\Country;
use App\Filters\StateFilter;
use App\State;
use Illuminate\Http\Request;

class StateController extends ApiController
{
    public function __construct(StateFilter $filters)
    {
        parent::__construct($filters);
    }

    /**
     * States list
     * @return \Illuminate\Http\JsonResponse
     */
    public function states()
    {
        $states = State::whereStatus(3)
            ->filter($this->filter)
            ->get();
        return $this->showAll($states);
    }
}
