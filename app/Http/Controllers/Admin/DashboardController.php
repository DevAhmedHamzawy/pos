<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Client;
use App\Models\Installment;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $categories_count = Category::count();
        $products_count = Product::count();
        $clients_count = Client::count();
        $users_count = User::whereHasRole('admin')->count();

        $sales_data = Order::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total_price) as sum')
        )
        ->groupBy(
            DB::raw('YEAR(created_at)'),
            DB::raw('MONTH(created_at)')
        )
        ->get();

        $todayInstallments = Installment::with('order.client')
            ->whereDate('due_date', today())
            ->where('paid_amount', 0)
            ->count();

            $paidThisMonth = Installment::whereMonth('paid_at',now()->month)
    ->whereYear('paid_at',now()->year)
    ->count();

    $next3Days = Installment::whereBetween(
        'due_date',
        [today(),today()->addDays(3)]
    )
    ->where('paid_amount', 0)
    ->count();

    $nextInstallments = Installment::with('order.client')
    ->whereBetween('due_date',[
        today(),
        today()->addDays(3)
    ])
    ->where('paid_amount', 0)
    ->orderBy('due_date')
    ->limit(10)
    ->get();

    $paidInstallments = Installment::with('order.client')
    ->whereNotNull('paid_at')
    ->latest('paid_at')
    ->limit(10)
    ->get();

        return view('dashboard.index', compact('categories_count', 'products_count', 'clients_count', 'users_count', 'sales_data', 'todayInstallments', 'paidThisMonth', 'next3Days', 'nextInstallments', 'paidInstallments'));
    }
}
