<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CaseSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CaseSessionController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();

        $query = CaseSession::query()
            ->with(['case.client:id,name']);

        if ($request->search) {
            $query->whereHas('case', function ($q) use ($request) {
                $q->where('case_number', 'like', "%{$request->search}%")
                ->orWhere('court', 'like', "%{$request->search}%")
                ->orWhere('opponent', 'like', "%{$request->search}%")
                ->orWhereHas('client', function ($q2) use ($request) {
                    $q2->where('name', 'like', "%{$request->search}%");
                });
            });
        }

        if ($request->from) {
            $query->whereDate('session_date', '>=', $request->from);
        }

        if ($request->to) {
            $query->whereDate('session_date', '<=', $request->to);
        }

        $sessions = $query
            ->orderByRaw("
                CASE
                    WHEN session_date >= ? THEN 0
                    ELSE 1
                END
            ", [$today])
            ->orderBy('session_date', 'asc')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render("Sessions/Index", [
            'sessions' => $sessions,
            'filters' => $request->only(['search', 'from', 'to'])
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_id' => 'required|exists:cases,id' ,
            'session_date' => 'required|date' ,
            'session_result' => 'nullable|string|max:500' ,
            'next_session' => 'nullable|date|gt:session_date'
        ]) ;
        $session = CaseSession::create($validated) ;
        // update next session
        $case = $session->case;
        $futureSessions = $case->sessions()->where('session_date', '>=', now())->orderBy('session_date')->first();
        $case->next_session = $futureSessions?->session_date;
        $case->save();

        return redirect()->back() ;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, CaseSession $session)
    {
        $validated = $request->validate([
            'session_date' => 'required|date' ,
            'session_result' => 'nullable|string|max:500' ,
            'next_session' => 'nullable|date'
        ]) ;
        $session->update($validated) ;

        $case = $session->case;
        $futureSessions = $case->sessions()->where('session_date', '>=', now())->orderBy('session_date')->first();
        $case->next_session = $futureSessions?->session_date;
        $case->save();

        return redirect()->back()->with('success' , 'تم تعديل بيانات الجلسة بنجاح') ;
    }


    public function destroy(CaseSession $session)
    {
        $case = $session->case;
        $session->delete() ;

        $futureSessions = $case->sessions()->where('session_date', '>=', now())->orderBy('session_date')->first();
        $case->next_session = $futureSessions?->session_date;
        $case->save();

        return redirect()->back()->with('success' , 'تم حذف الجلسة بنجاح') ;
    }
}
