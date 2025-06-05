## Development Plan: Commission Management Module (Phase 1 - Read-Only)

This plan outlines the steps to implement the core read-only features for managing commissions in the admin panel, based on the initial [`commission_management_integration_plan.md`](commission_management_integration_plan.md:1) and our current findings.

```mermaid
graph TD
    subgraph Backend (Laravel API)
        direction LR
        A1[Create app/Http/Controllers/Api/Admin/CommissionController.php] --> A2{Implement index method};
        A2 --> A3[Eager load User (artist) & Service];
        A2 --> A4[Implement pagination];
        A2 --> A5{Implement show method};
        A5 --> A6[Eager load User, Service];
        A1 --> A7[Use CommissionResource for responses];

        B1[Create app/Http/Resources/Admin/CommissionResource.php] --> B2{Define structure};
        B2 --> B3[Include Commission model fields];
        B2 --> B4[Include User (artist) subset: id, name, email];
        B2 --> B5[Include Service subset: id, name, category_name];

        C1[Update routes/api.php] --> C2{Define admin routes};
        C2 --> C3[GET /commissions -> CommissionController@index];
        C2 --> C4[GET /commissions/{commission} -> CommissionController@show];
    end

    subgraph Frontend (Next.js - admin-panel)
        direction LR
        D1[Update admin-panel/lib/types.ts] --> D2{Define types};
        D2 --> D3[Commission interface];
        D2 --> D4[PaginatedCommissionsResponse interface];
        D2 --> D5[Minimal Service interface];

        E1[Update admin-panel/lib/apiClient.ts] --> E2{Add API functions};
        E2 --> E3[fetchCommissions(page, limit, filters?)];
        E2 --> E4[fetchCommission(commissionId)];

        F1[Implement admin-panel/app/admin/commissions/page.tsx (List Page)] --> F2{Fetch & display commissions};
        F2 --> F3[Use apiClient.fetchCommissions];
        F2 --> F4[Use Shadcn UI DataTable with pagination];
        F2 --> F5[Columns: ID, Judul, Artis, Layanan, Status (Internal & Publik), Harga, Tgl Dibuat];
        F2 --> F6[Add "View Details" link to detail page];

        G1[Create & Implement admin-panel/app/admin/commissions/[id]/page.tsx (Detail Page)] --> G2{Fetch & display commission details};
        G2 --> G3[Use apiClient.fetchCommission];
        G2 --> G4[Display Commission, User (Artist), Service info];
        G2 --> G5[Add "Back to List" button];

        H1[Update admin-panel/components/admin/admin-sidebar.tsx] --> H2[Add "Commissions" navigation link];
    end

    Backend --> Frontend;
```

### I. Backend Development (Laravel API)

1.  **Create `CommissionController`**
    *   **File:** `app/Http/Controllers/Api/Admin/CommissionController.php`
    *   **`index(Request $request)` method:**
        *   Fetch `Commission` records.
        *   Eager load `user` (for artist information) and `service` (for related service information).
        *   Implement pagination (e.g., 15 items per page).
        *   Return data formatted by `CommissionResource`.
    *   **`show(Commission $commission)` method:**
        *   Fetch a single `Commission` record by its ID.
        *   Eager load `user` and `service`.
        *   Return data formatted by `CommissionResource`.

2.  **Create `CommissionResource`**
    *   **File:** `app/Http/Resources/Admin/CommissionResource.php`
    *   **Structure:**
        *   Include essential fields from the `Commission` model: `id`, `title`, `status`, `public_status` (accessor), `total_price`, `description`, `image`, `created_at`, `updated_at`.
        *   Include related `user` (artist) data: `id`, `name`, `email` (potentially using a lean `UserResource` or a sub-selection).
        *   Include related `service` data: `id`, `name`, and `category_name` (if `category_name` is readily available on the `Service` model or through an accessor/join).

3.  **Define API Routes**
    *   **File:** `routes/api.php`
    *   Add the following routes within the existing `admin` and `v1` group:
        *   `GET /commissions` -> `[App\Http\Controllers\Api\Admin\CommissionController::class, 'index']`
        *   `GET /commissions/{commission}` -> `[App\Http\Controllers\Api\Admin\CommissionController::class, 'show']` (ensure route model binding for `Commission`)

### II. Frontend Development (Next.js - `admin-panel`)

1.  **Update Type Definitions**
    *   **File:** `admin-panel/lib/types.ts`
    *   Define the `Commission` interface as detailed in [`commission_management_integration_plan.md#L91-L109`](commission_management_integration_plan.md:91).
    *   Define `PaginatedCommissionsResponse extends PaginatedResponse<Commission>`.
    *   Define a minimal `Service` interface: `id`, `name`, `category_name?`.

2.  **Update API Client**
    *   **File:** `admin-panel/lib/apiClient.ts`
    *   Add `fetchCommissions(page?: number, limit?: number, filters?: any)` function to call the `GET /api-proxy/api/v1/admin/commissions` endpoint.
    *   Add `fetchCommission(commissionId: number)` function to call the `GET /api-proxy/api/v1/admin/commissions/{commissionId}` endpoint.

3.  **Implement Commission List Page**
    *   **File:** `admin-panel/app/admin/commissions/page.tsx`
    *   Fetch paginated commission data using `fetchCommissions` from the API client.
    *   Display data in a Shadcn UI `DataTable`.
    *   **Columns:** ID, Judul (Title), Artis (User Name), Layanan (Service Name), Status (Internal), Status Publik (Public Status), Harga (Total Price), Tanggal Dibuat (Created At).
    *   Implement client-side or server-side pagination for the table.
    *   Each row should have a "View Details" link/button navigating to `/admin/commissions/[id]`.

4.  **Create and Implement Commission Detail Page**
    *   **Directory:** `admin-panel/app/admin/commissions/[id]/`
    *   **File:** `page.tsx`
    *   Fetch specific commission data using `fetchCommission` with the `commissionId` from the URL.
    *   Display comprehensive commission details:
        *   Basic commission information (title, description, status, price, image, etc.).
        *   Artist details (name, email).
        *   Service details (name, category).
    *   Include a "Back to Commission List" button/link.

5.  **Add Navigation Link**
    *   **File:** `admin-panel/components/admin/admin-sidebar.tsx`
    *   Add a new navigation item "Commissions" linking to `/admin/commissions`.

### III. Considerations

*   **Error Handling:** Implement appropriate error handling for API calls on the frontend and robust responses on the backend.
*   **Loading States:** Implement loading indicators on the frontend while data is being fetched.
*   **Data Formatting:** Ensure dates, currency, etc., are formatted appropriately for display.
*   **Existing Components:** Leverage existing UI components and patterns from the admin panel for consistency.