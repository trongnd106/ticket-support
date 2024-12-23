<x-app-layout>
    <x-slot name="header">
        {{ __('All tickets') }}
    </x-slot>

    <div class="mb-4 flex justify-between">
        <div class="flex space-x-2 pt-4">
            <!-- Filters: Status, Priority, Category, Label -->
            <select class="block w-full rounded-md border-gray-300 shadow-sm focus-within:text-primary-600 focus:border-primary-300 focus:ring-primary-200 focus:ring focus:ring-opacity-50" name="status" id="status" onchange="changeFilter('status', this)">
                <option>STATUS</option>
                <option @selected(request('status') === 'pending') value="pending">Pending</option>
                <option @selected(request('status') === 'processing') value="processing">Processing</option>
                <option @selected(request('status') === 'resolved') value="resolved">Resolved</option>
            </select>

            <select class="block w-full rounded-md border-gray-300 shadow-sm focus-within:text-primary-600 focus:border-primary-300 focus:ring-primary-200 focus:ring focus:ring-opacity-50" name="priority" id="priority" onchange="changeFilter('priority', this)">
                <option>PRIORITY</option>
                <option @selected(request('priority') === 'low') value="low">Low</option>
                <option @selected(request('priority') === 'medium') value="medium">Medium</option>
                <option @selected(request('priority') === 'high') value="high">High</option>
            </select>

            <select class="block w-full rounded-md border-gray-300 shadow-sm focus-within:text-primary-600 focus:border-primary-300 focus:ring-primary-200 focus:ring focus:ring-opacity-50" name="category" id="category" onchange="changeFilter('category', this)">
                <option>CATEGORY</option>
                @foreach(\App\Models\Category::pluck('name', 'id') as $id => $name)
                    <option value="{{ $name }}" @selected($name == request('category'))>{{ $name }}</option>
                @endforeach
            </select>

            <select class="block w-full rounded-md border-gray-300 shadow-sm focus-within:text-primary-600 focus:border-primary-300 focus:ring-primary-200 focus:ring focus:ring-opacity-50" name="label" id="label" onchange="changeFilter('label', this)">
                <option>LABEL</option>
                @foreach(\App\Models\Label::pluck('name', 'id') as $id => $name)
                    <option value="{{ $name }}" @selected($name == request('label'))>{{ $name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="rounded-lg bg-white p-4 shadow-xs">
        <div class="mb-8 w-full overflow-hidden rounded-lg border shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full table-auto border-collapse border border-gray-300">
                    <thead>
                        <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th class="px-6 py-4 text-center border border-gray-300">Count</th>
                            <th class="px-6 py-4 text-center border border-gray-300">Title</th>
                            <th class="px-6 py-4 text-center border border-gray-300">Author</th>
                            <th class="px-6 py-4 text-center border border-gray-300">Status</th>
                            <th class="px-6 py-4 text-center border border-gray-300">Priority</th>
                            <th class="px-6 py-4 text-center border border-gray-300">Categories</th>
                            <th class="px-6 py-4 text-center border border-gray-300">Labels</th>
                            <th class="px-6 py-4 text-center border border-gray-300">Created at</th>
                            <th class="px-6 py-4 text-center border border-gray-300">Updated at</th>
                            <th class="px-6 py-4 text-center border border-gray-300">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        @forelse($tickets as $ticket)
                            <tr class="text-gray-700">
                                <td class="px-6 py-4 text-sm text-center border border-gray-300">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 text-sm text-center border border-gray-300">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="hover:underline">{{ $ticket->title }}</a>
                                </td>
                                <td class="px-4 py-2 text-sm text-center border border-gray-300">
                                    {{ $ticket->creator?->name }}
                                </td>
                                <td class="px-4 py-2 text-sm text-center border border-gray-300">
                                    {{ $ticket->status }}
                                </td>
                                <td class="px-4 py-2 text-sm text-center border border-gray-300">
                                    {{ $ticket->priority }}
                                </td>
                                <td class="px-4 py-2 text-sm text-center border border-gray-300">
                                    @foreach($ticket->categories as $category)
                                        <span class="rounded-full bg-gray-50 px-2 py-2">{{ $category->name }}</span>
                                    @endforeach
                                </td>
                                <td class="px-4 py-2 text-sm text-center border border-gray-300">
                                    @foreach($ticket->labels as $label)
                                        <span class="rounded-full bg-gray-50 px-2 py-2">{{ $label->name }}</span>
                                    @endforeach
                                </td>
                                <td class="px-4 py-2 text-sm text-center border border-gray-300">
                                    {{ \Carbon\Carbon::parse($ticket->created_at)->format('H:i:s - d/m/Y') }}
                                </td>
                                <td class="px-4 py-2 text-sm text-center border border-gray-300">
                                    {{ \Carbon\Carbon::parse($ticket->updated_at)->format('H:i:s - d/m/Y') }}
                                </td>
                                <td class="px-4 py-2 space-x-2 text-center border border-gray-300">
                                    @role('agent')
                                        <a href="{{ route('tickets.edit', $ticket) }}">
                                            @csrf 
                                            <x-primary-button>
                                                Edit
                                            </x-primary-button>
                                        </a>
                                    @endrole
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-4 text-center" colspan="7">No tickets found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tickets->hasPages())
                <div class="border-t bg-gray-50 px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 sm:grid-cols-9">
                    {{ $tickets->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
    function changeFilter(filter, select) {
        const value = select.value;
        const urlParams = new URLSearchParams(window.location.search);

        if (value === filter.toUpperCase()) {
            urlParams.delete(filter);
        } else {
            urlParams.set(filter, value); 
        }

        window.location.search = urlParams.toString();
    }
    </script>
</x-app-layout>
