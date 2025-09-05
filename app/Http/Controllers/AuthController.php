<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $credentials['username'] = strtolower($credentials['username']);
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            Alert::success('Login Berhasil', 'Selamat datang Kembali!')->autoClose(2000);
            return redirect()->intended('/hospitals');
        }

        throw ValidationException::withMessages([
            'username' => 'Username atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Alert::success('Logout', 'Anda berhasil logout!')->autoClose(2000);

        return redirect()->route('login');
    }
}
