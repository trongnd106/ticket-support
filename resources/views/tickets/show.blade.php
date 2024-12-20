<x-app-layout>
    <x-slot name="header">
        {{ $ticket->title }}
    </x-slot>

    <div class="mx-auto space-y-4 px-4 mt-6" style="max-width: 48rem;">
        <div class="min-w-0 rounded-lg bg-white p-4 shadow-xs mb-6">
            <p class="text-gray-600">
                {{ $ticket->description }}
            </p>
        </div>

        @if($ticket->getMedia('tickets_attachments')->count())
            <div class="min-w-0 rounded-lg bg-white p-4 shadow-xs">
                <h4 class="mb-4 font-semibold text-gray-600">
                    Attachments
                </h4>

                @foreach($ticket->getMedia('tickets_attachments') as $media)
                    <p>
                        <a href="{{ route('attachment-download', $media) }}" class="hover:underline">{{ $media->file_name }}</a>
                    </p>
                @endforeach
            </div>
        @endif

        <div class="min-w-0 rounded-lg bg-white p-4 shadow-xs space-y-4">
            <h4 class="mb-4 font-semibold text-gray-600">
                Messages
            </h4>

            <form action="{{ route('message.store', $ticket) }}" method="POST">
                @csrf

                <div>
                    <textarea id="message"
                              name="message"
                              class="mt-1 block w-full h-32 rounded-md border-gray-300 shadow-sm focus-within:text-primary-600 focus:border-primary-300 focus:ring-primary-200 focus:ring focus:ring-opacity-50"
                              required>{{ old('message') }}</textarea>
                    <x-input-error :messages="$errors->get('message')" class="mt-2" />
                </div>

                <x-primary-button class="mt-4">
                    Submit
                </x-primary-button>
            </form>

            @forelse($ticket->messages as $message)
                <p class="rounded-lg bg-gray-50 p-3">{{ $message->message }}</p>
            @empty
                <p class="text-gray-600">
                    No messages found.
                </p>
            @endforelse
        </div>
    </div>
</x-app-layout>
