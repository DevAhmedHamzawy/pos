<?php

namespace App\Http\Controllers\Admin\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function create(Client $client)
    {
        $categories = Category::with('products')->get();
        $orders = $client->orders()->with('products')->paginate(5);
        return view('dashboard.clients.orders.create', compact('client', 'categories', 'orders'));
    }

    public function store(Request $request, Client $client)
    {
        $request->validate([
            'products' => 'required|array',
        ]);

        $this->attachOrder($request, $client);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('admin.orders.index');
    }

    public function edit(Client $client, Order $order)
    {
        $categories = Category::with('products')->get();
        $orders = $client->orders()->with('products')->paginate(5);

        return view('dashboard.clients.orders.edit', compact('client', 'order', 'categories', 'orders'));
    }

    public function update(Request $request, Client $client, Order $order)
    {
        $request->validate([
            'products' => 'required|array',
        ]);

        $this->detachOrder($order);

        $this->attachOrder($request, $client);

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('admin.orders.index');

    }


    private function attachOrder($request, $client)
    {
         $order = $client->orders()->create();

        $order->products()->attach($request->products);

        $total_price = 0;

        foreach($request->products as $id => $quantity)
        {
            $product = Product::findOrFail($id);
            $total_price += $product->sale_price * $quantity['quantity'];

            $product->decrement('stock', $quantity['quantity']);
        }

        $order->update(['total_price' => $total_price]);
    }

    private function detachOrder($order)
    {
        foreach ($order->products as $product) {
            $product->increment('stock', $product->pivot->quantity);
        }

        $order->delete();
    }
}
