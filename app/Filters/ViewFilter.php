<?php


namespace App\Filters;


use Carbon\Carbon;

class ViewFilter extends ProductFilter
{
    public function days($days = 'read_at,7')
    {
        $data = explode( ',', $days );
        $from = Carbon::now()->toDateTimeString();
        $to = Carbon::now()->subDays( $data[1] )->toDateTimeString();
        $this->builder->whereBetween( $data[0], [$to, $from] );
    }

    public function month($month = 'updated_at,12')
    {
        $data = explode( ',', $month );
        $from = Carbon::now()->toDateTimeString();
        $to = Carbon::now()->subMonth( $data['1'] )->toDateTimeString();
        $this->builder->whereBetween( $data[0], [$to, $from] );
    }

    public function popular($module = 'news', $order = 'desc')
    {
        return $this->builder->withCount( $module )->orderBY( $this->request->latest ?? $module . '_count', $order );
    }
}