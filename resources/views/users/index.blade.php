@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="container mx-auto py-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <h1 class="text-2xl font-bold text-indigo-600">Users</h1>

        <div class="flex justify-end mt-4 mb-4">
            <form method="GET" action="{{ url()->current() }}" class="flex w-full md:w-1/3">
                <input type="text" name="search" class="w-full p-2 border border-gray-300 rounded-lg" placeholder="Search users..." value="{{ request()->get('search') }}">
                <button type="submit" class="ml-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Search</button>
            </form>
        </div>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-indigo-100 text-indigo-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Name</th>
                            <th class="py-3 px-6 text-left">Email</th>
                            <th class="py-3 px-6 text-left">Phone</th>
                            <th class="py-3 px-6 text-left">Gender</th>
                            <th class="py-3 px-6 text-left">Role</th>
                        </tr>
                    </thead>
                    <tbody id="usersTable" class="text-gray-600 text-sm">
                        @foreach($users as $user)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-6 text-left">{{ $user['first_name'] }} {{ $user['last_name'] }}</td>
                                <td class="py-3 px-6 text-left">{{ $user['email'] }}</td>
                                <td class="py-3 px-6 text-left">{{ $user['phone'] }}</td>
                                <td class="py-3 px-6 text-left">
                                    @if($user['gender'] === 'm')
                                        Male
                                    @elseif($user['gender'] === 'f')
                                        Female
                                    @endif
                                </td>
                                <td class="py-3 px-6 text-left">{{ $user['role'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @include('partials.pagination')
    </div>
</div>
@endsection
