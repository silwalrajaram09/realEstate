@extends('layouts.admin-navigation')

@section('title', 'Manage Properties - Admin')

@section('page-title', 'Properties Management')

@section('content')
    <div class="p-8">
        <livewire:admin-property-manager />
    </div>
@endsection