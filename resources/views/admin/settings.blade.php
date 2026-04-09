@extends('layouts.admin-navigation')

@section('title', 'Platform Settings - Admin')

@section('content')
<div class="p-8 bg-[#fdfdfd] min-h-screen">
    
    <div class="mb-10">
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">System Configuration</h1>
        <p class="text-slate-500 font-medium mt-1">Fine-tune the platform behavior and algorithm parameters.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <!-- Sidebar Tabs -->
        <div class="lg:col-span-3 space-y-2">
            <button class="w-full text-left px-4 py-3 bg-indigo-50 text-indigo-700 rounded-xl font-bold text-sm flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Algorithm Tuning
            </button>
            <button class="w-full text-left px-4 py-3 text-slate-500 hover:bg-slate-50 rounded-xl font-bold text-sm flex items-center gap-3 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Security & Access
            </button>
            <button class="w-full text-left px-4 py-3 text-slate-500 hover:bg-slate-50 rounded-xl font-bold text-sm flex items-center gap-3 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Email Notifications
            </button>
        </div>

        <!-- Content Area -->
        <div class="lg:col-span-9 bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-10">
                
                <div class="mb-10">
                    <h2 class="text-xl font-bold text-slate-900">Recommendation Weights</h2>
                    <p class="text-sm text-slate-500 mt-1">Adjust the importance of different data points in the hybrid engine.</p>
                </div>

                <div class="space-y-10">
                    
                    <!-- Content Weight -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <label class="text-sm font-bold text-slate-700">Content Similarity (TF-IDF)</label>
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg font-black text-xs">50%</span>
                        </div>
                        <input type="range" class="w-full h-2 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-indigo-600" value="50">
                        <p class="text-[10px] text-slate-400 mt-2 italic">Controls how much weight is given to matching property details (location, price, type).</p>
                    </div>

                    <!-- Collaborative Weight -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <label class="text-sm font-bold text-slate-700">Collaborative Logic (Social)</label>
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg font-black text-xs">30%</span>
                        </div>
                        <input type="range" class="w-full h-2 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-blue-600" value="30">
                        <p class="text-[10px] text-slate-400 mt-2 italic">Controls weight given to "users who viewed this also viewed..." behavior.</p>
                    </div>

                    <!-- Popularity Weight -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <label class="text-sm font-bold text-slate-700">Global Popularity</label>
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg font-black text-xs">20%</span>
                        </div>
                        <input type="range" class="w-full h-2 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-emerald-600" value="20">
                        <p class="text-[10px] text-slate-400 mt-2 italic">Promotes trending properties regardless of specific user history.</p>
                    </div>

                </div>

                <div class="mt-12 pt-10 border-t border-slate-100">
                    <h2 class="text-xl font-bold text-slate-900 mb-6">Moderation Keywords</h2>
                    <textarea class="w-full bg-slate-50 border border-slate-100 rounded-2xl p-6 text-sm text-slate-600 focus:ring-2 focus:ring-indigo-500 outline-none" rows="4" placeholder="spam, fake, urgent, test..."></textarea>
                    <p class="text-[10px] text-slate-400 mt-3 font-medium">Properties containing these keywords in title or description will be automatically marked as 'Pending' for review.</p>
                </div>

                <div class="mt-12 flex justify-end">
                    <button class="px-8 py-3 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition shadow-lg shadow-slate-200 uppercase tracking-widest text-xs">
                        Save System State
                    </button>
                </div>

            </div>
        </div>

    </div>

</div>
@endsection
