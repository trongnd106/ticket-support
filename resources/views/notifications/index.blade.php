<x-app-layout>
    <x-slot name="header">
        {{ __('Your notifications') }}
    </x-slot>

    <div class="rounded-lg bg-white p-4 shadow-xs">
        <div class="mb-8 w-full overflow-hidden rounded-lg border shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full table-auto border-collapse border border-gray-300">
                    <thead>
                        <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th class="px-6 py-4 text-center border border-gray-300">Count</th>
                            <th class="px-6 py-4 text-center border border-gray-300">ID</th>
                            <th class="px-6 py-4 text-center border border-gray-300">Message</th>
                            <th class="px-6 py-4 text-center border border-gray-300">Sent</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        @forelse($notifications as $notification)
                            <tr class="text-gray-700">
                                <td class="px-6 py-4 text-sm text-center border border-gray-300">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 text-sm text-center border border-gray-300">
                                    {{ $notification->id }}
                                </td>
                                <td class="px-4 py-2 text-sm text-center border border-gray-300">
                                    {{ $notification->data['message'] }} with id {{ $notification->data['ticket_id'] }}
                                </td>
                                <td class="px-4 py-2 text-sm text-center border border-gray-300">
                                    {{ \Carbon\Carbon::parse($notification->created_at)->format('H:i:s - d/m/Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No notification found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
