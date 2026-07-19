<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Client;
use App\Models\Installment;
use App\Models\Maintenance;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {


$todaySales = Order::whereDate('created_at', today())
        ->sum('total_price');

    $todayOrders = Order::whereDate('created_at', today())
        ->count();


        $almostProducts = Product::whereColumn('stock', '<=', 'stock_limit')->count();

        $almostProductsTable = Product::where('stock', '>', 0)
    ->whereColumn('stock', '<=', 'stock_limit')
    ->orderBy('stock')
    ->take(10)
    ->get();

        $maintenances = Maintenance::count();


        $dueInstallments = Installment::where(function ($query) {
                        $query->whereDate('due_date', '<', today())->whereColumn('paid_amount', '<', 'amount');
                    })
                    ->orWhere(function ($query) {
                        $query->where('paid_amount', '>' , 0)
                            ->whereColumn('paid_amount', '<', 'amount');
                    })
                        ->count();

             $sales = Order::selectRaw('DATE(created_at) as date, SUM(total_price) as total')
                ->where('created_at', '>=', now()->subDays(29)->startOfDay())
                ->groupBy('date')
                ->pluck('total', 'date');

            $salesLabels = [];
            $salesData = [];

            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);

                $salesLabels[] = $date->format('d/m');
                $salesData[] = $sales[$date->format('Y-m-d')] ?? 0;
            }

            $salesRevenue = Order::sum('total_price');
            $maintenanceRevenue = Maintenance::sum('price');
            $spacePartRevenue = DB::table('maintenance_space_part')->sum('price');

            $revenues = [
    'sales' => $salesRevenue,
    'maintenance' => $maintenanceRevenue,
    'parts' => $spacePartRevenue,
];

$nextInstallments = Installment::with('order.client')
    ->whereBetween('due_date',[
        today(),
        today()->addDays(3)
    ])
    ->where('paid_amount', 0)
    ->orderBy('due_date')
    ->limit(10)
    ->get();



$topProducts = DB::table('product_order')
    ->join('products', 'products.id', '=', 'product_order.product_id')
    ->join('product_translations', function ($join) {
        $join->on('products.id', '=', 'product_translations.product_id')
             ->where('product_translations.locale', app()->getLocale());
    })
    ->select(
        'products.id',
        'product_translations.name',
        DB::raw('SUM(product_order.quantity) as total_quantity')
    )
    ->groupBy('products.id', 'product_translations.name')
    ->orderByDesc('total_quantity')
    ->take(10)
    ->get();

    $latestOrders = Order::with('client')
    ->latest()
    ->take(10)
    ->get();


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

    $maintenanceStatus = Maintenance::selectRaw('status, COUNT(*) as total')
        ->groupBy('status')
        ->pluck('total', 'status');

    $pending = $maintenanceStatus['pending'] ?? 0;
    $inProgress = $maintenanceStatus['in_progress'] ?? 0;
    $completed = $maintenanceStatus['completed'] ?? 0;
    $delivered = $maintenanceStatus['delivered'] ?? 0;

    $maintenances_total = $pending + $inProgress + $completed + $delivered;

    $pendingWidth = ($pending / $maintenances_total) * 100;
$inProgressWidth = ($inProgress / $maintenances_total) * 100;
$completedWidth = ($completed / $maintenances_total) * 100;
$deliveredWidth = ($delivered / $maintenances_total) * 100;

$newClientsMonth = Client::whereMonth('created_at', now()->month)
    ->whereYear('created_at', now()->year)
    ->count();

    $topClient = Order::select(
        'client_id',
        DB::raw('SUM(total_price) as total_sales')
    )
    ->with('client')
    ->groupBy('client_id')
    ->orderByDesc('total_sales')
    ->first();

    $stockValue = Product::sum('stock');
    $stockValueDecreasing = Product::whereColumn('stock' , '<=' , 'stock_limit')->count();
    $stockExpired = Product::whereStock(0)->count();


    // ========================
// اليوم
// ========================

$todaySales = Order::whereDate('created_at', today())
    ->sum('total_price');

$todayMaintenance = Maintenance::whereDate('created_at', today())
    ->sum('price');

$todayParts = DB::table('maintenance_space_part')->whereDate('created_at', today())
    ->sum('price');

$todayProfit = $todaySales + $todayMaintenance + $todayParts;


// ========================
// الأسبوع
// ========================

$weekSales = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
    ->sum('total_price');

$weekMaintenance = Maintenance::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
    ->sum('price');

$weekParts = DB::table('maintenance_space_part')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
    ->sum('price');

$weekProfit = $weekSales + $weekMaintenance + $weekParts;


// ========================
// الشهر
// ========================

$monthSales = Order::whereMonth('created_at', now()->month)
    ->whereYear('created_at', now()->year)
    ->sum('total_price');

$monthMaintenance = Maintenance::whereMonth('created_at', now()->month)
    ->whereYear('created_at', now()->year)
    ->sum('price');

$monthParts = DB::table('maintenance_space_part')->whereMonth('created_at', now()->month)
    ->whereYear('created_at', now()->year)
    ->sum('price');

$monthProfit = $monthSales + $monthMaintenance + $monthParts;


// ========================
// السنة
// ========================

$yearSales = Order::
    whereYear('created_at', now()->year)
    ->sum('total_price');

$yearMaintenance = Maintenance::whereYear('created_at', now()->year)
    ->sum('price');

$yearParts = DB::table('maintenance_space_part')->whereYear('created_at', now()->year)
    ->sum('price');

$yearProfit = $yearSales + $yearMaintenance + $yearParts;

        return view('dashboard.index', compact(
            'todaySales', 'todayOrders', 'almostProducts', 'maintenances', 'dueInstallments',  'pending',
        'inProgress',
        'completed',
        'stockValue', 'stockValueDecreasing', 'stockExpired',
        'deliveredWidth', 'pendingWidth', 'topClient', 'inProgressWidth', 'newClientsMonth', 'completedWidth',
        'delivered', 'categories_count', 'topProducts', 'latestOrders' , 'yearProfit' , 'monthProfit' , 'weekProfit' , 'todayProfit' , 'products_count', 'clients_count', 'users_count', 'salesLabels' , 'salesData', 'revenues', 'almostProductsTable' , 'sales_data', 'todayInstallments', 'paidThisMonth', 'next3Days', 'nextInstallments', 'paidInstallments'));
    }
}
