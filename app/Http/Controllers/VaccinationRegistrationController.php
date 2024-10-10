<?php

namespace App\Http\Controllers;

use App\Models\VaccinationRegistration;
use App\Models\VaccineCenter;
use Illuminate\Http\Request;

class VaccinationRegistrationController extends Controller
{

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

        VaccinationRegistration::create([
            'name' => $request->name,
            'email' => $request->email,
            'nid' => $request->nid,
            'mobile_number' => $request->mobile_number,
            'vaccine_center_id' => $request->vaccine_center,
        ]);

        return redirect()->route('home')->with('success', 'You have registered successfully!');
    }

}
