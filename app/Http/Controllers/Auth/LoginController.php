<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){
        return view("auth.login", [
            'page_meta' => [
                'method' => 'POST',
                'url' => route('login'),
            ]
        ]);
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Ambil data login dari request
        $credentials = $request->only('email', 'password');

        // Coba login
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Redirect berdasarkan role
            return redirect()->route($this->getRedirectRoute($user));
        }

        // Jika login gagal
        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    private function getRedirectRoute($user)
    {
        if ($user->role->name === 'admin') {
            return 'admin.dashboard';
        } elseif ($user->role->name === 'laboran') {
            return 'laboran.dashboard';
        } elseif ($user->role->name === 'user') {
            return 'dashboard';
        }

        return 'home';

    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Anda telah logout.');
    }
}
