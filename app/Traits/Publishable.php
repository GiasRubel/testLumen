<?php

namespace App\Traits;

trait Publishable
{
    public function publishStatus(){
        $model = getModelName()::findOrfail(request('id'));
        if(request('publish_status')== 'on'){ $requestData['status'] = 1;}
        else{$requestData['status'] = 0;}
        $model->timestamps = false;
        $result = $model->update($requestData);
        return response()->json(['success'=>$result]);
    }

    public function publishStatusConfirm(){
        $model = getModelName()::findOrfail(request('id'));
        $requestData = [];
        if(request('publish_status')== 'reject'){
            $requestData['status'] = 2;
        }elseif(request('publish_status') == 'publish'){
            $requestData['status'] = 3;
        }elseif(request('publish_status') == 'pending'){
            $requestData['status'] = 1;
        }
        $model->timestamps = false;
        $result = $model->update($requestData);
        return response()->json(['success'=>$result]);
    }

}