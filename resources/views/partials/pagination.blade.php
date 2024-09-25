@if($pagination['total'] > 0)

    <div class="px-6 py-4 bg-gray-50 flex justify-between items-center">
        <div>
            Showing {{ $pagination['from'] }} to {{ $pagination['to'] }} of {{ $pagination['total'] }} results
        </div>
        @if($pagination['last_page'] > 1)
            <div class="flex space-x-2">
                @php
                    $queryParams = request()->except('page');
                    $queryString = http_build_query($queryParams);
                    $queryString = $queryString ? '&' . $queryString : '';
                @endphp

                @if($pagination['current_page'] > 1)
                    <a href="?page={{ $pagination['current_page'] - 1 }}{{ $queryString }}" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 111.414 1.414L9.414 10l3.293 3.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif

                <a href="?page=1{{ $queryString }}" class="px-4 py-2 {{ $pagination['current_page'] == 1 ? 'bg-indigo-700' : 'bg-indigo-500' }} text-white rounded-lg hover:bg-indigo-600">
                    1
                </a>

                @if($pagination['current_page'] > 3)
                    <span class="px-4 py-2 text-gray-500">...</span>
                @endif

                @for ($page = max(2, $pagination['current_page'] - 1); $page <= min($pagination['current_page'] + 1, $pagination['last_page'] - 1); $page++)
                    <a href="?page={{ $page }}{{ $queryString }}" class="px-4 py-2 {{ $pagination['current_page'] == $page ? 'bg-indigo-700' : 'bg-indigo-500' }} text-white rounded-lg hover:bg-indigo-600">
                        {{ $page }}
                    </a>
                @endfor

                @if($pagination['current_page'] < $pagination['last_page'] - 2)
                    <span class="px-4 py-2 text-gray-500">...</span>
                @endif

                @if($pagination['last_page'] > 1)
                    <a href="?page={{ $pagination['last_page'] }}{{ $queryString }}" class="px-4 py-2 {{ $pagination['current_page'] == $pagination['last_page'] ? 'bg-indigo-700' : 'bg-indigo-500' }} text-white rounded-lg hover:bg-indigo-600">
                        {{ $pagination['last_page'] }}
                    </a>
                @endif

                @if($pagination['current_page'] < $pagination['last_page'])
                    <a href="?page={{ $pagination['current_page'] + 1 }}{{ $queryString }}" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L10.586 10 7.293 6.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif
            </div>
        @endif
    </div>
@else
    <div>No records found. </div>
@endif
