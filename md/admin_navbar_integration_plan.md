# Plan: Integrating the Admin Panel Navbar (Next.js)

**Goal:** Integrate the `AdminHeader` component into the main layout of the Next.js admin panel, display user information (name, email), implement the logout functionality, and use placeholders for search, notification, profile, and settings features.

**Phase 1: Information Gathering & Setup (Mostly Complete)**

1.  **Locate the Main Admin Layout File:**
    *   **Status:** Confirmed
    *   **File:** `admin-panel/app/admin/layout.tsx`
2.  **Review `AdminHeader` and `AuthContext`:**
    *   **Status:** Done
    *   **Files Reviewed:**
        *   `admin-panel/components/admin/admin-header.tsx`
        *   `admin-panel/context/AuthContext.tsx`
        *   `admin-panel/lib/types.ts` (for `User` type definition)

**Phase 2: Implementation**

1.  **Integrate `AdminHeader` into the Admin Layout (`admin-panel/app/admin/layout.tsx`):**
    *   **Action:**
        *   Modify `admin-panel/app/admin/layout.tsx`.
        *   Import `AdminHeader` from `@/components/admin/admin-header` and `useAuth` from `@/context/AuthContext`.
        *   Inside the layout component, use the `useAuth()` hook to get the `user` object and `isLoading` state.
        *   Conditionally render `AdminHeader` if `user` is available and not `isLoading`. Pass the `user` data (e.g., `<AdminHeader user={user} />`).
        *   Ensure the header is placed appropriately within the overall page structure (e.g., above the main content and sidebar).
    *   **Files to modify:** `admin-panel/app/admin/layout.tsx`

2.  **Implement `AdminHeader` Functionalities (`admin-panel/components/admin/admin-header.tsx`):**
    *   **Action:** Modify `admin-panel/components/admin/admin-header.tsx`.
        *   **User Information:**
            *   The component already receives `user` as a prop. Ensure `user.name` and `user.email` are correctly displayed in the `DropdownMenuLabel` and the `AvatarFallback` uses `user.name.charAt(0)`.
        *   **Search Bar (`Input` with `Search` icon):**
            *   No specific action on search for now. It will remain a visual element.
        *   **Notification Button (`Bell` icon):**
            *   No specific action on click for now. It will remain a visual element.
        *   **User Dropdown Menu Items:**
            *   **Profile (`DropdownMenuItem`):**
                *   Modify the existing `DropdownMenuItem` for "Profile".
                *   Add an `onSelect` handler: `onSelect={() => console.log("Profile clicked")}`. (Alternatively, use `<Link href="#">Profile</Link>` if appropriate for Shadcn `DropdownMenuItem`).
            *   **Settings (`DropdownMenuItem`):**
                *   Modify the existing `DropdownMenuItem` for "Settings".
                *   Add an `onSelect` handler: `onSelect={() => console.log("Settings clicked")}`.
            *   **Log out (`DropdownMenuItem`):**
                *   Import `useAuth` from `@/context/AuthContext`.
                *   Inside the `AdminHeader` component, get the `logout` function: `const { logout } = useAuth();`.
                *   Modify the existing `DropdownMenuItem` for "Log out".
                *   Add an `onSelect` handler: `onSelect={async () => { await logout(); }}`.
    *   **Files to modify:** `admin-panel/components/admin/admin-header.tsx`

**Phase 3: Verification**

1.  **Test the Integration:**
    *   Log in to the admin panel.
    *   Verify the header is displayed correctly at the top of admin pages.
    *   Confirm the logged-in user's name and email appear in the dropdown.
    *   Click the "Profile" link/item and check the console for "Profile clicked".
    *   Click the "Settings" link/item and check the console for "Settings clicked".
    *   Click the "Log out" button. Ensure the user is logged out and redirected (typically to the login page, as handled by `AuthContext` or protected route logic).

**Diagram (Conceptual Flow):**

```mermaid
graph TD
    A[Admin Layout File (admin/layout.tsx)] -- Uses --> B(useAuth Hook)
    B -- Provides --> UserData[User Object (name, email)]
    B -- Provides --> LogoutFunc[logout() function]
    A -- Renders --> C(AdminHeader Component)
    C -- Receives --> UserData
    C -- Contains --> SearchInput[Search Input (Visual)]
    C -- Contains --> NotificationIcon[Notification Icon (Visual)]
    C -- Contains --> UserDropdown[User Dropdown Menu]
    UserDropdown -- Contains --> ProfileLink[Profile Link (Placeholder - logs to console)]
    UserDropdown -- Contains --> SettingsLink[Settings Link (Placeholder - logs to console)]
    UserDropdown -- Contains --> LogoutButton[Logout Button (Functional)]
    LogoutButton -- Calls --> LogoutFunc