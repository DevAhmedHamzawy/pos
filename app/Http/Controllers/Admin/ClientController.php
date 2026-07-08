<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clients = Client::when($request->search, function ($q) use ($request) {

            return $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('phone', 'like', '%' . $request->search . '%')
                ->orWhere('address', 'like', '%' . $request->search . '%');
        })->latest()->paginate(5);
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

        Client::create($request_data);

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

        session()->flash('success', __('site.updated_successfully'));

        return redirect()->route('admin.clients.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('admin.clients.index');
    }
}
