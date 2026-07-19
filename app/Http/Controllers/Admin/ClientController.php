<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct()
    {
        //create read update delete
        $this->middleware(['permission:clients_read'])->only('index');
        $this->middleware(['permission:clients_create'])->only('create', 'store');
        $this->middleware(['permission:clients_update'])->only('edit', 'update');
        $this->middleware(['permission:clients_delete'])->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clients = Client::when($request->search, function ($q) use ($request) {

            return $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('phone', 'like', '%' . $request->search . '%')
                ->orWhere('address', 'like', '%' . $request->search . '%');
        })->latest()->paginate(10);
        return view('dashboard.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|array|min:1',
            'phone.0' => 'required',
            'address' => 'required'
        ]);

        $request_data = $request->all();
        $request_data['phone'] = array_filter($request->phone);

        $client = Client::create($request_data);

        activity()->log('قام '.auth()->user()->full_name.' بإضافة عميل'.$client->name);


        session()->flash('success', __('site.added_successfully'));

        return redirect()->route('admin.clients.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('dashboard.clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
         $request->validate([
            'name' => 'required',
            'phone' => 'required|array|min:1',
            'phone.0' => 'required',
            'address' => 'required'
        ]);

        $request_data = $request->all();
        $request_data['phone'] = array_filter($request->phone);

        $client->update($request_data);

        activity()->log('قام '.auth()->user()->full_name.' بتعديل عميل'.$client->name);

        session()->flash('success', __('site.updated_successfully'));

        return redirect()->route('admin.clients.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();

        activity()->log('قام '.auth()->user()->full_name.' بحذف عميل'.$client->name);

        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('admin.clients.index');
    }
}
