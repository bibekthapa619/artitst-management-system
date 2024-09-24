@extends('layouts.app')

@section('title', 'Artists')

@section('content')
<div class="container mx-auto py-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <h1 class="text-2xl font-bold text-indigo-600">Artists</h1>

        <div class="flex justify-between mt-4 mb-4">
            <form method="GET" action="{{ url()->current() }}" class="flex w-full md:w-1/3">
                <input type="text" name="search" class="w-full p-2 border border-gray-300 rounded-lg" placeholder="Search artists..." value="{{ request()->get('search') }}">
                <button type="submit" class="ml-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Search</button>
            </form>
            
            @hasrole('artist_manager')
            <div class="relative">
                <button id="options-menu-button" class="ml-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 flex items-center">
                    Options
                    <svg class="w-5 h-5 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
        
                <div id="options-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                    <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu-button">
                        <a href="{{ route('artists.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                            Create Artist
                        </a>
                        <a href="{{ route('artists.import-form') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                            Import
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                            Export
                        </a>
                    </div>
                </div>
            </div>
            @endhasrole
        </div>      

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-indigo-100 text-indigo-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">SN</th>
                            <th class="py-3 px-6 text-left">Name</th>
                            <th class="py-3 px-6 text-left">Artist Name</th>
                            <th class="py-3 px-6 text-left">First Release Year</th>
                            <th class="py-3 px-6 text-left">No of Albums</th>
                            <th class="py-3 px-6 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="artistsTable" class="text-gray-600 text-sm">
                        @foreach($artists as $artist)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-6 text-left">
                                    {{ ($pagination['current_page'] - 1) * $pagination['page_size'] + $loop->index + 1 }}
                                </td>
                                <td class="py-3 px-6 text-left">{{ $artist['first_name'] }} {{ $artist['last_name'] }}</td>
                                <td class="py-3 px-6 text-left">{{ $artist['name'] }}</td>
                                <td class="py-3 px-6 text-left">{{ $artist['first_release_year'] }}</td>
                                <td class="py-3 px-6 text-left">{{ $artist['no_of_albums_released'] }}</td>
                                
                                <td class="py-3 px-6 text-center">
                                    <div class="flex justify-center items-center space-x-4">
                                        <a href="{{ route('artists.show', $artist['user_id']) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1.5 12c2.5 4 6.5 7 10.5 7s8-3 10.5-7c-2.5-4-6.5-7-10.5-7S4 8 1.5 12z"/>
                                                <circle cx="12" cy="12" r="3" fill="currentColor" />
                                            </svg>
                                        </a>
                                        @hasrole('artist_manager')
                                        <a href="{{ route('artists.edit', $artist['user_id']) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.414 3a2 2 0 012.828 0l1.758 1.758a2 2 0 010 2.828l-9.9 9.9a2 2 0 01-.707.414l-3.543 1.414a1 1 0 01-1.272-1.272l1.414-3.543a2 2 0 01.414-.707l9.9-9.9zM14 7l3 3m-6.586 6.586L7 17l.707-.707m-1.414-1.414L7 17" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('artists.destroy', $artist['user_id']) }}" method="POST" onsubmit="return confirmDelete()">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                        @endhasrole
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @include('partials.pagination')
    </div>
</div>
<script>
    const optionsButton = document.getElementById('options-menu-button');
    const optionsMenu = document.getElementById('options-menu');

    optionsButton.addEventListener('click', function () {
        optionsMenu.classList.toggle('hidden');
    });

    optionsMenu.addEventListener('click', function () {
        optionsMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', function (event) {
        const isClickInside = optionsButton.contains(event.target) || optionsMenu.contains(event.target);

        if (!isClickInside) {
            optionsMenu.classList.add('hidden');
        }
    });

    function confirmDelete() {
        return confirm('Are you sure you want to delete this artist?');
    }
</script>
@endsection
