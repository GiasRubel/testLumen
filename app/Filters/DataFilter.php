<?php
/**
 * Created by PhpStorm.
 * User: alamincse
 * Date: 8/26/2017
 * Time: 2:49 PM
 */

namespace App\Filters;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DataFilter extends QueryFilter
{

    public function popular($module='news',$order='desc')
    {
        return $this->builder->withCount($module)->orderBY($module.'_count',$order);
    }

    public function status($status=3)
    {
        return $this->builder->where('status',$status);
    }

    public function createdBy( $user='admin')
    {
        return $this->builder->where('created_by',$user);
    }

    public function created($date)
    {
        $this->dateBetween($date,'created_at');
    }

    public function published($date)
    {
        $this->dateBetween($date,'published_at');
    }

    public function publishedToday($filed='published_time')
    {
        $date=$date??Carbon::today();
        $this->builder->where($filed,$date);
    }

    public function dates( $date)
    {
        $dates=[$date[1],$date[2]];
        $this->dateBetween($dates,$date[0]);
    }

    public function duration($times=24)
    {
        $currentLocalTime=Carbon::now(getCurrentTimeZone())->setTimezone('utc');
        $fromLocalTime=Carbon::now(getCurrentTimeZone())->subHour($times)->setTimezone('utc');
        return $this->builder->whereBetween('published_time',[$fromLocalTime,$currentLocalTime]);
    }

    protected function dateBetween( $date, string $filed=null)
    {
        if(is_array($date)) {
            $start = $date[1]??Carbon::now()->format('Y-m-d');
            return $this->builder->whereBetween($filed, [$start, $date[0]]);
        }
        else{
            $date=is_null($date)?Carbon::now()->format('Y-m-d'):$date;
            return $this->builder->whereDate($filed, $date);
        }
    }

    public function order($order='desc')
    {
        $filed=is_array($order)?$order[1]:'title';
        $order=is_array($order)?$order[0]:'desc';
        return $this->builder->orderBy($filed,$order);
    }

    public function orderBY()
    {
        return $this->builder->orderBy($this->request->orderBy,'desc');
    }

    public function take($limit=8)
    {
        return $this->builder->limit($limit);
    }

    public function latest($filed='updated_at')
    {
        return $this->builder->orderBy($filed,'desc');
    }

    public function title($title)
    {
        return $this->builder->where('title','like','%' . $title . '%');
    }


    //This Filter Work Only for Group, Contest, Program,  Events
    public function category($id)
    {
        return $this->builder->whereHas('categories', function ($query) use ($id) {
            if(is_array($id)){
                $query->whereIn('category_id', $id)
                    ->orderByRaw(DB::raw("FIELD(category_id,".implode(',', $id).")"));
            }
            else {
                $query->where('category_id', $id);
            }
        });
    }

    public function tag($title)
    {
        if(!is_numeric($title)){
        $tag=Tag::where('title',$title)->first(['id']);
        $title=$tag->id;
        }
        return $this->builder->whereHas('tags', function ($query) use ($title) {
            $query->where('tag_id', $title);
        });
    }

    public function withRelation($title)
    {
        return $this->builder->with([$title=>function ($query) use($title){
            $query->select($title.'.id', 'title');
        }]);
    }

    public function hosts()
    {
        return $this->builder->with(['hosts'=>function ($query){
            $query->select('users.id', 'username');
        }, 'hosts.profile'=>function($query){
            $query->select('user_id','display_name','image');
        }]);
    }

    public function countComments()
    {
        /*return $this->builder->withCount('comments')->with(['comments'=>function ($query){
            $query->select('comments.id', 'comment','created_by');
        }, 'comments.users'=>function($query){
            $query->select('username');
        }]);*/
        return $this->builder->withCount('comments');
    }

    public function countLikes()
    {
        /*return $this->builder->withCount('likes')->with(['likes'=>function ($query){
            $query->select('user_id','like', 'created_at');
        }, 'likes.user'=>function($query){
            $query->select('id','username');
        }, 'likes.user.profile'=>function($query)
        {
            $query->select('user_id','display_name','profile');
        }]);*/
    }
    /*public function urlKey($url)
    {
        return $this->builder->where('urlKey',$url);
    }*/
    public function blogCategory($id)
    {
        return $this->builder->whereHas('blogCategory', function ($query) use ($id){
                $query->where('category_id', $id);
        });
    }

    public function upcoming()
    {
        return $this->builder->where('updated_at','>=',Carbon::now());
    }

    public function today()
    {
        return $this->builder->whereDate('updated_at',Carbon::today()->format('Y-m-d'));
    }

    public function except(array $ids)
    {
        return $this->builder->whereNotIn('id', $ids);
    }

}