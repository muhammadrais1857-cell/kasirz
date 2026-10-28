<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $kredensial = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($kredensial, $request->boolean('ingat'))) {
            $request->session()->regenerate();

            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->intended(route('home'));
        }

        return back()->withErrors(['email' => 'Email atau kata sandi salah.'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email',
            'no_hp' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create($data + ['role' => 'pelanggan']);
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('sukses', 'Akun berhasil dibuat. Selamat berbelanja!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
