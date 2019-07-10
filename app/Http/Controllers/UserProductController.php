<?php


namespace App\Http\Controllers;


use App\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserProductController extends ApiController
{
    /**
     * most popular product depend on views and filter by days
     * @param $days
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function mostPopular($days)
    {
        $from = Carbon::now()->toDateString();
        $to = Carbon::now()->subDays($days)->toDateString();
        $products = Product::where(['user_id' => Auth::id(), 'status' => 3])
            ->whereHas('views', function ($view) use ($from, $to) {
                $view->whereBetween('read_at', [$to, $from]);
            })
            ->withCount(['views' => function ($view) use ($from, $to) {
                $view->whereBetween('read_at', [$to, $from]);
            }])
            ->with('service')
            ->filter($this->filter)
            ->orderBy('views_count', 'desc')
            ->get();
        return $this->showAll($products);
    }


    /**
     * @param $days
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function mostRecentSell($days)
    {
        $from = Carbon::now()->toDateTimeString();
        $to = Carbon::now()->subDays($days)->toDateTimeString();
        $products = Product::where(['user_id' => Auth::id(), 'status' => 4])
            ->with('service')
            ->whereBetween('updated_at', [$to, $from])
            ->filter($this->filter)
            ->get();
        return $this->showAll($products);
    }

    /**
     * Auth user delete her product
     * @param Product $product
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Product $product, $id)
    {
        $user_product = $product->findOrFail($id);
        if (Auth::id() !== $user_product->user_id){
            return $this->errorResponse('product not belong to this user.',403);
        }
        $user_product->delete();
        return $this->showMessage('Product has been successfully deleted',200);
    }


}