<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpacePart;
use Illuminate\Http\Request;

class SpacePartController extends Controller
{

    public function __construct()
    {
        //create read update delete
        $this->middleware(['permission:space_parts_read'])->only('index');
        $this->middleware(['permission:space_parts_create'])->only('create', 'store');
        $this->middleware(['permission:space_parts_update'])->only('edit', 'update');
        $this->middleware(['permission:space_parts_delete'])->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $space_parts = SpacePart::when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(10);
        return view('dashboard.space_parts.index', compact('space_parts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.space_parts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:space_parts,name',
            'price' => 'required|numeric',
        ]);

        SpacePart::create($request->all());

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('admin.space_parts.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SpacePart $spacePart)
    {
        return view('dashboard.space_parts.edit', compact('spacePart'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SpacePart $spacePart)
    {
        $request->validate([
            'name' => 'required|unique:space_parts,name,' . $spacePart->id,
            'price' => 'required|numeric',
        ]);

        $spacePart->update($request->all());

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('admin.space_parts.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SpacePart $spacePart)
    {
        $spacePart->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('admin.space_parts.index');
    }
}
