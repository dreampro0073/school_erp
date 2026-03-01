<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller{
    public function login(){   
        return view('login');
    }

    public function postLogin(Request $request){
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = Auth::user();
            $privillage = (int) ($user->priv ?? $user->privillage ?? $user->privilege ?? 0);
            $active = (int) ($user->active ?? 1);

            if ($privillage === 1 && $active === 0) {
                return redirect()->to('/super-admin/dashboard');
            }

            if ($privillage === 2) {
                return redirect()->to('/admin/dashboard');
            }

            if ($privillage === 3) {
                return redirect()->to('/teachers/dashboard');
            }

            return redirect()->to('students');
        }

        return back()
            ->withInput($request->only('email'))
            ->with('failure', 'Invalid username or password');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
