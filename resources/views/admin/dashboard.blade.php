@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
                    <div class="flex space-x-4">
                        <a href="{{ route('admin.analytics') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                            View Analytics
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

                <!-- System Overview -->
                <div class="grid md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-blue-800 mb-2">Total Bins</h3>
                        <p class="text-3xl font-bold text-blue-600">{{ $binSummary['total_bins'] ?? 5 }}</p>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-green-800 mb-2">Empty Bins</h3>
                        <p class="text-3xl font-bold text-green-600">{{ $binSummary['empty_bins'] ?? 0 }}</p>
                    </div>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-yellow-800 mb-2">Partial Bins</h3>
                        <p class="text-3xl font-bold text-yellow-600">{{ $binSummary['partial_bins'] ?? 1 }}</p>
                    </div>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-red-800 mb-2">Full Bins</h3>
                        <p class="text-3xl font-bold text-red-600">{{ $binSummary['full_bins'] ?? 2 }}</p>
                    </div>
                </div>

                <!-- Additional Stats -->
                <div class="grid md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-purple-800 mb-2">Overflowing Bins</h3>
                        <p class="text-3xl font-bold text-purple-600">{{ $binSummary['overflowing_bins'] ?? 0 }}</p>
                    </div>
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-orange-800 mb-2">Open Alerts</h3>
                        <p class="text-3xl font-bold text-orange-600">{{ $binSummary['open_alerts'] ?? 0 }}</p>
                    </div>
                    <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-indigo-800 mb-2">Collections Today</h3>
                        <p class="text-3xl font-bold text-indigo-600">{{ $binSummary['collections_today'] ?? 0 }}</p>
                    </div>
                </div>

                <!-- Bins Table -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">All Bins</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-300">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Location</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Level</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($bins as $bin)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $bin->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $bin->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $bin->latitude }},
                                        {{ $bin->longitude }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ ucfirst($bin->level) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($bin->level == 'empty') bg-green-100 text-green-800
                                            @elseif($bin->level == 'half') bg-yellow-100 text-yellow-800
                                            @elseif($bin->level == 'full') bg-red-100 text-red-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($bin->level) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Open Alerts -->
                @if($openAlerts->count() > 0)
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Open Alerts</h2>
                    <div class="space-y-4">
                        @foreach($openAlerts as $alert)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-semibold text-yellow-800">Alert for Bin
                                        {{ $alert->bin->name ?? $alert->bin_id }}
                                    </h3>
                                    <p class="text-yellow-700">{{ $alert->message }}</p>
                                    <p class="text-sm text-yellow-600 mt-1">Type: {{ $alert->type }} | Status:
                                        {{ $alert->status }}
                                    </p>
                                </div>
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-200 text-yellow-800">
                                    {{ $alert->level }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection