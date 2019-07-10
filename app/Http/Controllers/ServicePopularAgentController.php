<?php

namespace App\Http\Controllers;

use App\User;

class ServicePopularAgentController extends ApiController
{
    public function popular($service_title)
    {
        $agents = User::whereHas('accessors', function ($accessor) use($service_title){
            $accessor->whereTitle('agent')->whereHas('services',function ($service) use ($service_title){
                $service->whereTitle(str_replace('-',' ', $service_title));
            });
        })->with('profile')
            ->filter($this->filter)
            ->get();
        return $this->showAll($agents);
    }
}
