<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{config('app.name','RealEstateSuggester')}}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    @include('layouts.admin-navigation')

    <div class="flex">
        <aside class="w-64 bg-gray-800 text-white min-h-screen p-4">
            <ul class="space-y-4">
                <li>
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li>
                    <a href="#">Manage Users</a>
                </li>
                <li>
                    <a href="#">Approve Properties</a>
                </li>
            </ul>
        </aside>

        <main class="flex-1 p-6">
            {{ $slot }}
        </main>
    </div>

</body>
</html>
