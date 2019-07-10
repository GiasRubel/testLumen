<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 3/10/2018
 * Time: 9:34 AM
 */

namespace App\Traits;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait ApiResponse
{
    private function successResponse($data , $code)
    {
        return response()->json($data,$code);
    }

    protected function showAll(Collection $collection, $code = 200)
    {
        if ($collection->count() > 0){
            return $this->successResponse(['result'=>$collection], $code);
        }
        return response('',204);
    }

    protected function pagination(LengthAwarePaginator $data, $code = 200)
    {
        return $this->successResponse(['result' => $data],$code);
    }

    protected function showOne(Model $model, $code = 200)
    {
        return response()->json(['result'=>$model],$code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json(['errors'=>$message,'code'=>$code],$code);
    }

    protected function showMessage($message, $code = 200)
    {
        return response()->json(['result'=>$message],$code);
    }

    public function notifyMessage($data, $message = 'successfully created.',$code=201)
    {
        return response()->json(['result'=>$data,'success'=>$message],$code);
    }

}