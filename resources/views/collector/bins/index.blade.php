@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Bins Management</h1>
                    <div class="flex space-x-4">
                        <a href="{{ route('collector.collection.index') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                            ← Back to Dashboard
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

                <!-- Bins by Type -->
                @php
                $types = ['plastic', 'paper', 'glass', 'metal'];
                $typeColors = [
                'plastic' => 'bg-yellow-100 text-yellow-800',
                'paper' => 'bg-blue-100 text-blue-800',
                'glass' => 'bg-green-100 text-green-800',
                'metal' => 'bg-gray-100 text-gray-800'
                ];
                @endphp

                @foreach($types as $type)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $typeColors[$type] }} mr-3">
                            {{ ucfirst($type) }} Bins
                        </span>
                        <span class="text-gray-600">({{ $binsByType->get($type, collect())->count() }} bins)</span>
                    </h2>

                    @if($binsByType->get($type, collect())->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($binsByType->get($type, collect()) as $bin)
                        <div
                            class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $bin->name }}</h3>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($bin->level == 'empty') bg-green-100 text-green-800
                                    @elseif($bin->level == 'partial') bg-yellow-100 text-yellow-800
                                    @elseif($bin->level == 'full') bg-orange-100 text-orange-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($bin->level) }}
                                </span>
                            </div>
                            <div class="space-y-1 text-sm text-gray-600">
                                <p><strong>ID:</strong> {{ $bin->id }}</p>
                                <p><strong>Area:</strong> {{ $bin->area_name }}</p>
                                <p><strong>Status:</strong> {{ $bin->collected ? 'Collected' : 'Uncollected' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                        <p class="text-gray-500">No {{ $type }} bins found.</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection