@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Public Dashboard</h1>
                    <div class="flex space-x-4">
                        <button onclick="reportIssue()"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">
                            Report Issue
                        </button>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

                <!-- System Overview -->
                <div class="grid md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-blue-800 mb-2">Total Bins</h3>
                        <p class="text-3xl font-bold text-blue-600">{{ $bins->count() }}</p>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-green-800 mb-2">Empty Bins</h3>
                        <p class="text-3xl font-bold text-green-600">{{ $bins->where('level', 'empty')->count() }}
                        </p>
                    </div>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-yellow-800 mb-2">Full Bins</h3>
                        <p class="text-3xl font-bold text-yellow-600">{{ $bins->where('level', 'full')->count() }}
                        </p>
                    </div>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-red-800 mb-2">Your Reports</h3>
                        <p class="text-3xl font-bold text-red-600">{{ $userAlerts->count() }}</p>
                    </div>
                </div>

                <!-- Bins Map/Location -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Nearby Bins</h2>
                    <div class="bg-gray-100 rounded-lg p-6">
                        <p class="text-gray-600 mb-4">Find and report issues with waste bins in your area.</p>
                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($bins->take(6) as $bin)
                            <div class="bg-white rounded-lg p-4 shadow">
                                <h3 class="font-semibold text-gray-900">{{ $bin->name }}</h3>
                                <p class="text-sm text-gray-600">Location: {{ $bin->latitude }}, {{ $bin->longitude }}
                                </p>
                                <p class="text-sm text-gray-600">Status: {{ ucfirst($bin->level) }}</p>
                                <button onclick="reportBinIssue({{ $bin->id }})"
                                    class="mt-2 bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">
                                    Report Issue
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Your Reports -->
                @if($userAlerts->count() > 0)
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Your Recent Reports</h2>
                    <div class="space-y-4">
                        @foreach($userAlerts as $alert)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-semibold text-blue-800">Report for Bin
                                        {{ $alert->bin->name ?? $alert->bin_id }}
                                    </h3>
                                    <p class="text-blue-700">{{ $alert->message }}</p>
                                    <p class="text-sm text-blue-600 mt-1">Status: {{ $alert->status }} | Type:
                                        {{ $alert->type }}
                                    </p>
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($alert->status == 'open') bg-yellow-200 text-yellow-800
                                    @else bg-green-200 text-green-800 @endif">
                                    {{ ucfirst($alert->status) }}
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

<script>
    function reportIssue() {
        // Get user's location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                // Submit report
                fetch('/alerts', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            latitude: lat,
                            longitude: lng,
                            message: 'Public report from location'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert('Issue reported successfully!');
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error reporting issue');
                    });
            });
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    }

    function reportBinIssue(binId) {
        const message = prompt('Describe the issue:');
        if (message) {
            fetch('/alerts', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        bin_id: binId,
                        message: message
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert('Issue reported successfully!');
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error reporting issue');
                });
        }
    }
</script>
@endsection