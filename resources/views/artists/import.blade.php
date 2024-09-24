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
                        <span class="ml-1 text-gray-500 md:ml-2">Import</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Page Title -->
        <h1 class="text-2xl font-bold text-indigo-600 mb-4">Import Artist</h1>

        <!-- Download Sample CSV and Import Form -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6">
            <!-- Sample CSV Download -->
            <a href="{{ asset('/imports/artist-import.csv') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 mb-4 md:mb-0">
                Download Sample CSV
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Whoops! Something went wrong:</strong>
                <span class="block sm:inline">Please fix the following errors:</span>
                <ul class="mt-2 list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Import Form -->
        <form method="POST" action="{{ route('artists.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="csv_file" class="block text-sm font-medium text-gray-700">Upload CSV File</label>
                <div class="mt-2 flex items-center">
                    <input type="file" name="csv_file" id="csv_file" accept=".csv" class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
            </div>
            
            <!-- Import Button -->
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Import
            </button>
        </form>
    </div>
    <div class="container mx-auto my-6">
        <!-- Informational Box for CSV Instructions -->
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">CSV Upload Instructions:</strong>
            <span class="block sm:inline">Please follow the guidelines below to fill the CSV correctly:</span>
            <ul class="mt-2 list-disc list-inside text-sm text-blue-700">
                <li>Ensure the file is in <strong>CSV format</strong> (comma-separated values).</li>
                <li>The CSV must contain the following headers: <strong>first_name, last_name, email, phone, password, dob, gender, address, artist_name, first_release_year, no_of_albums_released</strong>.</li>
                <li>Each row should contain valid data under the correct header.</li>
                <li><strong>Email</strong> must be unique for each entry and in a valid format (e.g., <em>name@example.com</em>).</li>
                <li><strong>Phone number</strong> should be numeric and between 10 to 15 digits.</li>
                <li><strong>Password</strong> should be hashed after upload (you do not need to enter it hashed).</li>
                <li><strong>Date of birth (dob)</strong> should be in <em>YYYY-MM-DD</em> format.</li>
                <li><strong>Gender</strong> can only be <em>m for male</em>, <em>f for female</em>, or <em>o for other</em>.</li>
                <li><strong>First release year</strong> should be a valid 4-digit year (e.g., 2010).</li>
                <li><strong>Number of albums released</strong> should be a non-negative integer.</li>
            </ul>
        </div>
    </div>
    
</div>
@endsection
