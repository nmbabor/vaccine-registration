
# COVID Vaccine Registration Portal

This project is a web-based COVID vaccine registration portal built with Laravel. It allows users to register for vaccines, check their vaccination status, and receive notifications for scheduled vaccinations.

## Prerequisites

Make sure you have the following installed:

- **Docker** and **Docker Compose**
- **Git**

## Getting Started

### Clone the repository

```bash
git clone https://github.com/nmbabor/vaccine-registration.git
cd vaccine-registration
```

### Set up environment variables

1. Copy the `.env.example` file to `.env`:

   ```bash
   cp .env.example .env
   ```

2. Update the following fields in the `.env` file:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_DATABASE=laravel
   DB_USERNAME=laravel
   DB_PASSWORD=hello_secret


   # Email SMTP configuration for Gmail
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your-email@gmail.com
   MAIL_PASSWORD=your-email-password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=noreply@yourdomain.com
   MAIL_FROM_NAME="${APP_NAME}"
   ```

### Docker Setup

The project is dockerized, and the following steps will set up everything:

1. **Build and start Docker containers**:

   ```bash
   docker-compose up -d --build
   ```

   This command will build and start the following services:
   - **Laravel app** (PHP 8.2)
   - **MySQL** database
   - **Supervisor** to manage queue jobs and scheduled tasks

2. **Install dependencies**:

   Run the following command inside the running Laravel container:

   ```bash
   docker exec -it laravel_app composer install
   ```

3. **Run migrations and seed data**:

   To set up the database schema and seed initial data (e.g., vaccine centers), run:

   ```bash
   docker exec -it laravel_app php artisan migrate --seed
   ```

### Testing Setup

This project includes unit and feature tests that use an SQLite in-memory database for testing.

 **Run the tests**:

   Execute the following command to run tests inside the Docker container:

   ```bash
   docker exec -it laravel_app php artisan test
   ```

### Queue Worker and Scheduler

The project uses Laravel's queue system to process background jobs (e.g., scheduling vaccinations and sending notification emails) and the scheduler for periodic tasks.

- **Queue Worker**: Managed by **Supervisor** and automatically processes jobs like vaccination scheduling.
  
  If needed, you can manually run the queue worker:

  ```bash
  docker exec -it laravel_app php artisan queue:work
  ```

- **Scheduler**: Also managed by **Supervisor**, it runs every minute and processes tasks like sending vaccination reminder emails the night before a scheduled vaccination.

  You can manually trigger the scheduler:

  ```bash
  docker exec -it laravel_app php artisan schedule:run
  ```

### Running the Application

Once Docker containers are up and running, access the application via:

```bash
http://localhost:8000
```

You should see the registration portal homepage.

### Email Notifications

The app sends email notifications at 9 PM the night before the scheduled vaccination date. Ensure your **SMTP** settings are configured properly in the `.env` file to enable this functionality.

### Additional Commands

- **Clear cache and config**:

   If you update your environment variables or config settings, clear the cache:

   ```bash
   docker exec -it laravel_app php artisan config:clear
   docker exec -it laravel_app php artisan cache:clear
   ```

- **Run migrations**:

   If new migrations are added, run:

   ```bash
   docker exec -it laravel_app php artisan migrate
   ```

- **Run seeders**:

   If you want to seed additional data:

   ```bash
   docker exec -it laravel_app php artisan db:seed
   ```

### Shutting Down the Application

To stop and remove running Docker containers, run:

```bash
docker-compose down
```
### Future Requirement: SMS Notifications
If an additional requirement is introduced to send SMS notifications along with email notifications for the vaccine schedule, the following changes would be necessary:

1. **Integrate an SMS service provider**:
    - Choose an SMS service provider like Twilio or any other API that can send SMS messages.
    - Install the relevant package if needed (for example, for Twilio: twilio/sdk).

2. **Create an SMS notification class**:
    - In Laravel, you can use the built-in notification system to send SMS messages. Create an SMS notification class similar to the email notification.
    - Example: `php artisan make:notification VaccinationScheduledSMS`
    - This class would handle constructing and sending the SMS message through the chosen service provider.
    -  Update the VaccinationScheduledSMS Class
    - Example: 
    ```php
   class VaccinationScheduledSMS extends Notification implements ShouldQueue
   {
       use Queueable;
   
       protected $registration;
   
       /**
        * Create a new notification instance.
        */
       public function __construct($registration)
       {
           $this->registration = $registration;
       }
   
       /**
        * Get the notification's delivery channels.
        */
       public function via($notifiable)
       {
           return ['twilio'];
       }
   
       /**
        * Get the Twilio / SMS representation of the notification (if using Twilio).
        */
       public function toTwilio($notifiable)
       {
           return (new TwilioMessage)
               ->content('Your COVID-19 vaccine is scheduled for ' . $this->registration->scheduled_date . ' at ' . $this->registration->vaccineCenter->name . '.');
       }
   }
    ```
3. **Modify the `ScheduleVaccinationJob`**:
    - In the `ScheduleVaccinationJob` where the email notification is triggered first time after registration, add logic to send the SMS notification as well.
    - Example:
    ```php
    Mail::to($this->registration->email)->send(new VaccinationScheduledMail($this->registration));
   
   // Send SMS
   Notification::route('twilio', $this->registration->mobile_number)
       ->notify(new VaccinationScheduledSMS($this->registration));
   ```
4. **Modify the `SendVaccinationEmails` command**:
    - In the `SendVaccinationEmails` command where the email notification is sent, add logic to send SMS notifications as well. Ensure both notifications are queued for better performance.
    - Example:
    ```php
   foreach ($registrations as $registration) {
       try {
           // Send Email Notification
           Mail::to($registration->email)
               ->queue(new VaccinationScheduledMail($registration)); // Queue the email
       } catch (\Exception $e) {
           \Log::error('Failed to send vaccination email to: ' . $registration->email, ['error' => $e->getMessage()]);
       }
   
       try {
           // Send SMS Notification
           Notification::route('twilio', $registration->mobile_number)
               ->notify(new VaccinationScheduledSMS($registration));  // Queue the SMS
       } catch (\Exception $e) {
           \Log::error('Failed to send vaccination SMS to: ' . $registration->mobile_number, ['error' => $e->getMessage()]);
       }
   }
   ```
### Performance Optimization for User Registration and Search
In this project, I implemented a queue system to manage notifications and vaccination schedules within the registration process, significantly boosting performance and speed. To further optimize user registration and search functionality, and ensure greater scalability, several enhancements could be made to the application.

Here are some potential improvements that could be implemented with additional time:

1. **Caching Frequently Accessed Data**:
 Implement caching to store frequently accessed data, such as the list of vaccine centers or user search results.
 This would allow the system to retrieve data quickly without querying the database every time.
This reduces database load and improves response times, especially during high-traffic periods.

2. **Advanced Search with Full-Text Search Engines**:  For improved search functionality, integrate a full-text search engine like **Elasticsearch** or use **Laravel Scout** with a driver like **Algolia** or **Meilisearch**. These services offer advanced search indexing and can handle large datasets efficiently.
 This greatly enhances search performance and accuracy, especially for large datasets or multiple fields (like user names, NIDs, emails, or vaccine centers)..

3. **Testing and Monitoring Performance**:  Use tools like Laravel Telescope for real-time monitoring or Apache JMeter for load testing to evaluate system performance under different load conditions.
Monitoring helps identify performance bottlenecks, allowing you to optimize the system proactively.

