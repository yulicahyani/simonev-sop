<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(){
        // if(Auth::check()){
        //     return redirect()->route('backend.home');
        // } else {
        //     return view('auth.login');
        // }

        return view('auth.login');
    }
}
