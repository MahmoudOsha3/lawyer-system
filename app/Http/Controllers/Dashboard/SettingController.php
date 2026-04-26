<?php

namespace App\Http\Controllers\Dashboard ;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function index()
    {
        return Inertia::render('Profile/Index', [
            'settings' => Setting::first(),
            'user'     => auth()->user()->only(['name', 'email', 'phone']),
        ]);
    }

    public function saveOffice(Request $request)
    {
        $validated = $request->validate([
            'office_name'   => 'required|string|max:255',
            'office_phone'  => 'nullable|string|max:20',
            'mobile'        => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:500',
            'working_hours' => 'nullable|string|max:100',
            'tax_number'    => 'nullable|string|max:50',
            'office_email'  => 'nullable|email|max:255',
            'website'       => 'nullable|url|max:255',
            'bar_number'    => 'nullable|string|max:50',
            'bar_date'      => 'nullable|date',
            'bar_degree'    => 'nullable|string|max:100',
            'bar_status'    => 'nullable|string|max:50',
        ]);

        Setting::updateOrCreate(
            [],
            $validated
        );

        return back()->with('success', 'تم حفظ بيانات المكتب بنجاح');
    }

    public function savePersonal(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return back()->with('success', 'تم تحديث البيانات الشخصية بنجاح');
    }


    public function savePassword(Request $request)
    {
        $request->validate([
            'current_password'=> 'required',
            'password'        => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors([
                'current_password' => 'كلمة المرور الحالية غير صحيحة',
            ]);
        }

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'تم تغيير كلمة المرور بنجاح');
    }

}
