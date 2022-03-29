<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //Landing Page
    public function index(Request $request)
    {
        return view('admin.index');
    }
    //Dashboard Page
    public function dashboard(Request $request)
    {
        return view('admin.dashboard');
    }
}
