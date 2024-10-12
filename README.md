
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
