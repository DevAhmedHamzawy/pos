<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\QtyStatus;
use App\QtyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ProductController extends Controller
{

    public function __construct()
    {
        //create read update delete
        $this->middleware(['permission:products_read'])->only('index');
        $this->middleware(['permission:products_create'])->only('create', 'store');
        $this->middleware(['permission:products_update'])->only('edit', 'update');
        $this->middleware(['permission:products_delete'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::when($request->search, function ($q) use ($request) {

            return $q->whereTranslationLike('name', '%' . $request->search . '%');
        })->when($request->category_id, function ($q) use ($request) {

            return $q->where('category_id', $request->category_id);
        })->latest()->paginate(10);
        $categories = Category::all();

        return view('dashboard.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('dashboard.products.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
        ];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => 'required|unique:product_translations,name'];
            $rules += [$locale . '.description' => 'required'];
        }

        $rules += [
            'purchase_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'stock' => 'required|numeric',
        ];

        $request->validate($rules);

        $request_data = $request->all();

        if ($request->image) {

            Image::decode($request->image)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/product_images/' . $request->image->hashName()));

            $request_data['image'] = $request->image->hashName();

        }//end of if

        $product = Product::create($request_data);

        activity()->log('قام '.auth()->user()->full_name.' بإضافة منتج'.$product->name);

        $request->merge(['product_id' => $product->id, 'user_id' => auth()->user()->id, 'quantity' => $request->stock, 'status' => 'stock_purchase', 'type' => 'in', 'description' => 'initial stock']);
        DB::table('products_log_activity')->insert($request->only(['user_id', 'product_id', 'user_id', 'quantity', 'status', 'type', 'description']));


        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('admin.products.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('dashboard.products.edit', compact('product', 'categories', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $rules = [
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
        ];

        foreach (config('translatable.locales') as $locale) {

            $rules += [$locale . '.name' => 'required|unique:product_translations,name,' . $product->id . ',product_id'];
            $rules += [$locale . '.description' => 'required'];
        }

        $rules += [
            'purchase_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'stock' => 'required|numeric',
        ];

        $request->validate($rules);

        $request_data = $request->all();

        if ($request->image) {

            if ($product->image != 'default.png') {

                Storage::disk('public_uploads')->delete('/product_images/' . $product->image);

            }//end of inner if


            Image::decode($request->image)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/product_images/' . $request->image->hashName()));

            $request_data['image'] = $request->image->hashName();

        }//end of if

        $product->update($request_data);

        if ($product->isDirty('stock')) {

        $request->merge(['product_id' => $product->id, 'user_id' => auth()->user()->id, 'quantity' => $request->stock, 'status' => 'inventory_correction', 'type' => 'in', 'description' => 'inventory correction']);
        DB::table('products_log_activity')->insert($request->only(['user_id', 'product_id', 'user_id', 'quantity', 'status', 'type', 'description']));

        }

        activity()->log('قام '.auth()->user()->full_name.' بتعديل منتج'.$product->name);


        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('admin.products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image != 'default.png') {

            Storage::disk('public_uploads')->delete('/product_images/' . $product->image);

        }//end of if

        $name = $product->translate('ar')->name;
        $product->delete();

        activity()->log('قام '.auth()->user()->full_name.' بحذف منتج'.$name);

        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('admin.products.index');
    }

    public function changeQty(Product $product)
    {
        $statuses = QtyStatus::cases();
        $types = QtyType::cases();
        return view('dashboard.products.change_qty', compact('product', 'statuses', 'types'));
    }
    public function updateQty(Product $product, Request $request)
    {
        $new_qty = $request->quantity;

        if($request->type == 'in'){
            $request->merge(['user_id' => auth()->user()->id,'product_id' => $product->id, 'quantity' => $new_qty]);
            $product->update(['stock' => $new_qty + $product->stock]);
        }else{
            if($product->stock < $request->quantity){
                return back()->withErrors([
                    'stock' => __('site.not_enough_stock')
                ]);
            }
            $request->merge(['user_id' => auth()->user()->id, 'product_id' => $product->id, 'quantity' => $new_qty]);
            $product->update(['stock' => $product->stock - $new_qty]);
        }

        DB::table('products_log_activity')->insert($request->only(['user_id', 'product_id', 'quantity', 'status', 'type']));
        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('admin.products.index');
    }

    public function activityLog(Product $product)
    {
        $activities = DB::table('products_log_activity as pla')
            ->leftJoin('users', 'users.id', '=', 'pla.user_id')
            ->leftJoin('clients', 'clients.id', '=', 'pla.client_id')
            ->leftJoin('orders', 'orders.id', '=', 'pla.order_id')
            ->where('pla.product_id', $product->id)
            ->select(
                'pla.*',

                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as user_name"),

                'clients.name as client_name',

                'orders.id as order_id',

                'pla.order_number as order_number'
            )
            ->oldest('pla.id')
            ->paginate(10);
        return view('dashboard.products.activity_log', compact('product', 'activities'));
    }
}
