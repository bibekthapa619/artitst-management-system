@extends('layouts.app')

@section('title', 'Music')

@section('content')
<div class="container mx-auto py-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <h1 class="text-2xl font-bold text-indigo-600">Music</h1>

        <div class="flex justify-between mt-4 mb-4">
            <form method="GET" action="{{ url()->current() }}" class="flex w-full md:w-1/3">
                <input type="text" name="search" class="w-full p-2 border border-gray-300 rounded-lg" placeholder="Search music..." value="{{ request()->get('search') }}">
                <button type="submit" class="ml-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Search</button>
            </form>
            <a href="{{ route('music.create') }}" class="ml-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Create
            </a>
        </div>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-indigo-100 text-indigo-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">SN</th>
                            <th class="py-3 px-6 text-left">Title</th>
                            <th class="py-3 px-6 text-left">Album Name</th>
                            <th class="py-3 px-6 text-left">Genre</th>
                            <th class="py-3 px-6 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="musicTable" class="text-gray-600 text-sm">
                        @foreach($musics as $music)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-6 text-left">
                                    {{ ($pagination['current_page'] - 1) * $pagination['page_size'] + $loop->index + 1 }}
                                </td>
                                <td class="py-3 px-6 text-left">{{ $music['title'] }}</td>
                                <td class="py-3 px-6 text-left">{{ $music['album_name'] }}</td>
                                <td class="py-3 px-6 text-left">{{ $music['genre'] }}</td>
                                
                                <td class="py-3 px-6 text-center">
                                    <div class="flex justify-center items-center space-x-4">
                                        <a href="{{ route('music.edit', $music['id']) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.414 3a2 2 0 012.828 0l1.758 1.758a2 2 0 010 2.828l-9.9 9.9a2 2 0 01-.707.414l-3.543 1.414a1 1 0 01-1.272-1.272l1.414-3.543a2 2 0 01.414-.707l9.9-9.9zM14 7l3 3m-6.586 6.586L7 17l.707-.707m-1.414-1.414L7 17" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('music.destroy', $music['id']) }}" method="POST" onsubmit="return confirmDelete()">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
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
    function confirmDelete() {
        return confirm('Are you sure you want to delete this music?');
    }
</script>
@endsection
