@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">

    {{-- Page Header --}}
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Dashboard
            </h1>
            <p class="text-gray-600 mt-1">
                Welcome back, {{ auth()->user()->name }} 👋
            </p>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-6 py-10">

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            
            {{-- Card --}}
            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-gray-500 text-sm">Total Properties</h3>
                <p class="text-3xl font-bold text-blue-600 mt-2">
                    {{ $totalProperties ?? 0 }}
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-gray-500 text-sm">Properties Bought</h3>
                <p class="text-3xl font-bold text-green-600 mt-2">
                    {{ $boughtProperties ?? 0 }}
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-gray-500 text-sm">Properties Sold</h3>
                <p class="text-3xl font-bold text-red-600 mt-2">
                    {{ $soldProperties ?? 0 }}
                </p>
            </div>
        </div>

        {{-- Recent Properties --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                Recent Properties
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-3 px-4 text-gray-600">Title</th>
                            <th class="py-3 px-4 text-gray-600">Type</th>
                            <th class="py-3 px-4 text-gray-600">Price</th>
                            <th class="py-3 px-4 text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($properties ?? [] as $property)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    {{ $property->title }}
                                </td>
                                <td class="py-3 px-4 capitalize">
                                    {{ $property->type }}
                                </td>
                                <td class="py-3 px-4">
                                    Rs. {{ number_format($property->price) }}
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-3 py-1 rounded-full text-sm
                                        {{ $property->status === 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ ucfirst($property->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-6 text-gray-500">
                                    No properties found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
