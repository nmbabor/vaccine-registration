<?php

namespace App\Http\Controllers;

use App\Jobs\ScheduleVaccinationJob;
use App\Models\VaccinationRegistration;
use App\Models\VaccineCenter;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VaccinationRegistrationController extends Controller
{

    /**
     * Show the welcome page and status input.
     */
    public function index()
    {
        return view('welcome');
    }
    
    /**
     * Show the form for vaccine registration.
     */
    public function create()
    {
        $vaccine_centers = VaccineCenter::all();
        return view('register', compact('vaccine_centers'));
    }

    /**
     * Store a newly register user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.VaccinationRegistration::class],
            'nid' => ['required', 'integer', 'min:10', 'unique:'.VaccinationRegistration::class],
            'mobile_number' => ['required', 'size:11', 'regex:/^01[0-9]{9}$/'],
            'vaccine_center' => ['required', 'integer', 'exists:vaccine_centers,id'],
        ]);

        $registration = VaccinationRegistration::create([
            'name' => $request->name,
            'email' => $request->email,
            'nid' => $request->nid,
            'mobile_number' => $request->mobile_number,
            'vaccine_center_id' => $request->vaccine_center,
            // scheduled_date will be null initially
        ]);

        // Dispatch the scheduling job in the background
        ScheduleVaccinationJob::dispatch($registration);

        return redirect()->route('home')->with('success', 'You have registered successfully!');
    }

    /**
     * Check Vaccination Status.
     */

    public function checkVaccinationStatus(Request $request)
    {
        // Validate the NID input from the request
        $request->validate([
            'nid' => ['required', 'integer', 'min:10'],
        ]);

        $nid = $request->input('nid');
        
        // Try to find the registration record for the given NID
        $registration = VaccinationRegistration::where('nid', $nid)->first();

        // If no registration is found, return "Not registered"
        if (!$registration) {
            return response()->json([
                'status' => 'Not registered',
                'message' => 'You are not registered. Please register for the vaccine.',
                'register_url' => route('register'),
            ]);
        }

        // If the scheduled date is null, return "Not scheduled"
        if (is_null($registration->scheduled_date)) {
            return response()->json([
                'status' => 'Not scheduled',
                'message' => 'You are registered but not yet scheduled for the vaccine.',
            ]);
        }

        // Check if the scheduled date has passed (is before today)
        $today = Carbon::today();
        if ($registration->scheduled_date < $today) {
            return response()->json([
                'status' => 'Vaccinated',
                'message' => 'You are already vaccinated.',
            ]);
        }

        // If the scheduled date is in the future or today, return "Scheduled"
        return response()->json([
            'status' => 'Scheduled',
            'message' => 'Your vaccination is scheduled for "' . $registration->scheduled_date . '".',
        ]);
    }

}
