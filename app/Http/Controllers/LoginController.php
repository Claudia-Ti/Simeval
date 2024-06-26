<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth,DB,Hash;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
class LoginController extends Controller
{
    public function login_view(){
        if(Auth::user()){
            return Redirect::to(url()->previous());
        }
        return view('pages.login');
    }

    public function loginValidation(Request $request){
        // dd($request);
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials)){
            return redirect()->intended('/')
                            ->withSuccess('Login Successful');

        }
        return redirect("login")->with('error', 'Login Failed');
    }

    public function logout(Request $request)
    {
        Auth::logout();
 
        request()->session()->invalidate();
 
        request()->session()->regenerateToken();
 
        return redirect('/login');
    }
}
