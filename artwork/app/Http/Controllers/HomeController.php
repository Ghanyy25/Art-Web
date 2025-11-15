<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {

            if (Auth::user()->role == 'admin') {
                return view('dashboard.admin.home');

            }else if(Auth::user()->role == 'curator'){
                return view('dashboard.curator.home');

            } else {
                return view('dashboard.user.home');
            }

        } else {
            return redirect()->route('login');}

    }
}
