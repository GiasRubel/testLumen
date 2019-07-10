<?php


namespace App\Http\Controllers;


use App\Filters\ViewFilter;
use App\Product;
use App\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDashboardController extends ApiController
{

    public function __construct(ViewFilter $filters)
    {
        parent::__construct($filters);
    }

    /**
     *Auth user all product list
     */
    public function index()
    {
        $products = $this->userProducts()->with('service','type')
            ->filter($this->filter)
            ->get();
        return $this->showAll($products);
    }

    /**
     * Auth user total product list;
     * @return \Illuminate\Http\JsonResponse
     */
    public function totalProduct()
    {
        $totalProduct = $this->userProducts()->get()->count();
        return $this->showMessage($totalProduct);
    }

    /**
     * total sell product
     * @return \Illuminate\Http\JsonResponse
     */
    public function totalSell()
    {
        $totalSell = Product::where(['user_id' => Auth::id(), 'status' => 4])->get()->count();
        return $this->showMessage($totalSell);
    }

    /**
     * Total sell group by updated_at wise
     * @return \Illuminate\Http\JsonResponse
     */
    public function totalSellByDays()
    {
       $totalSell =  Product::where(['user_id' => Auth::id(), 'status' => 4])
           ->select(
               DB::raw('Date(updated_at) as Date, count(*) as Sell')
           )
           ->groupBy(DB::raw('Date(updated_at)'))
           ->orderBy(DB::raw('Date(updated_at)'))
           ->filter($this->filter)
           ->get();
        return $this->showMessage($totalSell->makeHidden('primary_image'));
    }

    /**
     *Total sell group by updated_at in month
     */
    public function totalSellByMonth()
    {
        $totalSell =  Product::where(['user_id' => Auth::id(), 'status' => 4])
            ->select(DB::raw('Date(updated_at) as Month'), DB::raw('count(*) as Sell'))
            ->groupBy(DB::raw('Month(updated_at)'))
            ->orderBy(DB::raw('Month(updated_at)'))
            ->filter($this->filter)
            ->get();
        return $this->showMessage($totalSell->makeHidden('primary_image'));
    }

    /**
     * Auth user total view product
     * @return \Illuminate\Http\JsonResponse
     */
    public function totalView()
    {
        $product_id = $this->userProducts()->pluck('id');
        $totalViews = View::whereIn('viewable_id', $product_id)
            ->where('viewable_type','App\Product')
            ->filter($this->filter)
            ->get()
            ->count();
        return $this->showMessage($totalViews);
    }

    /**
     * Product total view count date ways
     * @return \Illuminate\Http\JsonResponse
     */
    public function totalViewByDays()
    {
        $product_id = $this->userProducts()->pluck('id');
        $totalView =  View::whereIn('viewable_id', $product_id)
            ->select(DB::raw('Date(read_at) as Days'), DB::raw('count(*) as Views'))
            ->groupBy(DB::raw('Date(read_at)'))
            ->orderBy(DB::raw('Date(read_at)'))
            ->filter($this->filter)
            ->get();
        return $this->showMessage($totalView);
    }

    /**
     * Product total view count month ways
     * @return \Illuminate\Http\JsonResponse
     */
    public function totalViewByMont()
    {
        $product_id = $this->userProducts()->pluck('id');
        $totalView =  View::whereIn('viewable_id', $product_id)
            ->select(DB::raw('Date(read_at) as Month'), DB::raw('count(*) as Views'))
            ->groupBy(DB::raw('Month(read_at)'))
            ->orderBy(DB::raw('Month(read_at)'))
            ->filter($this->filter)
            ->get();
        return $this->showMessage($totalView);
    }

    /**
     * @return mixed
     */
    private function userProducts()
    {
        return Product::whereUserId(Auth::id());
    }
}