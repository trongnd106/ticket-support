<x-app-layout>
    <x-slot name="header">
        {{ __('System agents') }}
    </x-slot>

    <div class="mb-4 flex justify-between">
        <a class="rounded-lg border border-transparent bg-purple-600 px-4 py-2 text-center text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-purple-700 focus:outline-none focus:ring active:bg-purple-600" href="{{ route('tickets.create') }}">
            {{ __('Create') }}
        </a>
        <div class="flex space-x-2 pt-4">
            <select style="margin-left: 8px;" class="block w-full rounded-md border-gray-300 shadow-sm focus-within:text-primary-600 focus:border-primary-300 focus:ring-primary-200 focus:ring focus:ring-opacity-50" name="status" id="status" onchange="changeFilter('status', this)">
                <option>STATUS</option>
                <option @selected(request('status') === 'busy') value="busy">Busy</option>
                <option @selected(request('status') === 'free') value="free">Free</option>
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
                            <th class="px-6 py-4 text-center border border-gray-300">Name</th>
                            <th class="px-6 py-4 text-center border border-gray-300">Email</th>
                            <th class="px-6 py-4 text-center border border-gray-300">Phone</th>
                            <th class="px-6 py-4 text-center border border-gray-300">Total tickets</th>
                            <th class="px-6 py-4 text-center border border-gray-300">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        @forelse($agentsWithTicketCount as $agent)
                            <tr class="text-gray-700">
                                <td class="px-6 py-4 text-sm text-center border border-gray-300">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-4 py-2 text-sm text-center border border-gray-300">
                                    {{ $agent->name }}
                                </td>
                                <td class="px-4 py-2 text-sm text-center border border-gray-300">
                                    {{ $agent->email }}
                                </td>
                                <td class="px-4 py-2 text-sm text-center border border-gray-300">
                                    {{ $agent->phone }}
                                </td>
                                <td class="px-4 py-2 text-sm text-center border border-gray-300">
                                    {{ $agent->ticket_count }}
                                </td>
                                <td class="px-4 py-2 text-sm text-center border border-gray-300">
                                    {{ $agent->status }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No agents found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function changeFilter(filter, select) {
            const value = select.value;
            const url = new URL(window.location);
            if (value) {
                url.searchParams.set(filter, value); 
            } else {
                url.searchParams.delete(filter); 
            }
            window.location.href = url.toString(); 
        }
    </script>
</x-app-layout>