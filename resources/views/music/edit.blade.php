@extends('layouts.app')

@section('title', 'Music')

@section('content')
<div class="container mx-auto py-8">
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

        <form method="POST" action="{{ route('music.update', $music['id']) }}" class="mt-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="md:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">Music Details</h2>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" value="{{ old('title', $music['title']) }}" class="w-full p-2 border border-gray-300 rounded-lg @error('title') border-red-500 @enderror" required>
                    @error('title')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Album Name</label>
                    <input type="text" name="album_name" value="{{ old('album_name',$music['album_name']) }}" class="w-full p-2 border border-gray-300 rounded-lg @error('last_name') border-red-500 @enderror" required>
                    @error('album_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Genre</label>
                    <select id="genre" name="genre" class="w-full p-2 border border-gray-300 rounded-lg @error('genre') border-red-500 @enderror" required>
                        <option value="" disabled {{ old('genre',$music['genre']) === null ? 'selected' : '' }}>Select a genre</option>
                        @foreach($genres as $genre)
                            <option value="{{ $genre }}" {{ old('genre', $music['genre'] ?? '') == $genre ? 'selected' : '' }}>
                                {{ $genre }}
                            </option>
                        @endforeach
                    </select>
                    @error('genre')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Create</button>
            </div>
        </form>
    </div>
</div>

@endsection
