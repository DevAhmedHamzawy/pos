<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        //create read update delete
        $this->middleware(['permission:orders_read'])->only('index', 'products');
        $this->middleware(['permission:orders_delete'])->only('destroy');
    }
    public function index(Request $request)
    {
        $orders = Order::whereHas('client', function($q){
            return $q->where('name', 'like', '%' . request()->search . '%');
        })->paginate(5);

        return view('dashboard.orders.index', compact('orders'));
    }

    public function products(Order $order)
    {
        $products = $order->products;

        return view('dashboard.orders._products', compact('products', 'order'));
    }

    public function destroy(Order $order)
    {
        foreach ($order->products as $product) {
            $product->increment('stock', $product->pivot->quantity);

            DB::table('products_log_activity')->insert([
                'product_id'  => $product->id,
                'user_id'     => auth()->id(),
                'client_id'   => $order->client_id,
                'order_id'    => $order->id,
                'order_number' => $order->id,
                'quantity'    => $product->pivot->quantity,
                'status'      => 'return',
                'type'        => 'in',
                'description' => 'عملية مرتجع',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        $order->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('admin.orders.index');
    }
}
