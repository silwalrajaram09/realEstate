<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Property;

class HomeController extends Controller
{
    public function index()
    {
        //check if user is authenticated and redirect to dashboard, else show welcome page
        if (auth()->check()) {
            return redirect()->route('dashboard');
        } else {
            //$properties = Property::latest()->take(6)->get();
            //return view("welcome")->with('properties', $properties);
            return view('welcome');
        }
    }
}
