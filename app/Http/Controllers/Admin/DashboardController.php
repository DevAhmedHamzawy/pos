<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Client;
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

        return view('dashboard.index', compact('categories_count', 'products_count', 'clients_count', 'users_count', 'sales_data'));
    }
}
