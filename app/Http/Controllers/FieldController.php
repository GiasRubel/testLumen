<?php

namespace App\Http\Controllers;

use App\FieldGroup;
use App\Filters\ServiceFilter;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FieldController extends ApiController
{
    public function __construct(ServiceFilter $filters)
    {
        parent::__construct( $filters );
    }

    /**
     * Return the field and field options on specific service and group id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fieldWithOption($service_id, $group_id)
    {
        $service = Service::where( 'id', $service_id )->firstOrFail();
        $service = $service->serviceFields()->wherePivot( 'group_id', $group_id )->with( 'options' )
            ->filter( $this->filter )
            ->orderBy('order')
            ->groupBy( 'id' )
            ->get();
        return $this->showAll( $service );
    }


    /**
     * group with field and field options
     * @param FieldGroup $fieldGroup
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function groupsWithfieldFieldOptions(FieldGroup $fieldGroup, Request $request, $service_id)
    {
        $group = $fieldGroup->whereHas( 'fields', function ($query) use ($service_id) {
            $query->where( 'field_service.service_id', $service_id );
        } )->orderBy( 'order');

        if ($request->has( 'group_types' )) {
            $type_id = $request->query()['group_types'];
            $group = $group->whereHas( 'fields', function ($query) use ($type_id) {
                $query->where( 'field_service.type_id', $type_id );
            } );
        }

        if ($request->has( 'group_categories' )) {
            $category_id = $request->query()['group_categories'];
            $group = $group->whereHas( 'fields', function ($query) use ($category_id) {
                $query->where( 'field_service.category_id', $category_id );
            } );
        }

        $group = $group->with( ['fields' => function ($field) use ($request) {
            //$field->wherePivot('category_id', $category_id)->wherePivot('type_id', $type_id);
            if ($request->has( 'group_types' ) && $request->has( 'group_categories' )) {
                $field->orderBy( 'order')->where( 'field_service.category_id', $request->query()['group_categories'] )->where( 'field_service.type_id', $request->query()['group_types'] );
            } elseif ($request->has( 'group_types' )) {
                $field->orderBy( 'order')->where( 'field_service.type_id', $request->query()['group_types'] );
            } elseif ($request->has( 'group_categories' )) {
                $field->orderBy( 'order')->where( 'field_service.category_id', $request->query()['group_categories'] );
            }
        }, 'fields.options'] )->filter( $this->filter );
        $group = $group->get();
        return $this->showAll( $group );
    }
}
