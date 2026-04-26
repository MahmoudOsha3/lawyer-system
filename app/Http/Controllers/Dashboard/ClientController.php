<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ClientStoreRequest;
use App\Http\Requests\Client\ClientUpdateRequest;
use App\Models\CasePayment;
use App\Models\Client ;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = Client::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('national_id', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15);

        return Inertia::render('Clients/Index', [
            'clients' => $clients,
            'filters' => $request->only(['search'])
        ]);
    }


    public function store(ClientStoreRequest $request)
    {
        Client::create($request->validated());
        return redirect()->route('clients.index')
            ->with('success', 'تم إضافة الموكل بنجاح');
    }


    public function show(Client $client)
    {
        // Sum for case_price to total cases
        // Sum for cases payment to this client
        // detective for Sum for case_price and Sum for cases payment

        $totalCasesPrice = $client->cases->sum('case_price') ;
        $case_ids = $client->cases->pluck('id') ;
        $totalCasesPaid = CasePayment::whereHas('case', function ($q) use ($client) {
            $q->where('client_id', $client->id);
        })->sum('amount');

        $totalCasesNotPaid = $totalCasesPrice - $totalCasesPaid ;

        $stats = [
            'total_cases' => $client->cases->count() ,
            'totalCasesPrice' => (float) $totalCasesPrice ,
            'totalCasesPaid' => (float) $totalCasesPaid ,
            'totalCasesNotPaid' => (float) $totalCasesNotPaid ,] ;


        return Inertia::render("Clients/Show", [
            'client' => $client,
            'stats' => $stats ,
            'flash'  => [
                'success' => session('success'),
                'error' => session('error'),
            ]
        ]);
    }




    public function update(ClientUpdateRequest $request, Client $client)
    {
        $client->update($request->validated()) ;
        return redirect()->route('clients.index')
            ->with('success', 'تم تعديل بيانات الموكل بنجاح');
    }

    public function destroy(Client $client)
    {
        $client->delete() ;
        return redirect()->route('clients.index') ;
    }
}
