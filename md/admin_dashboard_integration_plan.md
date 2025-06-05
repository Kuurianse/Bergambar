# Admin Dashboard Integration Plan

This document outlines the plan to integrate the admin dashboard functionality into the Laravel project, with the frontend served by the Next.js `admin-panel`.

## Current Status:
*   The Next.js dashboard page ([`admin-panel/app/admin/dashboard/page.tsx`](admin-panel/app/admin/dashboard/page.tsx:1)) exists and uses mock data.
*   The Laravel dashboard controller ([`app/Http/Controllers/Admin/DashboardController.php`](app/Http/Controllers/Admin/DashboardController.php:1)) does *not* exist.
*   The API route for the dashboard (e.g., `/api/v1/admin/dashboard`) does *not* exist in [`routes/api.php`](routes/api.php:1).

## Data Requirements for Dashboard (based on Next.js mock data):
*   `totalUsers`: Total count of users.
*   `totalArtists`: Total count of artists.
*   `totalCommissions`: Total count of commissions.
*   `totalOrders`: Total count of orders.
*   `recentUsers`: List of the 5 most recently registered users (id, name, email, createdAt).
*   `recentCommissions`: List of the 5 most recent commissions (id, title, artist_name, status, price).

## Plan:

### Phase 1: Backend (Laravel)

1.  **Create `DashboardController`**:
    *   Create the file [`app/Http/Controllers/Api/Admin/DashboardController.php`](app/Http/Controllers/Api/Admin/DashboardController.php:1).
    *   It will have an `index()` method.
    *   This method will query the database to get:
        *   Total count of users.
        *   Total count of artists (users with an associated artist record).
        *   Total count of commissions.
        *   Total count of orders.
        *   A list of the 5 most recently registered users (e.g., `id`, `name`, `email`, `created_at`).
        *   A list of the 5 most recent commissions (e.g., `id`, `title`, `artist_name` (from related User), `status`, `price`).
    *   The method will return this data as a JSON response.
2.  **Create `DashboardResource` (Optional but Recommended)**:
    *   Create a resource like [`app/Http/Resources/Admin/DashboardResource.php`](app/Http/Resources/Admin/DashboardResource.php:1) to structure the JSON response cleanly. This resource would wrap the data fetched in the controller.
3.  **Define API Route**:
    *   Add a GET route in [`routes/api.php`](routes/api.php:1) like:
        `Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.index');`
        (Assuming `AdminDashboardController` is imported correctly using its FQCN, e.g., `App\Http\Controllers\Api\Admin\DashboardController`).
    *   This route will be within the `v1/admin` prefix and `auth:sanctum` middleware group.

### Phase 2: Frontend (Next.js - `admin-panel`)

1.  **Update `apiClient.ts`**:
    *   Add a new function in [`admin-panel/lib/apiClient.ts`](admin-panel/lib/apiClient.ts:1) to fetch dashboard data from the new Laravel API endpoint (e.g., `fetchDashboardStats`).
2.  **Update Dashboard Page**:
    *   Modify [`admin-panel/app/admin/dashboard/page.tsx`](admin-panel/app/admin/dashboard/page.tsx:1):
        *   Replace the mock `getDashboardStats` function with a call to the new `fetchDashboardStats` function from `apiClient.ts`.
        *   Ensure the component correctly handles the data structure returned by the API.
        *   Update any type definitions in [`admin-panel/lib/types.ts`](admin-panel/lib/types.ts:1) if necessary to match the API response.
3.  **Verify Sidebar Navigation**:
    *   Ensure the "Dashboard" link in [`admin-panel/components/admin/admin-sidebar.tsx`](admin-panel/components/admin/admin-sidebar.tsx:1) correctly points to `/admin/dashboard`.

### Mermaid Diagram of the Plan:

```mermaid
graph TD
    A[Start: Integrate Admin Dashboard] --> B{Backend Setup};
    B --> B1[Create DashboardController in Laravel];
    B1 --> B1a[Implement index() method to fetch stats];
    B1a --> B1b[Query: Total Users, Artists, Commissions, Orders];
    B1a --> B1c[Query: Recent Users (limit 5)];
    B1a --> B1d[Query: Recent Commissions (limit 5)];
    B1 --> B2[Create DashboardResource (Optional)];
    B --> B3[Define API Route in routes/api.php];
    B3 --> B3a[GET /api/v1/admin/dashboard];

    A --> C{Frontend Setup};
    C --> C1[Update apiClient.ts in Next.js];
    C1 --> C1a[Add fetchDashboardStats function];
    C --> C2[Modify Dashboard Page in Next.js];
    C2 --> C2a[Replace mock data with API call];
    C2 --> C2b[Update types if needed];
    C --> C3[Verify Sidebar Link];

    B3a --> C1a;
    C2a --> D{Testing & Verification};
    D --> E[End: Dashboard Integrated];