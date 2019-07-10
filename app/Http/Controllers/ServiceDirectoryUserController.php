<?php

namespace App\Http\Controllers;

use App\BusinessType;

class ServiceDirectoryUserController extends ApiController
{
    public function directoryWithUser($service_title)
    {
        $businessTypes = BusinessType::whereHas('services', function ($query) use ($service_title){
            $query->where('title', str_replace('-',' ',$service_title));
        })
            ->with(['directoryUsers'=>function($query){
                $query->withCount('businessClients')->orderBy('business_clients_count', 'DESC')->groupBy('id');
            }, 'directoryUsers.user.profile' ,'directoryUsers.businessCompanies'])
            ->get();
        return $this->showAll($businessTypes);
    }
}
