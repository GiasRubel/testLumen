<?php

namespace App;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use SoftDeletes;

    protected $appends = ['primary_image', 'liked'];
    protected $hidden = ['user_id', 'summary', 'updated_by', 'deleted_by', 'deleted_at','product_url','summery','description','htmlized_summary','htmlized_description'];
    protected $fillable = ['title', 'package_id', 'price', 'user_id', 'service_id', 'category_id', 'type_id', 'location_id', 'address', 'currency', 'status', 'summary', 'description', 'created_by', 'updated_by', 'deleted_by'];

    public function values()
    {
        return $this->morphMany( FieldValue::class, 'valuable' );
    }

    public function location()
    {
        return $this->belongsTo( Location::class );
    }

    public function serviceContacts()
    {
        return $this->belongsToMany( ServiceContact::class, 'contact_product', 'product_id', 'contact_id' );
    }

    public function images()
    {
        return $this->morphMany( Media::class, 'mediable' );
    }

    public function service()
    {
        return $this->belongsTo( Service::class );
    }

    public function category()
    {
        return $this->belongsTo( ServiceCategory::class );
    }

    public function type()
    {
        return $this->belongsTo( ServiceType::class );
    }

    public function package() //font-end relation
    {
        return $this->belongsTo( Package::class );
    }

    public function displays()
    {
        return $this->belongsToMany( ServiceDisplay::class, 'product_custom_display', 'product_id', 'display_id' );
    }

    public function views()
    {
        return $this->morphMany( View::class, 'viewable' );
    }

    public function likes()
    {
        return $this->morphMany( Like::class, 'likeable' );
    }

    public function messages()
    {
        return $this->hasMany( Message::class, 'messageable_id' )->where( 'messageable_type', 'App\Product' );
    }

    public function getPrimaryImageAttribute()
    {
        return $this->images()->wherePrimary( 1 )->latest()->select( ['file', 'title'] )->first();
    }

    public function getLikedAttribute()
    {
        if (isset( Auth::guard( 'api' )->user()->id )) {
            $liked = $this->likes()->where( [['user_id', Auth::guard( 'api' )->user()->id], ['likeable_type', 'App\Product']] )->first();
            return $liked ? true : false;
        } else {
            return false;
        }
    }

    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply( $query );
    }

    public function charts()
    {
        return $this->morphMany('App\Chart', 'chartable');
    }

}
