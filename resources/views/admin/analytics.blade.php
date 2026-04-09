@extends('layouts.admin-navigation')

@section('title', 'Market Intelligence - Admin')

@section('content')
<div class="p-8 bg-[#fdfdfd] min-h-screen">
    
    <!-- Hero Header -->
    <div class="mb-10 flex items-end justify-between">
        <div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Market Intelligence</h1>
            <p class="text-slate-500 font-medium mt-2">Deep dive into platform growth and user behavior clusters.</p>
        </div>
        <div class="text-right">
            <div id="analytics-clock" class="text-xl font-black text-slate-900 leading-none mb-1">00:00:00</div>
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ now()->format('l, M d') }}</div>
        </div>

        <script>
            function updateAnalyticsClock() {
                const now = new Date();
                const clock = document.getElementById('analytics-clock');
                if(clock) {
                    clock.textContent = now.toLocaleTimeString('en-US', { 
                        hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' 
                    });
                }
            }
            setInterval(updateAnalyticsClock, 1000);
            updateAnalyticsClock();
        </script>
    </div>

    <!-- Core KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <div class="bg-indigo-600 rounded-3xl p-8 text-white shadow-xl shadow-indigo-100">
            <p class="text-indigo-100 text-xs font-bold uppercase tracking-widest mb-2">Demand Funnel</p>
            <div class="text-5xl font-black mb-4">{{ number_format($conversionRate, 1) }}%</div>
            <div class="h-2 w-full bg-indigo-400/30 rounded-full overflow-hidden">
                <div class="bg-white h-full" style="width: {{ $conversionRate }}%"></div>
            </div>
            <p class="text-indigo-100 text-[10px] mt-4 font-bold uppercase">Conversion: Views to Enquiries</p>
        </div>

        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-2">Lead Traffic</p>
            <div class="text-5xl font-black text-slate-900 mb-4">{{ $totalEnquiries }}</div>
            <div class="flex items-center gap-1">
                 @foreach(range(1, 10) as $i)
                    <div class="w-2 h-{{ rand(4, 10) }} bg-slate-100 rounded-full"></div>
                 @endforeach
            </div>
            <p class="text-slate-400 text-[10px] mt-4 font-bold uppercase">Consolidated Interest volume</p>
        </div>

        <div class="bg-[#0f172a] rounded-3xl p-8 text-white shadow-xl">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-2">Match Integrity</p>
            @php $avgMatch = $matchTrends->avg('avg_score') ?? 0; @endphp
            <div class="text-5xl font-black mb-4 text-emerald-400">{{ round($avgMatch * 100) }}%</div>
            <p class="text-slate-500 text-[10px] font-bold uppercase">Avg Suggestion Quality</p>
            <div class="mt-4 flex gap-1">
                <div class="px-2 py-0.5 bg-emerald-500/10 text-emerald-400 rounded text-[10px] font-bold">OPTIMIZED</div>
            </div>
        </div>
    </div>

    <!-- Charts & Trends -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        
        <!-- Registration Trend -->
        <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 mb-8 flex items-center justify-between">
                User Acquisition
                <span class="text-xs font-bold text-slate-400">Past 30 Days</span>
            </h3>
            <div class="h-48 flex items-end gap-2 px-2">
                @foreach($userGrowth as $growth)
                    <div class="flex-1 bg-indigo-50 hover:bg-indigo-500 transition-colors rounded-t-md group relative" 
                         style="height: {{ max(10, ($growth->count / max(1, $userGrowth->max('count'))) * 100) }}%">
                        <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                            {{ $growth->count }} Users
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between mt-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                <span>{{ $userGrowth->first()->date ?? 'Start' }}</span>
                <span>{{ $userGrowth->last()->date ?? 'End' }}</span>
            </div>
        </div>

        <!-- Demand Segmentation -->
        <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 mb-8">Interest Segmentation</h3>
            <div class="space-y-6">
                @foreach($demandByType as $demand)
                <div>
                    <div class="flex items-center justify-between text-xs font-bold mb-2">
                        <span class="text-slate-600 uppercase tracking-wider">{{ $demand->type }}</span>
                        <span class="text-indigo-600">{{ $demand->count }} Enquiries</span>
                    </div>
                    <div class="w-full bg-slate-50 rounded-full h-3 overflow-hidden">
                        @php $maxDemand = $demandByType->first()->count; $demandWidth = ($demand->count / $maxDemand) * 100; @endphp
                        <div class="bg-indigo-600 h-full rounded-full" style="width:{{ $demandWidth }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- Top Performers -->
    <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
        <h3 class="text-xl font-bold text-slate-900 mb-8">Top Growth Contributors (Sellers)</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            @foreach($topSellers as $seller)
            <div class="p-6 bg-slate-50 rounded-2xl text-center border border-slate-100 hover:border-indigo-200 transition-colors">
                <div class="w-12 h-12 bg-white rounded-full mx-auto mb-4 flex items-center justify-center text-indigo-600 font-black shadow-sm border border-slate-100">
                    {{ strtoupper(substr($seller->name, 0, 1)) }}
                </div>
                <h4 class="text-sm font-bold text-slate-900 truncate">{{ $seller->name }}</h4>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">{{ $seller->properties_count }} Active Listings</p>
                <div class="mt-4 px-3 py-1 bg-white inline-block rounded-full text-[10px] font-bold text-indigo-600 border border-slate-100 uppercase">Power Seller</div>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
