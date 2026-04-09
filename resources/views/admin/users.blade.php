@extends('layouts.admin-navigation')

@section('title', 'Manage Users - Admin')

@section('page-title', 'Users Management')

@section('content')
    <div class="p-8">
        <livewire:admin-user-manager />
    </div>
@endsection