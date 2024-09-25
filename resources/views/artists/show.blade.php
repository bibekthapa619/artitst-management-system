@extends('layouts.app')

@section('title', 'Artists')

@section('content')
<div class="container mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('artists.index') }}" class="text-indigo-600 hover:text-indigo-800 inline-flex items-center">
                        Artists
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 5l7 7-7 7"></path>
                        </svg>
                        <span class="ml-1 text-gray-500 md:ml-2">Profile</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold text-indigo-600">Artist Details</h1>
        
        <div id="user-details" class="">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">First Name</label>
                    <p class="p-2 bg-gray-100 border border-gray-300 rounded-lg">{{ $artist['first_name'] }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                    <p class="p-2 bg-gray-100 border border-gray-300 rounded-lg">{{ $artist['last_name'] }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Artist Name</label>
                    <p class="p-2 bg-gray-100 border border-gray-300 rounded-lg">{{ $artist['name'] }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="p-2 bg-gray-100 border border-gray-300 rounded-lg">{{ $artist['email'] }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">First Release Year</label>
                    <p class="p-2 bg-gray-100 border border-gray-300 rounded-lg">{{ $artist['first_release_year'] }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">No of Albums Released</label>
                    <p class="p-2 bg-gray-100 border border-gray-300 rounded-lg">{{ $artist['no_of_albums_released'] }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <p class="p-2 bg-gray-100 border border-gray-300 rounded-lg">{{ $artist['phone'] }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                    <p class="p-2 bg-gray-100 border border-gray-300 rounded-lg">{{ $artist['dob'] }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Gender</label>
                    <p class="p-2 bg-gray-100 border border-gray-300 rounded-lg">
                        @if($artist['gender'] === 'm') Male
                        @elseif($artist['gender'] === 'f') Female
                        @elseif($artist['gender'] === 'o') Other
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Address</label>
                    <p class="p-2 bg-gray-100 border border-gray-300 rounded-lg">{{ $artist['address'] }}</p>
                </div>

                
            </div>
        </div>
    </div>
</div>


@endsection
