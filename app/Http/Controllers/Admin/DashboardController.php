<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ShuttleTransaction;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    public function index()
    {
        $userCount = User::count();
        $orderCount = Order::count();
        $shuttleCount = ShuttleTransaction::count();

        // Contoh data grafik (bisa dibuat lebih dinamis nanti)
        $chartLabels = ['Lapangan', 'Shuttlecock'];
        $chartData = [$orderCount, $shuttleCount];

        return view('admin.dashboard.index', [
            'activePage' => 'home',
            'userCount' => $userCount,
            'orderCount' => $orderCount,
            'shuttleCount' => $shuttleCount,
            'chartLabels' => json_encode($chartLabels),
            'chartData' => json_encode($chartData),
        ]);
    }
}
