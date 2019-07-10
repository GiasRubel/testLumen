<?php

namespace App\Http\Controllers;

use App\Filters\ServiceFilter;
use App\OauthAccessToken;
use App\Product;
use App\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductDetailController extends ApiController
{

    public function details($id, $service_title, Request $request, $title = null)
    {
        $product = Product::findOrFail( $id );
        $title = str_replace( '-', ' ', $service_title );
        $service = Service::whereTitle( $title )->firstOrFail();
        $product = $product->whereHas( 'service', function ($query) use ($service) {
            $query->whereId( $service->id );
        } )->with( ['category', 'type', 'images', 'location', 'serviceContacts', 'service' => function ($query) use ($service) {
            $query->where( 'services.id', $service->id );
        }, 'service.fieldGroups' => function ($query) use ($product) {
            $query->orderBy( 'order' )->groupBy( 'group_id' );
        }, 'service.fieldGroups.fields' => function ($query) use ($product) {
            if ($product->type_id && $product->category_id) {
                return $query->where( 'field_service.type_id', $product->type_id )
                    ->where( 'field_service.category_id', $product->category_id )
                    ->orderBy( 'order' )->groupBy( 'field_id' );
            } elseif ($product->type_id) {
                return $query->where( 'field_service.type_id', $product->type_id )
                    ->orderBy( 'order' )->groupBy( 'field_id' );
            } elseif ($product->category_id) {
                return $query->where( 'field_service.category_id', $product->category_id )
                    ->orderBy( 'order' )->groupBy( 'field_id' );
            } else {
                return $query->orderBy( 'order' );
            }
        }, 'service.fieldGroups.fields.values' => function ($query) use ($product) {
            $query->where( 'valuable_id', $product->id );
        }, 'service.fieldGroups.fields.values.options', 'charts'] )->findOrFail( $product->id );
        $data['user_id'] = !is_null( Auth::id() ) ? Auth::id() : null;
        $data['ip'] = $request->ip();
        $data['read_at'] = Carbon::now()->setTimezone( 'UTC' );
        $product->views()->create( $data );
        return $this->showOne( $product->makeHidden( 'primary_image' )->makeVisible( ['user_id', 'product_url', 'description'] ) );
    }

}
