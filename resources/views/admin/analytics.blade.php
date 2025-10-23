@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Analytics Dashboard</h1>
                    <a href="{{ route('admin.dashboard') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                        Back to Dashboard
                    </a>
                </div>

                <!-- Bin Summary -->
                <div class="grid md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-blue-800 mb-2">Total Bins</h3>
                        <p class="text-3xl font-bold text-blue-600">{{ $binSummary['total_bins'] ?? 0 }}</p>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-green-800 mb-2">Empty Bins</h3>
                        <p class="text-3xl font-bold text-green-600">{{ $binSummary['empty_bins'] ?? 0 }}</p>
                    </div>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-yellow-800 mb-2">Partial Bins</h3>
                        <p class="text-3xl font-bold text-yellow-600">{{ $binSummary['partial_bins'] ?? 0 }}</p>
                    </div>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-red-800 mb-2">Full Bins</h3>
                        <p class="text-3xl font-bold text-red-600">{{ $binSummary['full_bins'] ?? 0 }}</p>
                    </div>
                </div>

                <!-- Overflowing Bins -->
                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-purple-800 mb-2">Overflowing Bins</h3>
                        <p class="text-3xl font-bold text-purple-600">{{ $binSummary['overflowing_bins'] ?? 0 }}</p>
                    </div>
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-orange-800 mb-2">Total Alerts</h3>
                        <p class="text-3xl font-bold text-orange-600">{{ $binSummary['total_alerts'] ?? 0 }}</p>
                    </div>
                </div>



                <!-- Collection Efficiency -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Collection Efficiency</h2>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                        <p class="text-lg font-semibold text-gray-800">Overall Efficiency: <span
                                class="text-2xl font-bold text-green-600">{{ number_format($collectionEfficiency ?? 0, 2) }}%</span>
                        </p>
                    </div>
                </div>

                <!-- Bin Collection Rates -->
                @if(isset($binCollectionRates) && count($binCollectionRates) > 0)
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Bin Collection Rates by Level</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-300">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Level</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Bins</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Collections</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Collection Rate (%)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($binCollectionRates as $rate)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ ucfirst($rate['level'] ?? 'N/A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $rate['total_bins'] ?? 0 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $rate['collections'] ?? 0 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($rate['efficiency_rate'] ?? 0, 2) }}%
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection