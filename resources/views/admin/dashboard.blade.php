@extends('layouts.admin-navigation')

@section('title', 'Dashboard - Admin')

@section('page-title', 'Dashboard')

@section('content')
    <div class="p-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Total Users</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_users'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-green-600 font-medium">{{ $stats['total_sellers'] }}</span>
                    <span class="text-gray-500 ml-1">Sellers</span>
                    <span class="mx-2 text-gray-300">|</span>
                    <span class="text-blue-600 font-medium">{{ $stats['total_buyers'] }}</span>
                    <span class="text-gray-500 ml-1">Buyers</span>
                </div>
            </div>

            <!-- Total Properties -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Total Properties</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_properties'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-green-600 font-medium">{{ $stats['approved_properties'] }}</span>
                    <span class="text-gray-500 ml-1">Approved</span>
                    <span class="mx-2 text-gray-300">|</span>
                    <span class="text-yellow-600 font-medium">{{ $stats['pending_properties'] }}</span>
                    <span class="text-gray-500 ml-1">Pending</span>
                </div>
            </div>

            <!-- Pending Review -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Pending Review</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['pending_properties'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <a href="#pending" class="mt-4 inline-flex items-center text-sm text-blue-600 hover:text-blue-700">
                    Review pending properties
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <!-- Rejected -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Rejected</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['rejected_properties'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-sm text-gray-500">Properties not approved for listing</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Pending Properties -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200" id="pending">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Pending Properties</h3>
                        <a href="{{ route('admin.properties') }}" class="text-sm text-blue-600 hover:text-blue-700">View
                            All</a>
                    </div>
                </div>
                <div class="p-6">
                    @forelse($pendingProperties as $property)
                        <div
                            class="flex items-center justify-between py-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden">
                                    @if($property->image)
                                        <img src="{{ asset('images/' . $property->image) }}" alt="{{ $property->title }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $property->title }}</h4>
                                    <p class="text-sm text-gray-500">{{ $property->location }}</p>
                                    <p class="text-sm font-medium text-blue-600">Rs {{ number_format($property->price) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <form action="{{ route('admin.properties.approve', $property) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="px-3 py-1 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition">
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.properties.reject', $property) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="px-3 py-1 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-500">No pending properties</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Users -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Users</h3>
                        <a href="{{ route('admin.users') }}" class="text-sm text-blue-600 hover:text-blue-700">View All</a>
                    </div>
                </div>
                <div class="p-6">
                    @forelse($recentUsers as $user)
                        <div
                            class="flex items-center justify-between py-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-600">
    {{ strtoupper(substr($user->name, 0, 2)) }}
</span>

                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $user->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 text-xs font-medium rounded-full
                                                            @if($user->role === 'admin') bg-purple-100 text-purple-700
                                                            @elseif($user->role === 'owner') bg-blue-100 text-blue-700
                                                            @else bg-gray-100 text-gray-700 @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-500">No users yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection