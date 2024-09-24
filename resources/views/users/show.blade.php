@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="container mx-auto">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('users.index') }}" class="text-indigo-600 hover:text-indigo-800 inline-flex items-center">
                        Users
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 5l7 7-7 7"></path>
                        </svg>
                        <span class="ml-1 text-gray-500 md:ml-2">Details</span>
                    </div>
                </li>
            </ol>
        </nav>

        <h1 class="text-2xl font-bold text-indigo-600">User Details</h1>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block text-sm font-medium text-gray-700">First Name</label>
                <p class="p-2 bg-gray-100 border border-gray-300 rounded-lg">{{ $user['first_name'] }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Last Name</label>
                <p class="p-2 bg-gray-100 border border-gray-300 rounded-lg">{{ $user['last_name'] }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <p class="p-2 bg-gray-100 border border-gray-300 rounded-lg">{{ $user['phone'] }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                <p class="p-2 bg-gray-100 border border-gray-300 rounded-lg">{{ $user['dob'] }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Gender</label>
                <p class="p-2 bg-gray-100 border border-gray-300 rounded-lg">
                    @if($user['gender'] === 'm') Male
                    @elseif($user['gender'] === 'f') Female
                    @elseif($user['gender'] === 'o') Other
                    @endif
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Role</label>
                <p class="p-2 bg-gray-100 border border-gray-300 rounded-lg">{{ $user['role'] }}</p>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Address</label>
                <p class="p-2 bg-gray-100 border border-gray-300 rounded-lg">{{ $user['address'] }}</p>
            </div>
            
            @if($user['role'] === 'artist')
            <div class="md:col-span-2">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Artist Details</h2>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Artist Name</label>
                <p class="p-2 bg-gray-100 border border-gray-300 rounded-lg">{{ $user['name'] }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">First Release Year</label>
                <p class="p-2 bg-gray-100 border border-gray-300 rounded-lg">{{ $user['first_release_year'] }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">No of Albums Released</label>
                <p class="p-2 bg-gray-100 border border-gray-300 rounded-lg">{{ $user['no_of_albums_released'] }}</p>
            </div>

            @endif
            <div class="md:col-span-2">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Login Details</h2>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <p class="p-2 bg-gray-100 border border-gray-300 rounded-lg">{{ $user['email'] }}</p>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('users.edit', $user['id']) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Edit
            </a>
        </div>
    </div>
</div>
@endsection
