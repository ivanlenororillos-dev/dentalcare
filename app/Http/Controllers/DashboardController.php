<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Dentist;
use App\Models\ToothHistory;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_clients' => Client::count(),
            'total_dentists' => Dentist::count(),
            'procedures_today' => ToothHistory::whereDate('date_of_procedure', Carbon::today())->count(),
            'procedures_this_month' => ToothHistory::whereMonth('date_of_procedure', Carbon::now()->month)
                ->whereYear('date_of_procedure', Carbon::now()->year)
                ->count(),
        ];

        $recentActivity = ToothHistory::with(['client', 'dentist'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recentClients = Client::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'recentActivity', 'recentClients'));
    }
}
