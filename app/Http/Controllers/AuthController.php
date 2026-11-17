<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $fixedEmail = 'owner@example.com';
    private $fixedPassword = 'rahasia123';

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (
            $request->email === $this->fixedEmail &&
            $request->password === $this->fixedPassword
        ) {
            session(['user' => [
                'email' => $request->email
            ]]);

            return redirect()->route('products.index');
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout()
    {
        session()->forget('user');
        return redirect()->route('login');
    }
}
