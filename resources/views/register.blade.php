<x-guest-layout>
    <!-- Registration Header -->
    <a href="/" class="font-bold text-gray-800"> 
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left inline-block" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/>
        </svg>
        {{ __('Back') }} 
    </a>
    <div class="text-center mt-4">
        <h1 class="text-2xl font-bold text-gray-800">Register for COVID-19 Vaccine</h1>
        <p class="text-lg text-gray-600 mt-4">
            Fill in your details to secure your vaccination appointment.
        </p>
    </div>

    <!-- Registration Form -->
    <form method="POST" action="{{ route('register') }}" class="mt-8 max-w-lg mx-auto">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
         <!-- Mobile Number -->
        <div class="mt-4">
            <x-input-label for="mobile_number" :value="__('Mobile Number')" />
            <x-text-input id="mobile_number" class="block mt-1 w-full" type="tel" name="mobile_number" 
           pattern="^01[0-9]{9}$" title="Please enter a valid 11-digit mobile number starting with 01." :value="old('mobile_number')" required />
            <x-input-error :messages="$errors->get('mobile_number')" class="mt-2" />
        </div>

        <!-- NID (National ID) -->
        <div class="mt-4">
            <x-input-label for="nid" :value="__('National ID (NID)')" />
            <x-text-input id="nid" class="block mt-1 w-full" type="text" name="nid" pattern="[0-9]{10,}" 
                          title="Please enter a valid NID with at least 10 digits." :value="old('nid')" required />
            <x-input-error :messages="$errors->get('nid')" class="mt-2" />
        </div>

        <!-- Vaccine Center -->
        <div class="mt-4">
            <x-input-label for="vaccine_center_id" :value="__('Select Vaccine Center')" />
            <select id="vaccine_center_id" name="vaccine_center_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                <option value="" disabled selected>{{ __('Choose a center') }}</option>
                @foreach ($vaccine_centers as $center)
                    <option value="{{ $center->id }}" {{ old('vaccine_center_id') == $center->id ? 'selected' : ''}}>{{ $center->name }} (Limit: {{ $center->daily_limit }})</option>
                @endforeach
               
            </select>
            <x-input-error :messages="$errors->get('vaccine_center_id')" class="mt-2" />
        </div>

        <!-- Register Button -->
        <div class="mt-6">
            <x-danger-button>
                {{ __('Register') }}
            </x-danger-button>
        </div>
    </form>
</x-guest-layout>
