<div class="w-64 bg-white h-full fixed inset-y-0 left-0 transform transition-transform duration-300 sm:translate-x-0 sm:block">
    <div class="shrink-0 flex items-center h-16 px-6">
        <a href="/" class="text-xl font-semibold text-indigo-600">Artist Management</a>
    </div>
    <ul class="mt-4">
        <li class="px-6 py-2 hover:bg-gray-200">
            <a href="{{ route('home') }}" class="text-gray-700">Dashboard</a>
        </li>
        <li class="px-6 py-2 hover:bg-gray-200">
            <a href="{{ route('users.index') }}" class="text-gray-700">Users</a>
        </li>
        <li class="px-6 py-2 hover:bg-gray-200">
            <a href="#" class="text-gray-700">Artists</a>
        </li>
    </ul>
</div>
