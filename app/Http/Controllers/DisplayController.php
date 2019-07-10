<?php

namespace App\Http\Controllers;

use App\ServiceDisplay;

class DisplayController extends ApiController
{
    public function displayDependOnServiceTypeCategory()
    {
        $displays = ServiceDisplay::whereStatus(3)
            ->filter($this->filter)
            ->get();
        return $this->showAll($displays);
    }
}
