# Plan: Artist's Order Management Workflow

This document outlines the proposed plan for implementing the artist's side of managing incoming orders for their commissions.

## 1. Core Concept

When a client successfully orders and pays for a commission, the commission itself enters a new lifecycle managed by the artist. This involves updating the commission's status and providing an interface for the artist to view and act on these ordered commissions.

## 2. Commission Status Updates

The `status` column in the `commissions` table will be used to track the lifecycle of a commission once it has been ordered.

*   **Initial State After Client Order:**
    *   When an `Order` is successfully created with a 'paid' status (typically in `OrderController@confirmPayment`):
        *   The related `Commission`'s status should be automatically updated from its current state (e.g., 'available', 'pending_approval_by_admin') to **'Ordered - Pending Artist Action'**.
        *   This indicates the artist needs to review and acknowledge the new order.

*   **Artist-Driven Commission Statuses:**
    *   **`Ordered - Pending Artist Action`**: Default status after a client successfully orders and pays.
    *   **`In Progress`**: Artist has accepted/acknowledged the commission and has started or is about to start working on it.
    *   **`Submitted for Client Review`**: Artist has completed their work on the commission and has submitted it to the client for approval. (This may involve uploading files or sending a notification).
    *   **`Completed`**: The client has approved the submitted work, and the commission is considered fulfilled.
    *   **`Needs Revision`**: The client has reviewed the work and requested revisions. The artist will then work on the revisions, and the status will likely revert to 'In Progress' or a similar state.
    *   **(Optional) `Cancelled by Artist`**: If an artist cannot fulfill an order. This would require careful consideration of refund policies and processes, potentially involving admin mediation. For an initial implementation, this might be handled manually or deferred.

## 3. Artist Interface for Managing Sales/Ordered Commissions

New routes, controller methods, and views will be required to provide artists with an interface to manage their sales. This could be part of the existing `CommissionController` or a new dedicated `ArtistDashboardController`.

### 3.1. "My Sales" / "Incoming Orders" List Page

*   **Purpose:** Allows artists to see all their commissions that have active orders and require their attention or are in progress.
*   **Route Example:** `GET /artist/sales`
*   **Controller Method Example:** `CommissionController@listArtistSales` (or `ArtistDashboardController@listSales`)
    *   **Logic:**
        *   Ensure the user is authenticated and is an artist (has a profile or relevant role).
        *   Fetch commissions owned by the logged-in artist.
        *   Filter these commissions by relevant statuses: 'Ordered - Pending Artist Action', 'In Progress', 'Submitted for Client Review', 'Needs Revision'.
        *   Paginate results if necessary.
        *   Pass the commissions (with eager-loaded client/order information if efficient) to the view.
*   **View Example:** `resources/views/artist/sales/index.blade.php`
    *   **Content:**
        *   A clear title, e.g., "My Sales" or "Manage Incoming Orders".
        *   A table or list displaying:
            *   Commission Title (link to the "Manage Sale" page)
            *   Client Name (from the `Order`'s `User` relationship)
            *   Order Date (from the `Order`'s `created_at`)
            *   Current Commission Status (e.g., 'Ordered - Pending Artist Action')
            *   (Optional) Order Total Price
        *   A prominent link/button for each item: "Manage This Sale" or "View Order Details".

### 3.2. "Manage Sale" / Order Detail Page (Artist's View)

*   **Purpose:** Provides the artist with all necessary details for a specific ordered commission and allows them to take action.
*   **Route Example:** `GET /artist/sales/{commission}` (using route model binding for the `Commission`)
*   **Controller Method Example:** `CommissionController@showArtistSaleDetails` (or `ArtistDashboardController@showSaleDetails`)
    *   **Logic:**
        *   Ensure the authenticated user owns the specified commission.
        *   Fetch the commission with its related `Order` (assuming one active order per commission for simplicity initially, or logic to handle multiple if applicable) and the `User` who placed the order (the client).
        *   Pass all relevant data to the view.
*   **View Example:** `resources/views/artist/sales/show.blade.php`
    *   **Content:**
        *   Commission Details: Title, description, original price, image.
        *   Client Information: Name, link to profile (if applicable), contact (perhaps via integrated chat).
        *   Order Details: Order date, payment status (should be 'paid').
        *   Communication Area: Link to chat with the client.
        *   **Action Buttons/Forms (conditional on current commission status):**
            *   If `Commission.status` is **'Ordered - Pending Artist Action'**:
                *   Button: "Accept & Start Work" (triggers an update to 'In Progress').
            *   If `Commission.status` is **'In Progress'**:
                *   Button/Form: "Submit Work for Client Review" (triggers an update to 'Submitted for Client Review'). This might include a way to attach files or add a message.
            *   If `Commission.status` is **'Needs Revision'**:
                *   Button/Form: "Re-submit Work for Client Review" (after artist makes changes, updates status back to 'Submitted for Client Review').
        *   (Optional) Area to add private notes for the artist about the commission.

### 3.3. Controller Methods for Artist Actions (Examples)

These methods would handle the POST requests from the action buttons on the "Manage Sale" page.

*   **Accepting an Order:**
    *   **Route Example:** `POST /artist/sales/{commission}/accept`
    *   **Controller Method Example:** `CommissionController@acceptSale(Commission $commission)`
        *   **Logic:**
            *   Authorize: Ensure logged-in user owns the commission.
            *   Validate: Ensure commission status is 'Ordered - Pending Artist Action'.
            *   Update `Commission.status` to 'In Progress'.
            *   Save the commission.
            *   (Optional) Send a notification to the client.
            *   Redirect back to the "Manage Sale" page or "My Sales" list with a success message.

*   **Submitting Work for Review:**
    *   **Route Example:** `POST /artist/sales/{commission}/submit-review`
    *   **Controller Method Example:** `CommissionController@submitWorkForReview(Request $request, Commission $commission)`
        *   **Logic:**
            *   Authorize.
            *   Validate: Ensure commission status is 'In Progress' or 'Needs Revision'. Handle file uploads if part of submission.
            *   Update `Commission.status` to 'Submitted for Client Review'.
            *   Save the commission. Store submitted files/links if applicable.
            *   (Optional) Send a notification to the client.
            *   Redirect with success message.

## 4. Simplified Workflow Diagram

```mermaid
graph LR
    subgraph Client
        A[Views Commission] --> B{Places Order & Pays};
        B -- Success --> C[Order Status: 'paid'];
        C --> D[Commission Status: 'Ordered - Pending Artist Action'];
    end

    subgraph Artist
        E[Views "My Sales" Page] --> F{Sees Commission (Status: 'Ordered - Pending Artist Action')};
        F -- Clicks "Manage" --> G[Views "Manage Sale" Page for that Commission];
        G -- Status: 'Ordered - Pending Artist Action' --> H["Accept & Start Work" Button];
        H -- Clicks --> I[Commission Status: 'In Progress'];
        I --> J[Works on Commission];
        J --> K["Submit for Client Review" Button on "Manage Sale" Page];
        K -- Clicks --> L[Commission Status: 'Submitted for Client Review'];
    end

    subgraph ClientAgain ["Client Review (Simplified - Future Phase)"]
        M[Client Notified] --> N{Reviews Work};
        N -- Approves --> O[Order Status: 'client_approved'];
        O --> P[Commission Status: 'Completed'];
        N -- Requests Revisions --> Q[Commission Status: 'Needs Revision'];
    end

    D --> E;
    L --> M;
    Q --> I; %% Artist works on revisions, loop back
```

## 5. Next Steps (After this plan is approved)

1.  Define the exact string values for the `Commission.status` enum/column.
2.  Create necessary database migrations if the `commissions` table's `status` column needs modification (e.g., changing from a simple string to an enum, or altering existing string values).
3.  Implement the routes, controller methods, and Blade views outlined above.
4.  Integrate the automatic update of `Commission.status` to 'Ordered - Pending Artist Action' within the `OrderController@confirmPayment` method.

This plan provides a solid foundation for the artist's order management workflow. Further details, such as file handling for submissions, client-side review/approval interface, and notifications, can be built upon this base.