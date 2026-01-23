<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellrDashboardController extends Controller
{
     public function index(){
        return view('seller.dashboard');
    }
}
