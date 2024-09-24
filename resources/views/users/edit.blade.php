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
                        <span class="ml-1 text-gray-500 md:ml-2">Edit</span>
                    </div>
                </li>
            </ol>
        </nav>

        <h1 class="text-2xl font-bold text-indigo-600">Edit User</h1>

        <form method="POST" action="{{ route('users.update', $user['id']) }}" class="mt-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="md:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">User Details</h2>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $user['first_name']) }}" class="w-full p-2 border border-gray-300 rounded-lg @error('first_name') border-red-500 @enderror" required>
                    @error('first_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $user['last_name']) }}" class="w-full p-2 border border-gray-300 rounded-lg @error('last_name') border-red-500 @enderror" required>
                    @error('last_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user['phone']) }}" class="w-full p-2 border border-gray-300 rounded-lg @error('phone') border-red-500 @enderror" required>
                    @error('phone')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                    <input type="date" name="dob" value="{{ old('dob', $user['dob']) }}" class="w-full p-2 border border-gray-300 rounded-lg @error('dob') border-red-500 @enderror" required>
                    @error('dob')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Gender</label>
                    <select name="gender" class="w-full p-2 border border-gray-300 rounded-lg @error('gender') border-red-500 @enderror" required>
                        <option value="" disabled>Select Gender</option>
                        <option value="m" {{ old('gender', $user['gender']) == 'm' ? 'selected' : '' }}>Male</option>
                        <option value="f" {{ old('gender', $user['gender']) == 'f' ? 'selected' : '' }}>Female</option>
                        <option value="o" {{ old('gender', $user['gender']) == 'o' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" name="address" value="{{ old('address', $user['address']) }}" class="w-full p-2 border border-gray-300 rounded-lg @error('address') border-red-500 @enderror" required>
                    @error('address')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                @if($user['role'] === 'artist')
                <div id="artist-fields" class="md:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">Artist Details</h2>
                
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Artist Name</label>
                            <input type="text" name="name" value="{{ old('name', $user['name'] ?? '') }}" class="w-full p-2 border border-gray-300 rounded-lg" required>
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    
                        <div>
                            <label class="block text-sm font-medium text-gray-700">First Release Year</label>
                            <input type="number" name="first_release_year" value="{{ old('first_release_year', $user['first_release_year'] ?? '') }}" class="w-full p-2 border border-gray-300 rounded-lg" required>
                            @error('first_release_year')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Number of Albums Released</label>
                            <input type="number" name="no_of_albums_released" value="{{ old('no_of_albums_released', $user['no_of_albums_released'] ?? '') }}" class="w-full p-2 border border-gray-300 rounded-lg" required>
                            @error('no_of_albums_released')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="md:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">Login Details</h2>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user['email']) }}" class="w-full p-2 border border-gray-300 rounded-lg @error('email') border-red-500 @enderror" required>
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="mt-6">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
