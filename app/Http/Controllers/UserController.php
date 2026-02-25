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

            return redirect()->to('students');
        }

        return back()
            ->withInput($request->only('email'))
            ->with('failure', 'Invalid username or password');
    }
}
