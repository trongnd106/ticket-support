<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    @hasrole('user')
    <div class="mb-6 flex justify-center">
        <a href="{{ route('tickets.create') }}" class="mt-6 px-4 py-4 text-lg font-semibold text-black bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 rounded-lg shadow-md transform transition duration-200 ease-in-out hover:scale-105">
            Create new ticket
        </a>
    </div>
    @endhasrole('user')
    
    <div class="px-6 pt-6 mb-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        <div class="flex items-center rounded-lg bg-white shadow-xs">
            <a href="{{ route('tickets.index') }}" class="block flex w-full p-4">
                <div class="mr-4 h-full rounded-full bg-orange-100 p-3 text-orange-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600">
                        Total tickets
                    </p>
                    <p class="text-lg font-semibold text-gray-700">
                        {{ $totalTickets }}
                    </p>
                </div>
            </a>
        </div>

        <div class="flex items-center rounded-lg bg-white shadow-xs">
            @role('user')
            <a href="{{ route('tickets.index', ['status' => 'open']) }}" class="block flex w-full p-4">
                <div class="mr-4 h-full rounded-full bg-green-100 p-3 text-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600">
                        Open tickets
                    </p>
                    <p class="text-lg font-semibold text-gray-700">
                        0
                    </p>
                </div>
            </a>
            @endrole
            @role('admin')
            <a href="{{ route('agents.index') }}" class="block flex w-full p-4">
                <div class="mr-4 h-full rounded-full bg-green-100 p-3 text-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 14.25c0-1.66-1.34-3-3-3s-3 1.34-3 3 1.34 3 3 3 3-1.34 3-3zm-5.25 0c0-1.66-1.34-3-3-3s-3 1.34-3 3 1.34 3 3 3 3-1.34 3-3zm-10.5 0c0-1.66-1.34-3-3-3s-3 1.34-3 3 1.34 3 3 3 3-1.34 3-3zm2.5 2.25c0 1.38 1.12 2.5 2.5 2.5h10c1.38 0 2.5-1.12 2.5-2.5v-6.75c0-1.38-1.12-2.5-2.5-2.5h-10c-1.38 0-2.5 1.12-2.5 2.5v6.75z" />
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600">
                        Total agents
                    </p>
                    <p class="text-lg font-semibold text-gray-700">
                        {{ $agents }}
                    </p>
                </div>
            </a>
            @endrole    
        </div>

        <div class="flex items-center rounded-lg bg-white shadow-xs">
            @role('user')
            <a href="{{ route('tickets.index', ['status' => 'closed']) }}" class="block flex w-full p-4">
                <div class="mr-4 h-full rounded-full bg-blue-100 p-3 text-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600">
                        Closed tickets
                    </p>
                    <p class="text-lg font-semibold text-gray-700">
                        0
                    </p>
                </div>
            </a>
            @endrole
            @role('admin')
            <a href="{{ route('tickets.index', ['status' => 'closed']) }}" class="block flex w-full p-4">
                <div class="mr-4 h-full rounded-full bg-blue-100 p-3 text-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600">
                        Total users
                    </p>
                    <p class="text-lg font-semibold text-gray-700">
                        0
                    </p>
                </div>
            </a>
            @endrole('admin')
        </div>
    </div>
</x-app-layout>

