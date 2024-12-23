<x-app-layout>
    <x-slot name="header">
        {{ __('Categories') }}
    </x-slot>

    <div class="px-6 pt-4 flex justify-between items-center mb-4">
        <form action="{{ route('categories.store') }}" method="POST" class="flex items-center space-x-2">
            @csrf
            <input 
                type="text" 
                name="name" 
                placeholder="Enter category" 
                class="px-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-purple-200"
                required>
            <x-primary-button>{{ __('Create') }}</x-primary-button>
        </form>

        <a class="px-4 py-2 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none focus:ring active:bg-purple-600">
            {{ __('Create') }}
        </a>
    </div>

    <div class="p-4 bg-white rounded-lg shadow-xs">
        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-3/4 mx-auto table-fixed whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase bg-gray-50 border-b">
                            <th class="px-4 py-3 border-r border-gray-300">Count</th>
                            <th class="px-4 py-3 border-r border-gray-300">Name</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        @forelse($categories as $category)
                            <tr class="text-gray-700 text-center">
                                <td class="px-4 py-3 text-sm border-r border-gray-300">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-4 py-3 text-sm border-r border-gray-300">
                                    {{ $category->name }}
                                </td>
                                <td class="px-4 py-3 space-x-2">
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <x-primary-button>
                                            Delete
                                        </x-primary-button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-3 text-center" colspan="3">No categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t bg-gray-50 sm:grid-cols-9">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
