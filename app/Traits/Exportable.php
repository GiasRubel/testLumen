<?php
namespace App\Traits;
use App\Filters\DataFilter;
use PDF;
use Excel;


trait Exportable{

    public function pdf($modelItem)
    {
        $modelItem = getModelName()::findOrFail($modelItem);
        $pdf = PDF::loadView(config('viewUrl.rbnyAdminLayouts').'elements.export'.'.pdf',['modelItem'=>$modelItem])->setPaper('a4','portrait');
        return $pdf->download($modelItem->title.".pdf");
    }
    public function print($modelItem)
    {
        $modelItem=getModelName()::findOrFail($modelItem);
        return view(config('viewUrl.rbnyAdminLayouts').'elements.export'.'.print', compact('modelItem'));
    }
    public function listPdf(DataFilter $filters)
    {
        $modelItems = getModelName()::filter($filters)->get();
        $pdf = PDF::loadView(config('viewUrl.rbnyAdminLayouts').'elements.export'.'.listPdf',['modelItems'=>$modelItems])->setPaper('a4','portrait');
        return $pdf->download(camelCaseToSlug(baseRoute()).".pdf");
    }
    public function listPrint(DataFilter $filters)
    {
        $modelItems = getModelName()::filter($filters)->get();
        return view(config('viewUrl.rbnyAdminLayouts').'elements.export'.'.listPrint',compact('modelItems'));
    }
    public function listExcel($fields=null, DataFilter $filters)
    {
        $fields=$fields?$fields:['title','status','created_at'];
        $modelItems = getModelName()::filter($filters)->get($fields)->toArray();
        return Excel::create(camelCaseToSlug(baseRoute()).'.',function($excel) use ($modelItems){
            $excel->sheet('mySheet',function($sheet) use ($modelItems){
                $sheet->fromArray($modelItems);
            });
        })->download("xlsx");
    }
}