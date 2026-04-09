@extends('layouts.admin-navigation')

@section('title', 'Control Center - Admin')

@section('content')
<div class="p-8 bg-[#f8fafc] min-h-screen">
    
    <!-- Top Greeting & Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-[#0f172a] tracking-tight">System Intelligence</h1>
            <p class="text-slate-500 font-medium mt-1">Real-time overview of your real estate ecosystem.</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right hidden md:block">
                <div id="real-time-clock" class="text-lg font-black text-slate-900 leading-none">00:00:00</div>
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ now()->format('l, M j') }}</div>
            </div>
            <span class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-bold text-slate-700 shadow-sm flex items-center gap-2">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                Engine Healthy
            </span>
        </div>

        <script>
            function updateClock() {
                const now = new Date();
                document.getElementById('real-time-clock').textContent = now.toLocaleTimeString('en-US', { 
                    hour12: false, 
                    hour: '2-digit', 
                    minute: '2-digit', 
                    second: '2-digit' 
                });
            }
            setInterval(updateClock, 1000);
            updateClock();
        </script>
    </div>

    <!-- Stats Grid: Premium Glass Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        <!-- Total Participants -->
        <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-6 hover:translate-y-[-4px] transition-transform duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Participants</span>
            </div>
            <div class="text-4xl font-black text-slate-900 leading-none mb-1">{{ $stats['total_users'] }}</div>
            <div class="flex items-center gap-2 text-xs font-bold">
                <span class="text-blue-600">{{ $stats['total_sellers'] }} Sellers</span>
                <span class="text-slate-300">/</span>
                <span class="text-indigo-600">{{ $stats['total_buyers'] }} Buyers</span>
            </div>
        </div>

        <!-- Inventory Volume -->
        <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-6 hover:translate-y-[-4px] transition-transform duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Inventory</span>
            </div>
            <div class="text-4xl font-black text-slate-900 leading-none mb-1">{{ $stats['total_properties'] }}</div>
            <div class="flex items-center gap-2 text-xs font-bold">
                <span class="text-emerald-600">{{ $stats['approved_properties'] }} Verified</span>
                <span class="text-slate-300">/</span>
                <span class="text-amber-500">{{ $stats['pending_properties'] }} New</span>
            </div>
        </div>

        <!-- System Intelligence -->
        <div class="bg-[#0f172a] rounded-2xl shadow-[0_20px_50px_rgba(15,23,42,0.2)] border border-slate-800 p-6 hover:translate-y-[-4px] transition-transform duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-slate-800 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Brain Cache</span>
            </div>
            <div class="text-4xl font-black text-white leading-none mb-1">{{ number_format($stats['engine_cached_recs']) }}</div>
            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Computed Matches</div>
        </div>

        <!-- Conversion Volume -->
        <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl shadow-[0_20px_50px_rgba(79,70,229,0.3)] p-6 text-white hover:translate-y-[-4px] transition-transform duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-white/60 uppercase tracking-widest">Enquiries</span>
            </div>
            <div class="text-4xl font-black leading-none mb-1">{{ $stats['total_enquiries'] }}</div>
            <div class="text-[10px] text-white/80 font-bold uppercase tracking-wider">Total Lead Volume</div>
        </div>
    </div>

    <!-- Second Row: Deep Analysis -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-10">
        
        <!-- Pending Approvals: The Workflow -->
        <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Verification Queue</h3>
                    <p class="text-xs text-slate-500 mt-1">High-priority listings awaiting your green light.</p>
                </div>
                <a href="{{ route('admin.properties') }}" class="text-xs font-bold text-indigo-600 px-4 py-2 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors uppercase tracking-wider">View All</a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($pendingProperties as $property)
                <div class="px-8 py-5 flex items-center justify-between group transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-12 bg-slate-100 rounded-lg overflow-hidden border border-slate-100">
                             <img src="{{ $property->image_url }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all">
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900">{{ $property->title }}</h4>
                            <p class="text-xs text-slate-400">{{ $property->location }} · <span class="text-indigo-600 font-bold">Rs {{ number_format($property->price) }}</span></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <form action="{{ route('admin.properties.approve', $property) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="px-4 py-1.5 bg-slate-900 text-white text-[10px] font-bold rounded-lg uppercase tracking-wider hover:bg-slate-800 transition shadow-sm">Approve</button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center text-slate-400 font-serif italic">Operational silence. No pending verifications.</div>
                @endforelse
            </div>
        </div>

        <!-- Heatmap: Market Hotspots -->
        <div class="lg:col-span-4 flex flex-col gap-6">
            
            <!-- Market Hotspots -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zM7 9c0-2.76 2.24-5 5-5s5 2.24 5 5c0 2.88-2.88 7.19-5 9.39C9.92 16.21 7 11.85 7 9z"/><circle cx="12" cy="9" r="2.5"/></svg>
                    Market Activity
                </h3>
                <div class="space-y-4">
                    @foreach($topLocations as $loc)
                    <div>
                        <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                            <span class="text-slate-600">{{ $loc->location }}</span>
                            <span class="text-indigo-600">{{ $loc->count }} Listings</span>
                        </div>
                        <div class="w-full bg-slate-50 rounded-full h-1.5 overflow-hidden">
                            @php $maxLoc = $topLocations->first()->count; $locWidth = ($loc->count / $maxLoc) * 100; @endphp
                            <div class="bg-indigo-500 h-1.5 rounded-full" style="width:{{ $locWidth }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- AI Engine Profile -->
            <div class="bg-indigo-900 rounded-2xl p-8 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <h4 class="text-sm font-bold text-indigo-300 uppercase tracking-widest mb-2">Lead Intelligence</h4>
                    <div class="text-4xl font-black mb-1">{{ round(($stats['high_quality_leads'] / max(1, $stats['total_enquiries'])) * 100) }}%</div>
                    <div class="text-[10px] text-white/60 font-medium">Conversion Accuracy Rate</div>
                    <p class="text-xs mt-4 text-indigo-100/70 leading-relaxed font-medium italic">
                        "Algorithm is successfully bridging the gap between buyer preferences and active listings."
                    </p>
                </div>
                <svg class="absolute -bottom-8 -right-8 w-40 h-40 text-white/5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>

        </div>
    </div>
</div>
@endsection