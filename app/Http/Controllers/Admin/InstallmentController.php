<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Installment;
use App\Models\Order;
use Illuminate\Http\Request;

class InstallmentController extends Controller
{
    public function __construct()
    {
        //create read update delete
        $this->middleware(['permission:installments_read'])->only('index');
        $this->middleware(['permission:installments_update'])->only('edit', 'update');
    }
    public function index(Client $client, Order $order)
    {
        $installments = $order->installments()->paginate(10);
        return view('dashboard.clients.installments.index', compact('client', 'installments'));
    }

    public function edit(Client $client, Order $order, Installment $installment)
    {
        return view('dashboard.clients.installments.edit', compact('installment', 'client', 'order'));
    }

    public function update(Request $request, Client $client, Order $order, Installment $installment)
    {

        $request->validate([
            'paid_amount' => 'required|numeric|max:' . $installment->amount,
        ]);

        $request->merge([
            'paid_at' => now()
        ]);

        if ($request->paid_amount == $installment->amount) {
            $request->merge([
                'status' => 'paid'
            ]);
        }else{
            $request->merge([
                'status' => 'unpaid'
            ]);
        }

        $installment->update($request->all());

        activity()->log('قام '.auth()->user()->full_name.' بتحصيل قسط'.$installment->installment_no.' للطلب '.$order->id.' للعميل '.$client->name);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('admin.installments.index', [$client, $order]);
    }
}
