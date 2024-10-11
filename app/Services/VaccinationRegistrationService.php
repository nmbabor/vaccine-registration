<?php

namespace App\Services;

use App\Jobs\ScheduleVaccinationJob;
use App\Models\VaccinationRegistration;
use Carbon\Carbon;

class VaccinationRegistrationService
{
    /**
     * Handle user registration.
     */
    public function register(array $data)
    {
        // Create the registration
        $registration = VaccinationRegistration::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'nid' => $data['nid'],
            'mobile_number' => $data['mobile_number'],
            'vaccine_center_id' => $data['vaccine_center_id'],
            // scheduled_date will be null initially
        ]);

        // Dispatch the scheduling job to run in the background
        ScheduleVaccinationJob::dispatch($registration);

        return $registration;
    }

    /**
     * Check the vaccination status by NID.
     */
    public function checkStatusByNid($nid)
    {
        // Try to find the registration by NID
        $registration = VaccinationRegistration::where('nid', $nid)->first();

        // If no registration is found
        if (!$registration) {
            return [
                'status' => 'Not registered',
                'message' => 'You are not registered. Please register for the vaccine.',
                'register_url' => route('register'),
            ];
        }

        // If the scheduled date is null
        if (is_null($registration->scheduled_date)) {
            return [
                'status' => 'Not scheduled',
                'message' => 'You are registered but not yet scheduled for the vaccine.',
            ];
        }

        // Check if the scheduled date has passed
        $today = Carbon::today();
        if ($registration->scheduled_date < $today) {
            return [
                'status' => 'Vaccinated',
                'message' => 'You are already vaccinated.',
            ];
        }

        // If the scheduled date is in the future or today
        return [
            'status' => 'Scheduled',
            'message' => 'Your vaccination is scheduled for "' . $registration->scheduled_date . '".',
        ];
    }
}
