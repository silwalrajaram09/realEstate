@extends('layouts.admin-navigation')

@section('title', 'Manage Properties - Admin')

@section('page-title', 'Properties Management')

@section('content')
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">All Properties</h2>
            <form method="GET" action="{{ route('admin.properties') }}" class="flex items-center gap-2">
                <label for="status" class="text-sm text-gray-600">Status</label>
                <select id="status" name="status" onchange="this.form.submit()" class="text-sm border border-gray-300 rounded-lg px-2 py-1">
                    <option value="">All</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                    <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                </select>
                <div class="text-sm text-gray-500 ml-2">
                    Total: {{ $properties->total() }}
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Property</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Seller</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Purpose</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Listed</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($properties as $property)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-12 w-12 rounded-lg bg-gray-200 overflow-hidden shrink-0">
                                            @if($property->image)
                                                <img src="{{ asset('images/' . $property->image) }}"
                                                    class="h-full w-full object-cover">
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $property->title }}</div>
                                            <div class="text-sm text-gray-500">{{ $property->location }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $property->seller->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $property->seller->email ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Rs {{ number_format($property->price) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                @if($property->purpose === 'buy') bg-green-100 text-green-800
                                                @else bg-blue-100 text-blue-800 @endif">
                                        {{ ucfirst($property->purpose) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                @if($property->status === 'approved') bg-green-100 text-green-800
                                                @elseif($property->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($property->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $property->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="{{ route('admin.properties.featured-toggle', $property) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-amber-600 mr-3">{{ $property->is_featured ? 'Unfeature' : 'Feature' }}</button>
                                    </form>
                                    @if($property->status === 'pending')
                                        <form action="{{ route('admin.properties.approve', $property) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-900 mr-3">
                                                Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.properties.reject', $property) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="reason" value="Rejected by admin review. Please review your listing details and resubmit.">
                                            <button type="submit" class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Reject this property?')">
                                                Reject
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.properties.request-changes', $property) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="reason" value="Please update listing details and resubmit for approval.">
                                            <button type="submit" class="text-blue-600 ml-2">Request Changes</button>
                                        </form>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    No properties found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($properties->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $properties->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection