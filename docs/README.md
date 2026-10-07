# KJobs backend

This folder contains the backend-specific project notes and startup guidance.

## Stack

- Laravel 12
- PHP 8.2
- Sanctum
- SQLite by default
- l5-swagger

## Local setup

From `kjobs-backend`:

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

- API origin: `http://127.0.0.1:8000`
- Routes live under `/api`
- Health check: `/up`
- Swagger UI is served from the OpenAPI docs output

## Implementation notes

- Controllers stay thin.
- Validation is handled through Form Requests.
- Output is returned with API Resources and the `ApiResponse` envelope.
- Role checks use `User::isAdmin()`, `isEmployer()`, and `isUser()`.
- Employer registration creates the user and the employer profile in one transaction.
- Auth endpoints are protected with Sanctum.

## Current state

The backend is real for authentication, current user access, logout, and the admin user list. The job listing, application, employer management, and dashboard APIs are still pending.
