<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    public function __construct()
    {
        //create read update delete
        $this->middleware(['permission:brands_read'])->only('index');
        $this->middleware(['permission:brands_create'])->only('create', 'store');
        $this->middleware(['permission:brands_update'])->only('edit', 'update');
        $this->middleware(['permission:brands_delete'])->only('destroy');
    }
    public function index(Request $request)
    {
        $brands = Brand::when($request->search, function ($q) use ($request) {

            return $q->whereLike('name', '%' . $request->search . '%');

        })->latest()->paginate(10);

        return view('dashboard.brands.index', compact('brands'));

    }//end of index

    public function create()
    {
        return view('dashboard.brands.create');

    }//end of create

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:brands,name',
        ]);

        $brand = Brand::create($request->all());

        activity()->log('قام '.auth()->user()->full_name.' بإضافة ماركة'.$brand->name);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('admin.brands.index');

    }//end of store

    public function edit(Brand $brand)
    {
        return view('dashboard.brands.edit', compact('brand'));

    }//end of edit

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => ['required', Rule::unique('brands', 'name')->ignore($brand->id, 'id')],
        ]);

        $brand->update($request->all());

        activity()->log('قام '.auth()->user()->full_name.' بتعديل ماركة'.$brand->name);

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('admin.brands.index');

    }//end of update

    public function destroy(Brand $brand)
    {
        $brand->delete();

        activity()->log('قام '.auth()->user()->full_name.' بحذف ماركة'.$brand->name);

        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('admin.brands.index');

    }//end of destroy

}//end of controller
