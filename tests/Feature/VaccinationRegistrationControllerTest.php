<?php

namespace Tests\Feature;

use App\Models\VaccineCenter;
use App\Models\VaccinationRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VaccinationRegistrationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_registers_a_user_successfully()
    {
        // Arrange: Create a vaccine center
        $center = VaccineCenter::factory()->create();

        // Act: Make a POST request to the registration route
        $response = $this->post(route('register'), [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'nid' => '1234567890123',
            'mobile_number' => '01712345678',
            'vaccine_center_id' => $center->id,
        ]);

        // Assert: Check if the user is redirected to the home route with a success message
        $response->assertRedirect(route('home'))
                 ->assertSessionHas('success', 'You have registered successfully!');

        // Assert: Check if the registration was created in the database
        $this->assertDatabaseHas('vaccination_registrations', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'nid' => '1234567890123',
            'mobile_number' => '01712345678',
            'vaccine_center_id' => $center->id,
        ]);
    }

    /**
     * A basic test example.
     */
    public function test_it_fails_validation_if_required_fields_are_missing()
    {
        // Arrange: Create a vaccine center
        $center = VaccineCenter::factory()->create();

        // Act: Send a POST request with missing name
        $response = $this->post(route('register'), [
            'email' => 'johndoe@example.com',
            'nid' => '1234567890123',
            'mobile_number' => '01712345678',
            'vaccine_center_id' => $center->id,
        ]);

        // Assert: Validation errors should be returned
        $response->assertSessionHasErrors(['name']);
    }

    public function test_it_fails_if_nid_is_not_unique()
    {
        // Arrange: Create a vaccine center and a registered user
        $center = VaccineCenter::factory()->create();
        VaccinationRegistration::factory()->create([
            'nid' => '1234567890123',
        ]);

        // Act: Try to register a new user with the same NID
        $response = $this->post(route('register'), [
            'name' => 'John Doe',
            'email' => 'johndoe2@example.com',
            'nid' => '1234567890123',
            'mobile_number' => '01712345678',
            'vaccine_center_id' => $center->id,
        ]);

        // Assert: NID validation should fail
        $response->assertSessionHasErrors(['nid']);
    }
}
