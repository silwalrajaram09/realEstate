<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BuyerDashboardController extends Controller
{
    public function index(){
        return view('buyer.dashboard');
    }
}
