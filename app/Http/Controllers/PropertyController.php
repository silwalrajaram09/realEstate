<?php

namespace App\Http\Controllers;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function buy(Request $request)
    {
        $properties = Property::where('purpose', 'buy')
            ->when($request->type, fn($q) =>
                $q->where('type', $request->type))
            ->get();

        return view('properties.list', compact('properties'));
    }

    public function sell(Request $request)
    {
        $properties = Property::where('purpose', 'sell')
            ->when($request->type, fn($q) =>
                $q->where('type', $request->type))
            ->get();

        return view('properties.list', compact('properties'));
    }

    public function index(Request $request)
    {
        $properties = Property::when($request->category, fn($q) =>
            $q->where('category', $request->category))
            ->get();

        return view('properties.list', compact('properties'));
    }
}
