<x-app-layout>
    <x-slot name="header">
        <h1 class="text-center" style="font-size: 20px;">{{ __('Create new agent') }}</h1>
    </x-slot>

    <div class="flex justify-center mt-8">
        <div class="container max-w-2xl">
            <div class="w-full max-w-lg rounded-lg bg-white p-6 mt-6 shadow-md">
                <form action="{{ route('agents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input type="text"
                                    id="name"
                                    name="name"
                                    class="block w-full"
                                    value="{{ old('name') }}"
                                    required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input type="email"
                                    id="email"
                                    name="email"
                                    class="block w-full"
                                    value="{{ old('email') }}"
                                    required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="phone" :value="__('Phone')" />
                        <x-text-input type="text"
                                    id="phone"
                                    name="phone"
                                    class="block w-full"
                                    value="{{ old('phone') }}"
                                    required />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input type="password"
                                    id="password"
                                    name="password"
                                    class="block w-full"
                                    value="{{ old('password') }}"
                                    required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    class="block w-full"
                                    value="{{ old('password_confirmation') }}"
                                    required />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="status" :value="__('Status')" />
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus-within:text-primary-600 focus:border-primary-300 focus:ring-primary-200 focus:ring focus:ring-opacity-50">
                            <option value="busy" @selected(old('status') == 'busy')>{{ __('Busy') }}</option>
                            <option value="free" @selected(old('status') == 'free')>{{ __('Free') }}</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-primary-button>
                            {{ __('Submit') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
