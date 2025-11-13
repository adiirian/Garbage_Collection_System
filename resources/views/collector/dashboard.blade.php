@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Collector Dashboard</h1>
                    <div class="flex space-x-4">
                        <a href="{{ route('collector.profile') }}"
                            class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg">
                            View Profile
                        </a>
                        <button id="add-bin-btn"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                            + Add Bin
                        </button>
                        <a href="{{ route('collector.bins.index') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                            Manage Bins
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

                <!-- Add Bin Form -->
                <div id="add-bin-form-container" class="mb-8 hidden">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Add New Bin</h2>
                    <form id="add-bin-form" class="bg-gray-50 p-6 rounded-lg">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="bin-name" class="block text-sm font-medium text-gray-700">Bin Name</label>
                                <input type="text" id="bin-name" name="name" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="bin-area_name" class="block text-sm font-medium text-gray-700">Area
                                    Name</label>
                                <select id="bin-area_name" name="area_name" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Area</option>
                                    <option value="Trinidad Poblacion">Trinidad Poblacion</option>
                                </select>
                            </div>
                            <div>
                                <label for="bin-level" class="block text-sm font-medium text-gray-700">Level</label>
                                <select id="bin-level" name="level" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Level</option>
                                    <option value="empty">Empty</option>
                                    <option value="partial">Partial</option>
                                    <option value="full">Full</option>
                                    <option value="overflowing">Overflowing</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end space-x-4">
                            <button type="button" id="cancel-add-bin"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">
                                Cancel
                            </button>
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                                Create Bin
                            </button>
                        </div>
                    </form>
                </div>

                <!-- System Overview -->
                <div class="grid md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 cursor-pointer transition-all"
                        onclick="handleCardClick('all')" id="total-bins-card">
                        <h3 class="text-xl font-semibold text-blue-800 mb-2">Total Bins</h3>
                        <p class="text-3xl font-bold text-blue-600" id="total-bins">{{ $totalBins }}</p>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6 cursor-pointer transition-all"
                        onclick="handleCardClick('collected')" id="collected-bins-card">
                        <h3 class="text-xl font-semibold text-green-800 mb-2">Collected Bins</h3>
                        <p class="text-3xl font-bold text-green-600" id="collected-bins">
                            {{ $bins->where('collected', true)->count() }}
                        </p>
                    </div>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6 cursor-pointer transition-all"
                        onclick="handleCardClick('uncollected')" id="uncollected-bins-card">
                        <h3 class="text-xl font-semibold text-red-800 mb-2">Uncollected Bins</h3>
                        <p class="text-3xl font-bold text-red-600" id="uncollected-bins">
                            {{ $bins->where('collected', false)->count() }}
                        </p>
                    </div>
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 cursor-pointer transition-all"
                        onclick="handleCardClick('alerts')" id="open-alerts-card">
                        <h3 class="text-xl font-semibold text-orange-800 mb-2">Open Alerts</h3>
                        <p class="text-3xl font-bold text-orange-600" id="open-alerts">{{ $openAlerts->count() }}</p>
                    </div>
                </div>

                <!-- Today's Collections -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Today's Collections</h2>
                    <p class="text-lg text-gray-700">{{ $todayCollections }} bins collected today</p>
                </div>

                <!-- Bins -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4" id="bins-table-title">All Bins</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-300" id="bins-table">
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
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="bins-table-body">
                                @foreach($bins as $bin)
                                <tr id="bin-row-{{ $bin->id }}" class="bin-row" data-filter="all"
                                    data-collected="{{ $bin->collected ? 'true' : 'false' }}"
                                    data-has-alert="{{ $openAlerts->where('bin_id', $bin->id)->count() > 0 ? 'true' : 'false' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $bin->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $bin->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $bin->area_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $bin->collected ? 'Empty' : ($bin->level ? ucfirst($bin->level) : 'Empty') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $bin->collected ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $bin->collected ? 'Collected' : 'Uncollected' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="editBin({{ $bin->id }})"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs mr-2">Edit</button>
                                        <button onclick="deleteBin({{ $bin->id }})"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs mr-2">Delete</button>
                                        <button onclick="collect({{ $bin->id }})"
                                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs">Collect</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Open Alerts -->
                <div id="open-alerts-section" class="mb-8 hidden">
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
                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-200 text-yellow-800">{{ $alert->level }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recently Collected Bins -->
                @if($collectedBins->count() > 0)
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Recently Collected Bins</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-300">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Bin Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Collector</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Collected At</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($collectedBins as $collected)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $collected['bin_name'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $collected['collector_name'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $collected['collected_at'] ? \Carbon\Carbon::parse($collected['collected_at'])->format('M d, Y H:i') : 'N/A' }}
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

<!-- Edit Bin Modal -->
<div id="edit-bin-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Bin</h3>
            <form id="edit-bin-form">
                @csrf
                <input type="hidden" id="edit-bin-id" name="id">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit-bin-name">Name</label>
                    <input type="text" id="edit-bin-name" name="name"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit-bin-area_name">Area Name</label>
                    <select id="edit-bin-area_name" name="area_name"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                        <option value="Trinidad Poblacion">Trinidad Poblacion</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit-bin-level">Level</label>
                    <select id="edit-bin-level" name="level"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                        <option value="empty">Empty</option>
                        <option value="partial">Partial</option>
                        <option value="full">Full</option>
                        <option value="overflowing">Overflowing</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeEditModal()"
                        class="mr-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bin Details Modal -->
<div id="bin-details-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Bin Details</h3>
            <div id="bin-details-content"></div>
            <div class="flex justify-end mt-4">
                <button type="button" onclick="closeDetailsModal()"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle Add Bin Form
    document.getElementById('add-bin-btn').addEventListener('click', function() {
        const formContainer = document.getElementById('add-bin-form-container');
        formContainer.classList.toggle('hidden');
    });

    // Cancel Add Bin
    document.getElementById('cancel-add-bin').addEventListener('click', function() {
        const formContainer = document.getElementById('add-bin-form-container');
        formContainer.classList.add('hidden');
        document.getElementById('add-bin-form').reset();
    });

    // Add Bin Form Handler
    document.getElementById('add-bin-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch('/collector/bins', {
                method: 'POST',
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    // Add new bin to table
                    addBinToTable(data.bin);
                    // Clear form and hide
                    document.getElementById('add-bin-form').reset();
                    document.getElementById('add-bin-form-container').classList.add('hidden');
                    // Update counters
                    updateCounters();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding bin: ' + error.message);
            });
    });

    // Edit Bin Functions
    function editBin(binId) {
        // Fetch bin data and populate modal
        fetch(`/collector/bins/${binId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('edit-bin-id').value = data.id;
                document.getElementById('edit-bin-name').value = data.name;
                document.getElementById('edit-bin-area_name').value = data.area_name;
                document.getElementById('edit-bin-level').value = data.level;
                document.getElementById('edit-bin-modal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading bin data: ' + error.message);
            });
    }

    function closeEditModal() {
        document.getElementById('edit-bin-modal').classList.add('hidden');
    }

    // Edit Bin Form Handler
    document.getElementById('edit-bin-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const binId = document.getElementById('edit-bin-id').value;
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch(`/collector/bins/${binId}`, {
                method: 'PUT',
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    closeEditModal();
                    // Update bin in table
                    updateBinInTable(binId, data.bin);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating bin: ' + error.message);
            });
    });

    // Delete Bin Function
    function deleteBin(binId) {
        if (confirm('Are you sure you want to delete this bin?')) {
            fetch(`/collector/bins/${binId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.message) {
                        alert(data.message);
                        // Remove bin from table
                        document.getElementById(`bin-row-${binId}`).remove();
                        // Update counters
                        updateCounters();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting bin: ' + error.message);
                });
        }
    }

    // View Bin Details Function
    function viewBinDetails(binId) {
        // Fetch bin data and show in modal
        fetch(`/admin/bins/${binId}`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const content = document.getElementById('bin-details-content');
                content.innerHTML = `
                <p><strong>ID:</strong> ${data.id}</p>
                <p><strong>Name:</strong> ${data.name}</p>
                <p><strong>Area:</strong> ${data.area_name}</p>
                <p><strong>Level:</strong> ${data.level}</p>
                <p><strong>Created:</strong> ${data.created_at}</p>
                <p><strong>Updated:</strong> ${data.updated_at}</p>
            `;
                document.getElementById('bin-details-modal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading bin details: ' + error.message);
            });
    }

    function closeDetailsModal() {
        document.getElementById('bin-details-modal').classList.add('hidden');
    }

    // Collect Bin Function
    function collect(binId) {
        if (confirm('Mark this bin as collected?')) {
            fetch(`/collector/bins/${binId}/clean`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    alert('Bin collected!');
                    // Remove the entire bin row from the table
                    const binRow = document.getElementById(`bin-row-${binId}`);
                    if (binRow) {
                        binRow.remove();
                    }
                    // Update counters
                    updateCounters();
                    updateTotalCollections();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error collecting bin: ' + error.message);
                });
        }
    }

    // Helper Functions
    function addBinToTable(bin) {
        const tbody = document.getElementById('bins-table-body');
        const row = document.createElement('tr');
        row.id = 'bin-row-' + bin.id;
        row.className = 'bin-row';
        row.setAttribute('data-filter', 'all');
        row.setAttribute('data-collected', 'false');
        row.setAttribute('data-has-alert', 'false');
        const level = bin.collected ? 'Empty' : (bin.level ? bin.level.charAt(0).toUpperCase() + bin.level.slice(1) :
            'Empty');
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${bin.id}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${bin.name}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${bin.area_name}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${level}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Uncollected</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button onclick="editBin(${bin.id})" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs mr-2">Edit</button>
                <button onclick="deleteBin(${bin.id})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs mr-2">Delete</button>
                <button onclick="collect(${bin.id})" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs">Collect</button>
            </td>
        `;
        tbody.appendChild(row);
    }

    function updateBinInTable(binId, bin) {
        const row = document.getElementById(`bin-row-${binId}`);
        if (row) {
            row.cells[1].textContent = bin.name;
            row.cells[2].textContent = bin.area_name;
            const level = bin.collected ? 'Empty' : (bin.level ? bin.level.charAt(0).toUpperCase() + bin.level.slice(1) :
                'Empty');
            row.cells[3].textContent = level;
        }
    }

    function updateCounters() {
        // Fetch updated counts from server via API
        fetch('/admin/analytics/bins/summary', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // Update the counters on the current page
                document.getElementById('total-bins').textContent = data.total_bins;
                document.getElementById('bins-needing-attention').textContent = data.bins_with_alerts;
                document.getElementById('total-alerts').textContent = data
                    .bins_with_alerts; // Fixed: removed stray code and line break
            })
            .catch(error => {
                console.error('Error updating counters:', error);
                // Fallback to page reload
                location.reload();
            });
    }

    // Function to update total collections counter
    function updateTotalCollections() {
        fetch('/admin/analytics/collections/today', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('total-collections').textContent = data.collections_today;
            })
            .catch(error => {
                console.error('Error updating total collections:', error);
                // Fallback: calculate from current value + 1
                const current = parseInt(document.getElementById('total-collections').textContent) || 0;
                document.getElementById('total-collections').textContent = current + 1;
            });
    }

    // Card click handler for filtering bins
    let activeFilter = 'all';

    function handleCardClick(filterType) {
        activeFilter = filterType;

        // Update card styles
        document.querySelectorAll('[id$="-card"]').forEach(card => {
            card.classList.remove('ring-2', 'ring-blue-500');
        });
        document.getElementById(filterType === 'all' ? 'total-bins-card' :
            filterType === 'collected' ? 'collected-bins-card' :
            filterType === 'uncollected' ? 'uncollected-bins-card' :
            'open-alerts-card').classList.add('ring-2', 'ring-blue-500');

        // Show/hide open alerts section
        const alertsSection = document.getElementById('open-alerts-section');
        if (filterType === 'alerts') {
            alertsSection.classList.remove('hidden');
        } else {
            alertsSection.classList.add('hidden');
        }

        // Filter table rows
        const rows = document.querySelectorAll('.bin-row');
        let visibleCount = 0;
        rows.forEach(row => {
            let show = false;
            if (filterType === 'all') {
                show = true;
            } else if (filterType === 'collected') {
                show = row.dataset.collected === 'true';
            } else if (filterType === 'uncollected') {
                show = row.dataset.collected === 'false';
            } else if (filterType === 'alerts') {
                show = row.dataset.hasAlert === 'true';
            }
            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        // Update table title
        const titles = {
            'all': 'All Bins',
            'collected': 'Collected Bins',
            'uncollected': 'Uncollected Bins',
            'alerts': 'Bins with Alerts'
        };
        document.getElementById('bins-table-title').textContent = titles[filterType];
    }

    // Initialize default filter
    document.addEventListener('DOMContentLoaded', function() {
        handleCardClick('all');
    });
</script>
@endsection