<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\MaintenanceStatus;
use App\Models\Brand;
use App\Models\Client;
use App\Models\Maintenance;
use App\Models\SpacePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MaintenanceController extends Controller
{
    public function __construct()
    {
        //create read update delete
        $this->middleware(['permission:maintenances_read'])->only('index', 'show');
        $this->middleware(['permission:maintenances_create'])->only('create', 'store');
        $this->middleware(['permission:maintenances_update'])->only('edit', 'update');
        $this->middleware(['permission:maintenances_delete'])->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $maintenances = Maintenance::when($request->search, function ($q) use ($request) {

            return $q->where('id', $request->search)
                        ->orWhere('imei', 'like', '%' . request()->search . '%')
                        ->orWhereHas('client', function($q){
                            return $q->where('name', 'like', '%' . request()->search . '%');
                        })->orWhereHas('brand', function($q){
                            return $q->where('name', 'like', '%' . request()->search . '%');
                        });

                    })->when($request->status, function ($q) use ($request) {
                         return $q->where('status', $request->status);
            })->with('client', 'brand')->paginate(5);

        $statuses = MaintenanceStatus::cases();

        return view('dashboard.maintenances.index', compact('maintenances', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        $brands = Brand::all();

        return view('dashboard.maintenances.create', compact('clients', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'brand_id' => 'required|exists:brands,id',
            'model' => 'required',
            'imei' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
        ]);

        Maintenance::create($request->all());

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('admin.maintenances.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Maintenance $maintenance)
    {
        $clients = Client::all();
        $brands = Brand::all();
        $statuses = MaintenanceStatus::cases();
        $space_parts = SpacePart::all();

        return view('dashboard.maintenances.show', compact('clients', 'brands', 'maintenance', 'statuses', 'space_parts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Maintenance $maintenance)
    {
        $clients = Client::all();
        $brands = Brand::all();
        $statuses = MaintenanceStatus::cases();
        $space_parts = SpacePart::all();

        return view('dashboard.maintenances.edit', compact('clients', 'brands', 'maintenance', 'statuses', 'space_parts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Maintenance $maintenance)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
            'client_id' => 'required|exists:clients,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric',
            'model' => 'required',
            'imei' => 'required',
            'description' => 'required',



            'parts.*.id' => 'nullable|exists:space_parts,id',
            'parts.*.quantity' => 'nullable|numeric|min:1',
            'parts.*.price' => 'nullable|numeric|min:0',
        ]);

        $validator->after(function ($validator) use ($request) {
            foreach ($request->parts ?? [] as $index => $part) {

                if (!empty($part['id'])) {

                    if (empty($part['quantity'])) {
                        $validator->errors()->add(
                            "parts.$index.quantity",
                            'يرجى إدخال كمية قطعة الغيار.'
                        );
                    }

                    if ($part['price'] === '' || $part['price'] === null) {
                        $validator->errors()->add(
                            "parts.$index.price",
                            'يرجى إدخال سعر قطعة الغيار.'
                        );
                    }
                }
            }
        });

        $validator->validate();
        DB::transaction(function () use ($request, $maintenance) {

            $maintenance->update([
                'status'      => $request->status,
                'client_id'   => $request->client_id,
                'brand_id'    => $request->brand_id,
                'model'       => $request->model,
                'imei'        => $request->imei,
                'price'       => $request->price,
                'description' => $request->description,
                'notes'       => $request->notes,
            ]);

            $parts = [];

            if ($request->filled('parts')) {

                foreach ($request->parts as $part) {

                    if (empty($part['id'])) {
                        continue;
                    }

                    $parts[$part['id']] = [
                        'quantity' => $part['quantity'],
                        'price'    => $part['price'],
                    ];
                }
            }

            $maintenance->spaceParts()->sync($parts);

        });

        session()->flash('success', __('site.updated_successfully'));

        return redirect()->route('admin.maintenances.index');



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maintenance $maintenance)
    {
        //
    }
}
