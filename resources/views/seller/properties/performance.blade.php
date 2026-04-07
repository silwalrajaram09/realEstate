<x-app-layout>
<div class="max-w-7xl mx-auto px-6 py-10">
    <h1 class="text-2xl font-semibold text-[#1a1a2e] mb-6">Listing Performance</h1>
    <div class="bg-white border border-[#ede8df] rounded-md overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-[#faf7f2]">
                <tr>
                    <th class="text-left p-3">Property</th>
                    <th class="text-left p-3">Views</th>
                    <th class="text-left p-3">Inquiries</th>
                    <th class="text-left p-3">Saves</th>
                </tr>
            </thead>
            <tbody>
            @foreach($properties as $property)
                <tr class="border-t border-[#f0ece4]">
                    <td class="p-3">{{ $property->title }}</td>
                    <td class="p-3">{{ $property->views_count + $property->views_events_count }}</td>
                    <td class="p-3">{{ $property->enquiries_count }}</td>
                    <td class="p-3">{{ $property->favorited_by_count }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="p-3">{{ $properties->links() }}</div>
    </div>
</div>
</x-app-layout>
