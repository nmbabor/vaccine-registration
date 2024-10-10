<x-guest-layout>
    <!-- Welcome Note -->
    <div class="text-center mt-4">
        <h1 class="text-2xl font-bold text-gray-800">Welcome to COVID Vaccine Hub</h1>
        <p class="text-lg text-gray-600 mt-4">
            Register today to secure your vaccination if you haven't already!
        </p>

        <!-- Register Button -->
        <div class="mt-6">
            <a href="{{ route('register') }}" class="px-4 py-2 bg-red-600 border rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Register for Vaccine') }}
            </a>
        </div>
    </div>

    <!-- Check Vaccine Status Form -->
    <div class="pt-4 max-w-lg mx-auto">
        <h2 class="text-xl font-semibold text-gray-800 text-center mt-4">OR</h2>
        <h2 class="text-xl font-semibold text-gray-800 text-center mt-4">Check Vaccine Status</h2>
        <form method="GET" action="{{ route('login') }}" class="mt-6">
            @csrf

            <!-- NID Input -->
            <div>
                <x-input-label for="nid" :value="__('Enter Your NID')" />
                <x-text-input id="nid" class="block mt-1 w-full" type="text" name="nid" 
                  pattern="[0-9]{13,}" 
                  title="Please enter a valid NID with at least 13 digits."
                  required autofocus />
                <x-input-error :messages="$errors->get('nid')" class="mt-2" />
            </div>

            <!-- Submit Button -->
            <div class="mt-6 flex">
                <x-primary-button class="">
                    {{ __('Check Status') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
