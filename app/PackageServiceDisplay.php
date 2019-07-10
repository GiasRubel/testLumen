<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PackageServiceDisplay extends Pivot
{
    protected $fillable=['package_id','service_display_id'];

    protected $table='custom_displayad';

    public function customAds()
    {
        return $this->belongsToMany(ServiceDisplay::class,'custom_displayad','package_id','service_display_id');
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class);

    }
}
