<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <span>REGISTERING PROVIDES NOTHING FOR AVERAGE USER SO DO SO ONLY WHEN INTENDING TO POST CONTENT(and for that you will need to ask for authorization)</span><br>
        <span>I repeat once more. DO NOT create account unless you've talked to admin or whomever.</span><br>
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>


        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        <!-- Contact -->
        <div class="mt-4">
            <x-input-label for="description" :value="__('Description')" />
            <br>
            <textarea rows="3" cols="40"id="description" class="block mt-1 w-full"  name="description" required autocomplete="username" placeholder="in here write any form of contact(preferably discord) in case people might want you to change a mistake that you've made "></textarea>
            {{--<x-input-error :messages="$errors->get('email')" class="mt-2" />--}}
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
