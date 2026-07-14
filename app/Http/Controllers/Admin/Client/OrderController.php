<?php

namespace App\Http\Controllers\Admin\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Client;
use App\Models\Installment;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        //create read update delete
        $this->middleware(['permission:brands_create'])->only('create', 'store');
        $this->middleware(['permission:brands_update'])->only('edit', 'update');
    }

    public function index(Request $request, Client $client)
    {
        $orders = $client->orders()->when($request->search, function ($q) use ($request) {

            return $q->whereId($request->search);

        })->when($request->installment_status, function ($q) use ($request) {

            return $q->where('installment_status', $request->installment_status);

        })->latest()->paginate(5);

        return view('dashboard.clients.orders.index', compact('client', 'orders'));
    }

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

        $this->detachOrder($request, $order);

        $this->attachOrder($request, $client);

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('admin.orders.index');

    }


    private function attachOrder($request, $client)
    {
        $order = $client->orders()->create($request->only('start', 'benefit', 'installment_number', 'total_after_benefit', 'installment_value'));

        $order->products()->attach($request->products);

        $total_price = 0;

        foreach($request->products as $id => $quantity)
        {
            $product = Product::findOrFail($id);
            $total_price += $product->sale_price * $quantity['quantity'];

            $product->decrement('stock', $quantity['quantity']);

            $request->merge(['product_id' => $product->id, 'user_id' => auth()->user()->id, 'client_id' => $client->id, 'order_id' => $order->id, 'order_number' => $order->id, 'quantity' => $quantity['quantity'], 'status' => 'stock_transfer', 'type' => 'out', 'description' => 'عملية شراء']);
            DB::table('products_log_activity')->insert($request->only(['product_id', 'client_id', 'order_id', 'user_id', 'quantity', 'status', 'type', 'description']));

        }

        $order->update(['total_price' => $total_price]);

        if($order->installment_number > 0)
        {
            for($i=1;$i<=$order->installment_number;$i++){

            $order->installments()->create([
                'client_id'=>$order->client_id,
                'installment_no'=>$i,
                'amount'=>$order->installment_value,
                'due_date'=>Carbon::parse(now())->addMonths($i),
            ]);

}
        }
    }

    private function detachOrder($request, $order)
    {
        foreach ($order->products as $product) {
            $product->increment('stock', $product->pivot->quantity);

            $request->merge(['product_id' => $product->id, 'user_id' => auth()->user()->id, 'client_id' => $order->client->id, 'order_id' => $order->id, 'order_number' => $order->id, 'quantity' => $product->pivot->quantity, 'status' => 'inventory_correction', 'type' => 'in', 'description' => 'تصحيح المخزن']);
            DB::table('products_log_activity')->insert($request->only(['product_id', 'client_id', 'order_id', 'user_id', 'quantity', 'status', 'type', 'description']));
        }

        $order->delete();
    }

    public function productSearch(Request $request)
    {
        $search = $request->search;
        $products = Product::whereTranslationLike('name', '%' . $request->search . '%')
                ->orWhere('imei', $request->search)
                ->orWhereHas('brand', function ($q) use ($request) {
                    $q->where('name', $request->search);
                })->get();
        return response()->json(['html' => view('dashboard.clients.orders._products', compact('products'))->render()]);
    }
}
