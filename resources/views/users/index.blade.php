<x-app-layout>
    <x-slot name="header">
        {{ __('Our customers') }}
    </x-slot>

    <div class="ml-6 mb-4 flex justify-between">        
        <div class="mb-4 pt-4">
            <form action="{{ route('users.index') }}" method="GET" class="flex items-center">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by user email" class="px-6 block w-full rounded-md border-gray-300 shadow-sm focus-within:text-primary-600 focus:border-primary-300 focus:ring-primary-200 focus:ring focus:ring-opacity-50 px-4 py-2" />
                <button type="submit" class="ml-2 bg-purple-600 text-grey px-4 py-2 rounded-md hover:bg-purple-700 focus:outline-none">Search</button>
            </form>
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
                            <th class="px-6 py-4 text-center border border-gray-300">Total tickets</th>
                            <th class="px-6 py-4 text-center border border-gray-300">Un-resolved</th>
                            @role('admin')
                            <th class="px-6 py-4 text-center border border-gray-300">Action</th>
                            @endrole
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        @forelse($usersWithTicketCount as $user)
                            <tr class="text-gray-700">
                                <td class="px-6 py-4 text-sm text-center border border-gray-300">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-4 py-2 text-sm text-center border border-gray-300">
                                    {{ $user->name }}
                                </td>
                                <td class="px-4 py-2 text-sm text-center border border-gray-300">
                                    {{ $user->email }}
                                </td>
                                <td class="px-4 py-2 text-sm text-center border border-gray-300">
                                    {{ $user->ticket_count }}
                                </td>
                                <td class="px-4 py-2 text-sm text-center border border-gray-300">
                                    {{ $user->unresolved_count }}
                                </td>
                                @role('admin')
                                <td class="px-4 py-2 text-sm text-center border border-gray-300">
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <x-primary-button>
                                            Delete
                                        </x-primary-button>
                                    </form>
                                </td>
                                @endrole
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
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
