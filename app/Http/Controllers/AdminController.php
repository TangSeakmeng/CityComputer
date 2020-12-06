<?php

namespace App\Http\Controllers;

use App\Http\Middleware\IsActivate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

//        if(!Auth::user()->activate) {
//            Auth::logout();
//            return redirect('login')->withErrors(['Your account is inactive']);
//        }
    }

    public function __invoke(Request $request)
    {
        return view('backend.index');
    }

    public function index()
    {
        return view('backend.index');
    }
}
