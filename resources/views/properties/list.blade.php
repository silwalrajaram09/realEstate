@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-6">Property Results</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @forelse($properties as $property)
        <div class="bg-white p-4 rounded shadow">
            <h3 class="font-semibold">{{ $property->title }}</h3>
            <p>Type: {{ ucfirst($property->type) }}</p>
            <p>Price: Rs {{ number_format($property->price) }}</p>
        </div>
    @empty
        <p>No properties found.</p>
    @endforelse
</div>
@endsection

