<?php

namespace App\Traits;

use Carbon\Carbon;

trait SetTimestamps
{
    public function setCreatedAtAttribute()
    {
        $this->getCurrentTimeFromRequestTime();
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::createFromTimestamp(strtotime($value))
            ->timezone(getCurrentTimeZone())->toDateTimeString();
    }

    public function setUpdatedAtAttribute()
    {
        $this->getCurrentTimeFromRequestTime('updated_at');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::createFromTimestamp(strtotime($value))
            ->timezone(getCurrentTimeZone())->toDateTimeString();
    }

    public function setDeletedAtAttribute()
    {
        $this->getCurrentTimeFromRequestTime('deleted_at');
    }

    public function getDeletedAtAttribute($value)
    {
        if ($value) {
            return Carbon::createFromTimestamp(strtotime($value))
                ->timezone(getCurrentTimeZone())->toDateTimeString();
        }
    }

    public function getCurrentTimeFromRequestTime($filed = 'created_at')
    {
        $this->attributes[$filed] = Carbon::now(getCurrentTimeZone())->setTimezone('UTC');
    }
}