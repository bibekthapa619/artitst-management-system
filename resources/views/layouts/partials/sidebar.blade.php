<div :class="{'block': open, 'hidden': !open}" class="w-64 bg-white shadow-md h-full fixed inset-y-16 left-0 transform transition-transform duration-300 sm:block">
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

<button @click="open = !open" class="fixed top-4 left-4 sm:hidden p-2 bg-white rounded-md shadow-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
    <svg class="w-6 h-6" fill="none" stroke="#4F46E5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
    </svg>
</button>