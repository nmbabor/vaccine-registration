<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVaccinationRegistrationRequest;
use App\Jobs\ScheduleVaccinationJob;
use App\Models\VaccinationRegistration;
use App\Models\VaccineCenter;
use App\Services\VaccinationRegistrationService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VaccinationRegistrationController extends Controller
{

    protected $vaccinationService;

    public function __construct(VaccinationRegistrationService $vaccinationService)
    {
        $this->vaccinationService = $vaccinationService;
    }


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
    public function store(StoreVaccinationRegistrationRequest $request)
    {
        // Handle registration through the service and validate the input using the request class
        $this->vaccinationService->register($request->validated());

        return redirect()->route('home')->with('success', 'You have registered successfully!');
    }

    /**
     * Check Vaccination Status.
     */

    public function checkVaccinationStatus(Request $request)
    {
        // Retrieve vaccination status by NID using the service
        $result = $this->vaccinationService->checkStatusByNid($request->nid);
        
        return response()->json($result);
    }

}
