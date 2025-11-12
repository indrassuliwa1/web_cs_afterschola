<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show login form.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if ($user->role === 'supervisor') {
            return redirect()->route('supervisor.dashboard');
        }

        Auth::logout();
        return redirect()->route('login')->withErrors([
            'email' => 'Role pengguna tidak dikenali.'
        ]);
    }

    /**
     * Handle logout.
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
