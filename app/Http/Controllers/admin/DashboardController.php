<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class DashboardController extends Controller
{
    public function index(){
        $orders = Order::limit(10)->orderBy('created_at', 'desc')->get();
        $dashboardData = Cache::remember('dashboardData', 60, function () {
            return [
                "todayOrder" => Order::whereDate('created_at', today())->count(),
                "total_products" => Product::all()->count(),
                "total_users" => User::all()->count(),
                "total_cat" => Categorie::all()->count(),

                //order overView
                "total_orders" => Order::all()->count(),
                "total_complete_order" => Order::where('status', 'delivered')->count(),
                "total_cancel_order" => Order::where('status', 'cancelled')->count(),
                "total_sell" => Order::where('status', 'delivered')->sum('total'),
            ];
        });

        return view('admin.dashboard', compact('dashboardData','orders'));
    }
}
