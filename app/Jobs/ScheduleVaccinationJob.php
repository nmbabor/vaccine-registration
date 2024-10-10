<?php

namespace App\Jobs;

use App\Mail\VaccinationScheduledMail;
use App\Models\VaccinationRegistration;
use App\Models\VaccineCenter;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class ScheduleVaccinationJob implements ShouldQueue
{
    use Queueable;

    public $registration;
    /**
     * Create a new job instance.
     */
    
    public function __construct(VaccinationRegistration $registration)
    {
        $this->registration = $registration;
    }
        
        /**
         * Execute the job.
         */
    public function handle(): void
    {
        // Find the vaccine center selected by the user
        $vaccineCenter = VaccineCenter::find($this->registration->vaccine_center_id);

        // Get the next available weekday date
        $scheduledDate = $this->getNextAvailableWeekday($vaccineCenter);

        // Update the user's registration with the scheduled date
        $this->registration->scheduled_date = $scheduledDate;
        $this->registration->save();

        // Send a first email notification to the user
        Mail::to($this->registration->email)->send(new VaccinationScheduledMail($this->registration));
    }
    private function getNextAvailableWeekday(VaccineCenter $center)
    {
        $capacityLimit = $center->daily_limit;
        $currentDay = Carbon::now();

        // If the current time is after 10 AM, start scheduling for the next day
        if ($currentDay->hour >= 10) {
            $currentDay->addDay();
        }

        // Loop until we find a date with availability
        while (true) {
            // Skip if the day is Friday or Saturday
            if (in_array($currentDay->dayOfWeek, [Carbon::FRIDAY, Carbon::SATURDAY])) {
                $currentDay->addDay();
                continue;
            }

            // Count scheduled vaccinations for that day
            $count = VaccinationRegistration::where('vaccine_center_id', $center->id)
                ->whereDate('scheduled_date', $currentDay->toDateString())
                ->count();

            if ($count < $capacityLimit) {
                return $currentDay;
            }

            // Move to the next day if capacity is full
            $currentDay->addDay();
        }
    }
}
