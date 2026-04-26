<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Cases;
use App\Models\Client;
use App\Models\CaseSession;
use App\Models\CasePayment;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_sessions' => CaseSession::count(),
            'open_cases'     => Cases::where('status', 'open')->count(),
            'total_clients'  => Client::count(),
            'pending_fees'   => Cases::with('payments')
                ->get()
                ->sum(fn ($case) =>
                    $case->case_price - $case->payments->sum('amount')
                ),
        ];

        $upcomingSessions = CaseSession::with(['case.client:id,name'])
            ->where('session_date', '>=', now()->toDateString())
            ->orderBy('session_date')
            ->limit(7)
            ->get(['id', 'case_id', 'session_date', 'session_result', 'next_session']);

        $recentClients = Client::latest()
            ->limit(5)
            ->get(['id', 'name', 'phone', 'type']);

        $recentCases = Cases::with('client:id,name')
            ->latest()
            ->limit(5)
            ->get(['id', 'client_id', 'case_number', 'court', 'status', 'created_at']);

        $recentPayments = CasePayment::with(['case.client:id,name'])
            ->latest()
            ->limit(5)
            ->get(['id', 'case_id', 'amount', 'payment_date', 'notes']);

        return Inertia::render('Dashboard', [
            'stats'            => $stats,
            'upcomingSessions' => $upcomingSessions,
            'recentClients'    => $recentClients,
            'recentCases'      => $recentCases,
            'recentPayments'   => $recentPayments,
            'officeName'       => auth()->user()->name ?? 'محمود',
        ]);
    }
}