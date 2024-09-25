@extends('layouts.app')

@section('title', 'Music')

@section('content')
<div class="container mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('music.index') }}" class="text-indigo-600 hover:text-indigo-800 inline-flex items-center">
                        Users
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 5l7 7-7 7"></path>
                        </svg>
                        <span class="ml-1 text-gray-500 md:ml-2">Create</span>
                    </div>
                </li>
            </ol>
        </nav>

        <h1 class="text-2xl font-bold text-indigo-600">Create Music</h1>

        <form method="POST" action="{{ route('music.store') }}" class="mt-6">
            @csrf

            <div id="music-records">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 music-row">
                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="music[0][title]" class="w-full p-2 border border-gray-300 rounded-lg @error('title') border-red-500 @enderror" required>
                        @error('title')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium text-gray-700">Album Name</label>
                        <input type="text" name="music[0][album_name]" class="w-full p-2 border border-gray-300 rounded-lg @error('album_name') border-red-500 @enderror" required>
                        @error('album_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700">Genre</label>
                        <select name="music[0][genre]" class="w-full p-2 border border-gray-300 rounded-lg @error('genre') border-red-500 @enderror" required>
                            <option value="" disabled selected>Select a genre</option>
                            @foreach($genres as $genre)
                                <option value="{{ $genre }}">{{ $genre }}</option>
                            @endforeach
                        </select>
                        @error('genre')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="md:col-span-1 flex items-end">
                        <button type="button" class="add-row px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                            +
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Create</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let recordIndex = 1;
        const musicRecords = document.getElementById('music-records');

        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('add-row')) {
                e.preventDefault();
                const newRow = document.createElement('div');
                newRow.classList.add('grid', 'grid-cols-1', 'md:grid-cols-12', 'gap-6', 'music-row', 'mt-4');
                newRow.innerHTML = `
                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="music[${recordIndex}][title]" class="w-full p-2 border border-gray-300 rounded-lg" required>
                    </div>
                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium text-gray-700">Album Name</label>
                        <input type="text" name="music[${recordIndex}][album_name]" class="w-full p-2 border border-gray-300 rounded-lg" required>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700">Genre</label>
                        <select name="music[${recordIndex}][genre]" class="w-full p-2 border border-gray-300 rounded-lg" required>
                            <option value="" disabled selected>Select a genre</option>
                            @foreach($genres as $genre)
                                <option value="{{ $genre }}">{{ $genre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-1 flex items-end">
                        <button type="button" class="remove-row px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                            -
                        </button>
                    </div>
                `;
                musicRecords.appendChild(newRow);
                recordIndex++;
            }

            if (e.target && e.target.classList.contains('remove-row')) {
                e.preventDefault();
                e.target.closest('.music-row').remove();
            }
        });
    });
</script>

@endsection
