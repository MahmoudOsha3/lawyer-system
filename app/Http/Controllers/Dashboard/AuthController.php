<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function login()
    {
        return Inertia::render("Auth/Login") ;
    }
    public function loginCheck(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|max:20'
        ]);


        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return to_route('dashboard');
        }

        return back()->withErrors([
            'email' => 'بيانات الاعتماد المقدمة لا تتطابق مع سجلاتنا.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
