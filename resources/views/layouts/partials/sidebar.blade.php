<div class="w-64 bg-white h-full fixed inset-y-0 left-0 transform transition-transform duration-300 sm:translate-x-0 sm:block">
    <div class="shrink-0 flex items-center h-16 px-6">
        <a href="/" class="text-xl font-semibold text-indigo-600">Artist Management</a>
    </div>
    <ul class="mt-4">
        <li class="px-6 py-2 hover:bg-gray-200 {{ request()->is('/') ? 'bg-indigo-200 text-indigo-600' : 'text-gray-700' }}">
            <a href="{{ route('home') }}">Dashboard</a>
        </li>
        @hasrole('super_admin')
        <li class="px-6 py-2 hover:bg-gray-200 {{ request()->is('users*') ? 'bg-indigo-200 text-indigo-600' : 'text-gray-700' }}">
            <a href="{{ route('users.index') }}">Users</a>
        </li>
        @endhasrole
        @hasrole('super_admin|artist_manager')
        <li class="px-6 py-2 hover:bg-gray-200 {{ request()->is('artists*') ? 'bg-indigo-200 text-indigo-600' : 'text-gray-700' }}">
            <a href="#">Artists</a>
        </li>
        @endhasrole
        @hasrole('artist')
        <li class="px-6 py-2 hover:bg-gray-200 {{ request()->is('artists*') ? 'bg-indigo-200 text-indigo-600' : 'text-gray-700' }}">
            <a href="#">My Music</a>
        </li>
        @endhasrole
    </ul>
</div>
