<?php

namespace App\Http\Controllers;

use App\User;

class PopularAgentController extends ApiController
{
    public function popular()
    {
        $agents = User::whereHas('accessors', function ($accessor){
            $accessor->whereTitle('agent');
        })
            ->filter($this->filter)
            ->get();

        return $this->showAll($agents);
    }
}
