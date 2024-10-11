<?php

namespace Tests\Unit;

use App\Models\VaccinationRegistration;
use App\Services\VaccinationRegistrationService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VaccinationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_check_vaccination_status_not_registered()
    {
        $service = new VaccinationRegistrationService();

        // Call the checkStatusByNid method with an invalid NID
        $result = $service->checkStatusByNid('0000000000000');

        // Assert that the user is not registered
        $this->assertEquals('Not registered', $result['status']);
        $this->assertEquals('You are not registered. Please register for the vaccine.', $result['message']);
    }

    public function test_check_vaccination_status_scheduled()
    {
        // Create a registration with a scheduled date
        $registration = VaccinationRegistration::factory()->create([
            'scheduled_date' => date('Y-m-d',strtotime(Carbon::tomorrow())),
        ]);

        $service = new VaccinationRegistrationService();

        // Call the checkStatusByNid method with a valid NID
        $result = $service->checkStatusByNid($registration->nid);

        // Assert the correct response for a scheduled user
        $this->assertEquals('Scheduled', $result['status']);
        $this->assertEquals('Your vaccination is scheduled for "' . $registration->scheduled_date . '".', $result['message']);
    }

    public function test_check_vaccination_status_vaccinated()
    {
        // Create a registration with a past scheduled date
        $registration = VaccinationRegistration::factory()->create([
            'scheduled_date' => Carbon::yesterday(),
        ]);

        $service = new VaccinationRegistrationService();

        // Call the checkStatusByNid method
        $result = $service->checkStatusByNid($registration->nid);

        // Assert the correct response for a vaccinated user
        $this->assertEquals('Vaccinated', $result['status']);
        $this->assertEquals('You are already vaccinated.', $result['message']);
    }
}
