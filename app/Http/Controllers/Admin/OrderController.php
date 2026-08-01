<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
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
        $orders = Order::query();

        if ($request->filled('search')) {
            $orders->where(function ($q) use ($request) {
                $q->whereHas('client', function ($client) use ($request) {
                    $client->where('name', 'like', '%' . $request->search . '%');
                })
                ->orWhere('id', $request->search);
            });
        }

        $orders = $orders->paginate(10);

        return view('dashboard.orders.index', compact('orders'));
    }

    public function create()
    {
        $categories = Category::with('products')->get();
        return view('dashboard.orders.create', compact('categories'));
    }

    public function store(Request $request)
    {
       $request->validate([
            'products' => 'required|array',
        ]);

        $order = $this->attachOrder($request);

        activity()->log('قام '.auth()->user()->full_name.' بإضافة طلب'.$order->id);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('admin.orders.index');
    }

    public function edit(Order $order)
    {
        $categories = Category::with('products')->get();

        return view('dashboard.orders.edit', compact('order', 'categories'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'products' => 'required|array',
        ]);

        $this->detachOrder($request, $order);

        $order = $this->attachOrder($request);

        activity()->log('قام '.auth()->user()->full_name.' بتعديل طلب'.$order->id);

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('admin.orders.index');

    }

    public function products(Order $order)
    {
        $products = $order->products;

        return view('dashboard.orders._products', compact('products', 'order'));
    }

    public function receipt(Order $order)
    {
        $order->load('products');

        return view('dashboard.orders.receipt', compact('order'));
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

        if($order->client){
            activity()->log('قام '.auth()->user()->full_name.' بحذف طلب'.$order->id.' للعميل '.$order->client->name);
        }else{
            activity()->log('قام '.auth()->user()->full_name.' بحذف طلب'.$order->id);
        }

        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('admin.orders.index');
    }

    private function attachOrder($request)
    {
        $order = Order::create($request->only('start', 'benefit', 'installment_number', 'total_after_benefit', 'installment_value', 'discount', 'total_price'));

        $order->products()->attach($request->products);

        $total_price = 0;

        foreach($request->products as $id => $quantity)
        {
            $product = Product::findOrFail($id);
            // $total_price += $product->sale_price * $quantity['quantity'];

            $product->decrement('stock', $quantity['quantity']);

            $request->merge(['product_id' => $product->id, 'user_id' => auth()->user()->id, 'order_id' => $order->id, 'order_number' => $order->id, 'quantity' => $quantity['quantity'], 'status' => 'stock_transfer', 'type' => 'out', 'description' => 'عملية شراء']);
            DB::table('products_log_activity')->insert($request->only(['product_id', 'client_id', 'order_id', 'user_id', 'quantity', 'status', 'type', 'description']));

        }

        return $order;
    }

    private function detachOrder($request, $order)
    {
        foreach ($order->products as $product) {
            $product->increment('stock', $product->pivot->quantity);

            $request->merge(['product_id' => $product->id, 'user_id' => auth()->user()->id, 'order_id' => $order->id, 'order_number' => $order->id, 'quantity' => $product->pivot->quantity, 'status' => 'inventory_correction', 'type' => 'in', 'description' => 'تصحيح المخزن']);
            DB::table('products_log_activity')->insert($request->only(['product_id', 'client_id', 'order_id', 'user_id', 'quantity', 'status', 'type', 'description']));
        }

        $order->delete();
    }
}
