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
        
                <div id="options-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                    <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu-button">
                        <a href="{{ route('artists.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                            Create Artist
                        </a>
                        <a href="{{ route('artists.import-form') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                            Import
                        </a>
                        <a href="{{ route('artists.export',['search' => request()->get('search')]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                            Export
                        </a>
                    </div>
                </div>
            </div>
            @endhasrole
        </div>      

        <div class="bg-white shadow-sm rounded-lg">
            <div class="table-responsive">
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
                                <td class="py-3 px-6 text-center relative">
                                    <div class="flex justify-center items-center space-x-4">
                                        <div class="relative inline-block text-left">
                                            <button onclick="toggleDropdown(this)" class="text-gray-600 hover:text-gray-900" title="Options">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6h.01M12 12h.01M12 18h.01"/>
                                                  </svg>
                                            </button>
                                
                                            <div class="hidden origin-top-right absolute right-0 mt-2 w-44 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                                    <a href="{{ route('artists.show', $artist['user_id']) }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50" role="menuitem">Profile</a>
                                                    <a href="{{ route('artists.show-music', $artist['user_id']) }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50" role="menuitem">Music</a>
                                                    @hasrole('artist_manager')
                                                    <a href="{{ route('artists.edit', $artist['user_id']) }}" class="block px-4 py-2 text-sm text-yellow-600 hover:bg-blue-50" role="menuitem">Edit</a>
                                                    <form action="{{ route('artists.destroy', $artist['user_id']) }}" method="POST" class="block" role="menuitem" onsubmit="return confirmDelete()">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-blue-50">Delete</button>
                                                    </form>
                                                    @endhasrole
                                                </div>
                                            </div>
                                        </div>
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

    function toggleDropdown(button) {
        const dropdown = button.nextElementSibling;

        document.querySelectorAll('.relative .origin-top-right').forEach(d => {
            if (d !== dropdown) {
                d.classList.add('hidden');
            }
        });

        dropdown.classList.toggle('hidden');

        const dropdownRect = dropdown.getBoundingClientRect();
        const tableRect = button.closest('table').getBoundingClientRect(); 
        const windowBottom = window.innerHeight; 

        const maxBottom = Math.min(tableRect.bottom, windowBottom);

        if (dropdownRect.bottom > maxBottom) {
            dropdown.style.top = `-${dropdownRect.height}px`; 
        } else {
            dropdown.style.top = ''; 
        }
    }
    document.addEventListener('click', function (e) {
        const dropdowns = document.querySelectorAll('.relative .origin-top-right');
        dropdowns.forEach(dropdown => {
            if (!dropdown.contains(e.target) && !dropdown.previousElementSibling.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });

</script>
@endsection
