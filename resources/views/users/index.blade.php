@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="container mx-auto py-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <h1 class="text-2xl font-bold text-indigo-600">Users</h1>

        <div class="flex justify-between mt-4 mb-4">
            <form method="GET" action="{{ url()->current() }}" class="flex w-full md:w-1/3">
                <input type="text" name="search" class="w-full p-2 border border-gray-300 rounded-lg" placeholder="Search users..." value="{{ request()->get('search') }}">
                <button type="submit" class="ml-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Search</button>
            </form>

            <a href="{{ route('users.create') }}" class="ml-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Create
            </a>
        </div>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-indigo-100 text-indigo-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">SN</th>
                            <th class="py-3 px-6 text-left">Name</th>
                            <th class="py-3 px-6 text-left">Email</th>
                            <th class="py-3 px-6 text-left">Phone</th>
                            <th class="py-3 px-6 text-left">Gender</th>
                            <th class="py-3 px-6 text-left">Role</th>
                            <th class="py-3 px-6 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTable" class="text-gray-600 text-sm">
                        @foreach($users as $user)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-6 text-left">
                                    {{ ($pagination['current_page'] - 1) * $pagination['page_size'] + $loop->index + 1 }}
                                </td>
                                <td class="py-3 px-6 text-left">{{ $user['first_name'] }} {{ $user['last_name'] }}</td>
                                <td class="py-3 px-6 text-left">{{ $user['email'] }}</td>
                                <td class="py-3 px-6 text-left">{{ $user['phone'] }}</td>
                                <td class="py-3 px-6 text-left">
                                    @if($user['gender'] === 'm')
                                        Male
                                    @elseif($user['gender'] === 'f')
                                        Female
                                    @else
                                        Other
                                    @endif
                                </td>
                                <td class="py-3 px-6 text-left">{{ $user['role'] }}</td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex justify-center items-center space-x-4">
                                        <a href="{{ route('users.show', $user['id']) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1.5 12c2.5 4 6.5 7 10.5 7s8-3 10.5-7c-2.5-4-6.5-7-10.5-7S4 8 1.5 12z"/>
                                                <circle cx="12" cy="12" r="3" fill="currentColor" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('users.edit', $user['id']) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.414 3a2 2 0 012.828 0l1.758 1.758a2 2 0 010 2.828l-9.9 9.9a2 2 0 01-.707.414l-3.543 1.414a1 1 0 01-1.272-1.272l1.414-3.543a2 2 0 01.414-.707l9.9-9.9zM14 7l3 3m-6.586 6.586L7 17l.707-.707m-1.414-1.414L7 17" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('users.destroy', $user['id']) }}" method="POST" onsubmit="return confirmDelete()">
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
        return confirm('Are you sure you want to delete this user?');
    }
</script>
@endsection
