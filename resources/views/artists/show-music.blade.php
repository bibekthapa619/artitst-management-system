@extends('layouts.app')

@section('title', 'Artists')

@section('content')
<div class="container mx-auto py-8">
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
                        <span class="ml-1 text-gray-500 md:ml-2">Details</span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <div class="border-b border-gray-200 mb-6">
            <ul class="flex">
                <li class="mr-2">
                    <a id="user-details-tab" href="{{ route('artists.show',$artist['user_id']) }}" class="inline-block py-2 px-4 text-gray-600 hover:text-indigo-800 font-semibold" onclick="showTab('user-details')">Details</a>
                </li>
                <li class="mr-2">
                    <a href="{{ route('artists.show-music',$artist['user_id']) }}" id="music-tab" class="inline-block py-2 px-4 text-indigo-600 hover:text-indigo-800 font-semibold" onclick="showTab('music')">Music</a>
                </li>
            </ul>
        </div>

        <div id="music" class="tab-content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <div class="flex justify-between mt-4 mb-4">
                        <form method="GET" action="{{ url()->current() }}" class="flex w-full md:w-1/3">
                            <input type="text" name="search" class="w-full p-2 border border-gray-300 rounded-lg" placeholder="Search music..." value="{{ request()->get('search') }}">
                            <button type="submit" class="ml-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Search</button>
                        </form>
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
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @include('partials.pagination')

                </div>
            </div>
        </div>

    </div>
</div>


@endsection
