@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Edit Bin</h1>
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

                <form id="edit-bin-form" method="POST" action="{{ url('/collector/bins') }}/{{ $bin->id }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Bin Name</label>
                            <input type="text" id="name" name="name" value="{{ $bin->name }}" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="area_name" class="block text-sm font-medium text-gray-700">Area Name</label>
                            <select id="area_name" name="area_name" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="Trinidad Poblacion"
                                    {{ $bin->area_name == 'Trinidad Poblacion' ? 'selected' : '' }}>Trinidad Poblacion
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="level" class="block text-sm font-medium text-gray-700">Level</label>
                            <select id="level" name="level" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="empty" {{ $bin->level == 'empty' ? 'selected' : '' }}>Empty</option>
                                <option value="partial" {{ $bin->level == 'partial' ? 'selected' : '' }}>Partial
                                </option>
                                <option value="full" {{ $bin->level == 'full' ? 'selected' : '' }}>Full</option>
                                <option value="overflowing" {{ $bin->level == 'overflowing' ? 'selected' : '' }}>
                                    Overflowing</option>
                            </select>
                        </div>
                    </div>

                    @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mt-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="mt-6 flex justify-end space-x-4">
                        <a href="{{ route('collector.collection.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">
                            Cancel
                        </a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                            Update Bin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection