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
        <form id="check-status-form" class="mt-6">
            @csrf
            <!-- NID Input -->
            <div>
                <x-input-label for="nid" :value="__('Enter Your NID')" />
                <x-text-input id="nid" class="block mt-1 w-full" type="text" name="nid" 
                  pattern="[0-9]{10,}" 
                  title="Please enter a valid NID with at least 10 digits."
                  required autofocus />
                <x-input-error :messages="$errors->get('nid')" class="mt-2" />
                <span id="error-nid" class="text-red-500 hidden">Please enter a valid NID.</span>
            </div>

            <!-- Submit Button -->
            <div class="mt-3 flex">
                <x-primary-button type="button" id="check-status-button">
                    {{ __('Check Status') }}
                </x-primary-button>
            </div>
        </form>
        <!-- Response Section -->
        <div id="status-result" class="hidden mt-2">
            <h3>Status: <b id="status-text"></b></h3>
            <p id="status-message" class="mb-2"></p>
            <a href="#" id="register-link" class="hidden href">Register here</a>
        </div>
    </div>
    <script>
       document.addEventListener('DOMContentLoaded', () => {
            // Handle form submission
            document.querySelector('#check-status-button').addEventListener('click', async () => {
                const nid = document.querySelector('#nid').value;
                const errorNid = document.querySelector('#error-nid');
                const statusResult = document.querySelector('#status-result');
                const statusText = document.querySelector('#status-text');
                const statusMessage = document.querySelector('#status-message');
                const registerLink = document.querySelector('#register-link');

                // Clear previous messages
                errorNid.classList.add('hidden');
                statusResult.classList.add('hidden');

                // Validate NID (must be at least 10 digits)
                if (!/^\d{10,}$/.test(nid)) {
                    errorNid.classList.remove('hidden');
                    return;
                }

                try {
                    // Send the AJAX request using fetch
                    const response = await fetch('{{ route("check-vaccination-status") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify({ nid }),
                    });

                    if (!response.ok) throw new Error('Network response was not ok');

                    const data = await response.json();

                    // Display the result
                    statusResult.classList.remove('hidden');
                    statusText.textContent = data.status;
                    statusMessage.textContent = data.message;

                    // Show the registration link if available
                    if (data.register_url) {
                        registerLink.setAttribute('href', data.register_url);
                        registerLink.classList.remove('hidden');
                    } else {
                        registerLink.classList.add('hidden');
                    }
                } catch (error) {
                    alert('Something went wrong. Please try again.');
                }
            });
        });

    </script>
</x-guest-layout>
