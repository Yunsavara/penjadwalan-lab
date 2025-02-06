<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        // Cek password secara manual
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password salah.']);
        }

        // Jika email & password benar, lakukan login
        Auth::login($user);

        return redirect()->route($this->getRedirectRoute($user));
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
