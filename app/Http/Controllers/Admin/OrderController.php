<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
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
        }

        $order->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('admin.orders.index');
    }
}
