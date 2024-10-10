<?php

namespace App\Console\Commands;

use App\Mail\VaccinationScheduledMail;
use App\Models\VaccinationRegistration;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendVaccinationEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vaccination:send-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send vaccination appointment emails to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         // Get the current date and time
         $currentDateTime = Carbon::now();
        
         // Get the date for tomorrow
         $tomorrowDate = $currentDateTime->addDay();
 
         // Get all registrations that are scheduled for tomorrow
         $registrations = VaccinationRegistration::whereDate('scheduled_date', $tomorrowDate->toDateString())->get();
 
         foreach ($registrations as $registration) {
             Mail::to($registration->email)->send(new VaccinationScheduledMail($registration));
         }
 
         $this->info('Vaccination emails sent successfully!');
    }
}
