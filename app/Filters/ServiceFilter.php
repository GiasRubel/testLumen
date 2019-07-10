<?php


namespace App\Filters;


class ServiceFilter extends DataFilter
{
    public function type()
    {
        return $this->getRequestFilter( __FUNCTION__ );
    }

    public function service($service_id)
    {
        return $this->getRequestFilter( __FUNCTION__ );
    }

    public function category($category_id)
    {
        return $this->getRequestFilter( __FUNCTION__ );
    }

    public function ignorePackage()
    {
        return $this->builder->whereDoesntHave( 'package', function ($package) {
            $package->Where( 'id', $this->request->ignorePackage );
        } );
    }

    public function getRequestFilter($relation)
    {
        $value = $this->request->{$relation};
        return $this->builder->whereHas( $relation, function ($type) use ($value) {
            if (is_numeric( $value )) {
                return $type->whereId( $value );
            }
            if (is_string( $value )) {
                return $type->whereTitle( str_replace( '-', ' ', $value ) );
            }
            return $type->whereIn( 'id', $value );
        } );
    }

    public function services()
    {
        $services = $this->request->services;
        return $this->builder->whereHas( 'services', function ($service) use ($services) {
            if (is_numeric( $services )) {
                return $service->whereId( $services );
            }
            return $service->whereTitle( str_replace( '-', ' ', $services ) );
        } );
    }

    public function types()
    {
        $types = $this->request->types;

        return $this->builder->whereHas( 'types', function ($type) use ($types) {

            if (is_numeric( $types ) || is_array( $types )) {
                return $type->whereIn( 'id', (array)$types );
            }
            return $type->whereTitle( str_replace( '-', ' ', $types ) );
        } );
    }

    public function categories()
    {
        $categories = $this->request->categories;
        return $this->builder->whereHas( 'categories', function ($category) use ($categories) {
            if (is_numeric( $categories )) {
                return $category->whereId( $categories );
            }
            return $category->whereTitle( str_replace( '-', ' ', $categories ) );
        } );
    }

    public function field_types()
    {
        $types = $this->request->field_types;
        return $this->builder->whereHas( 'types', function ($type) use ($types) {
            if (is_numeric( $types )) {
                return $type->where( 'field_service.type_id', $types );
            }
            return $type->whereTitle( str_replace( '-', ' ', $types ) );
        } );
    }

    public function field_categories()
    {
        $categories = $this->request->field_categories;
        return $this->builder->whereHas( 'categories', function ($category) use ($categories) {
            if (is_numeric( $categories )) {
                return $category->where( 'field_service.category_id', $categories );
            }
            return $category->whereTitle( str_replace( '-', ' ', $categories ) );
        } );
    }

    public function accessors()
    {
        $accessors = $this->request->accessors;
        return $this->builder->whereHas( 'accessors', function ($accessor) use ($accessors) {
            if (is_numeric( $accessors )) {
                return $accessor->whereId( $accessors );
            }
            return $accessor->whereTitle( str_replace( '-', ' ', $accessors ) );
        } );
    }

    public function service_ids()
    {
        $ids = explode( ',', $this->request->service_ids );
        return $this->builder->whereIn( 'service_id', $ids );
    }

    public function type_ids()
    {
        $ids = explode( ',', $this->request->type_ids );
        return $this->builder->whereIn( 'type_id', $ids );
    }

    public function category_ids()
    {
        $ids = explode( ',', $this->request->category_ids );
        return $this->builder->whereIn( 'category_id', $ids );
    }

}