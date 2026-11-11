# Laravel 13 Secure API — Lab (Modules 5 & Lessons 1–2)

## Overview

This repository contains a **Laravel 13** secure REST API laboratory covering:

- **Module 5 — Lesson 1:** Secure API design, Sanctum personal access tokens, and token-based authentication (register, login, profile, logout).
- **Module 5 — Lesson 2:** Role-based access control (RBAC) with custom middleware.

The API is built with **Laravel 13**, **Laravel Sanctum** (Bearer tokens), **MySQL** (InnoDB), and tested with **Postman**.

## RBAC endpoints

| Endpoint | Method | Middleware | Admin | Student | No token |
|---|---|---|---|---|---|
| `/api/hello` | GET | public | — | — | — |
| `/api/register` | POST | throttle | — | — | — |
| `/api/login` | POST | throttle | — | — | — |
| `/api/profile` | GET/POST | `auth:sanctum` | 200 | 200 | 401 |
| `/api/students` | GET | `auth:sanctum` + `role:admin,student` | 200 | 200 | 401 |
| `/api/admin/dashboard` | GET | `auth:sanctum` + `admin` | 200 | 403 | 401 |
| `/api/student/dashboard` | GET | `auth:sanctum` + `role:student` | 403 | 200 | 401 |
| `/api/logout` | POST | `auth:sanctum` | — | — | — |

### Middleware

- `App\Http\Middleware\CheckRole` — variadic role middleware: accepts one or more allowed roles via `string ...$roles`; returns **401** when unauthenticated and **403** when the user's role is not allowed.
- `App\Http\Middleware\AdminMiddleware` — admin-only middleware; returns **401** when unauthenticated and **403** when the user's role is not `admin`.

### Custom middleware aliases

Registered in `bootstrap/app.php`:

```php
$middleware->alias([
    'role' => \App\Http\Middleware\CheckRole::class,
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
]);
```

### Protected routes (`routes/api.php`)

```php
Route::middleware(['auth:sanctum'])->group(function () {
    // /profile, /logout

    Route::middleware(['role:admin,student'])->get('/students', function () { /* fixed JSON array */ });

    Route::middleware(['admin'])->get('/admin/dashboard', function () { /* Welcome Admin! */ });

    Route::middleware(['role:student'])->get('/student/dashboard', function () { /* Welcome Student! */ });
});
```

## Local testing steps

1. `cp .env.example .env`
2. Configure MySQL credentials (database `demo_app_integ2`, InnoDB, `DB_HOST=127.0.0.1`, `DB_PORT=3306`).
3. `php artisan install:api`
4. `php artisan migrate`
5. `php artisan migrate` (adds `users.role` default `student`)
6. `php artisan optimize:clear`
7. `php artisan serve`
8. Test endpoints in Postman with `Authorization → Bearer Token` and `Accept: application/json`.

## Evidence screenshots

Eight Postman screenshots (secrets/tokens redacted) live in the `screenshots/` folder:

- `01-admin-login.png` — POST `/api/login` (admin) → 200
- `02-admin-allowed.png` — GET `/api/admin/dashboard` (admin) → 200
- `03-admin-denied.png` — GET `/api/student/dashboard` (admin) → 403
- `04-student-login.png` — POST `/api/login` (student) → 200
- `05-student-allowed.png` — GET `/api/student/dashboard` (student) → 200
- `06-student-denied.png` — GET `/api/admin/dashboard` (student) → 403
- `07-shared-route.png` — GET `/api/students` (student) → 200
- `08-no-token.png` — GET `/api/admin/dashboard` (no token) → 401

## Reflection

- **Authentication vs. authorization:** Authentication verifies *who* the user is (e.g., valid Sanctum Bearer token via `auth:sanctum`). Authorization decides *what* that authenticated user is allowed to do (role check via `CheckRole` / `AdminMiddleware`).
- **401 vs. 403:** A request without a token is **unauthenticated**, so Sanctum returns **401 Unauthorized**. An authenticated user whose role is not permitted for the endpoint receives **403 Forbidden**.

## Submission

- **Course:** Integrative Programming & Technologies II
- **Module:** Module 5 — Lesson 2 (RBAC) / Lesson 1 (Secure API)
- **Submission:** GitHub repository URL + screenshots
