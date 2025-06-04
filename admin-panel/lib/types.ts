export interface User {
  id: number;
  name: string;
  email: string;
  role: 'user' | 'artist' | 'admin'; // Adjust if roles are more dynamic
  username?: string | null; // Optional, as per UserResource
  bio?: string | null; // Optional
  profile_picture?: string | null; // Optional
  created_at: string; // ISO date string from Laravel
  updated_at: string; // ISO date string from Laravel
  is_active: boolean; // Derived in UserResource
  is_artist?: boolean; // Added for artist info
  artist_id?: number | null; // Added for artist info
}

export interface LaravelLink {
  url: string | null;
  label: string;
  active: boolean;
}

export interface PaginatedResponse<T> {
  data: T[];
  links?: { // Standard Laravel pagination links structure
    first: string | null;
    last: string | null;
    prev: string | null;
    next: string | null;
  };
  meta?: { // Standard Laravel pagination meta structure
    current_page: number;
    from: number | null;
    last_page: number;
    links?: LaravelLink[]; // Detailed links array
    path: string;
    per_page: number;
    to: number | null;
    total: number;
  };
}

// Artist related types
export interface Artist {
  id: number;
  user_id: number;
  portfolio_link: string | null;
  is_verified: boolean;
  rating: number | null;
  created_at: string;
  updated_at: string;
  user?: User; // User details associated with the artist
}

export interface PaginatedArtistsResponse extends PaginatedResponse<Artist> {}

// --- Category Related Types ---
export interface Category {
  id: number;
  name: string;
  description?: string | null;
  slug?: string | null;
  created_at?: string; // ISO date string
  updated_at?: string; // ISO date string
  services_count?: number;
}

export interface PaginatedCategoriesResponse extends PaginatedResponse<Category> {}

// Add other interfaces as needed for Commissions, Services, etc.
// For example:
// export interface Commission { ... }
// --- Commission Related Types ---

export interface Service {
  id: number;
  title: string;
  description: string | null;
  price: number; // Assuming price is a number, adjust if it's string
  artist_name?: string | null;
  category_name?: string | null;
  created_at: string; // ISO date string
}

export interface PaginatedServicesResponse extends PaginatedResponse<Service> {}

export interface Commission {
  id: number;
  user_id: number;
  title: string;
  status: string; // Internal status
  public_status: string; // Accessor from Laravel
  total_price: number | string; // string from DB, number after parse
  description: string | null;
  image: string | null;
  service_id: number | null;
  created_at: string;
  updated_at: string;
  user?: User; // Artist info
  service?: Service; // Service info
  // Optional relations for detail page - to be added in later phases if needed
  // orders?: Order[];
  // reviews?: Review[];
  // payments?: Payment[];
}

export interface PaginatedCommissionsResponse extends PaginatedResponse<Commission> {}

// --- Payment Type ---
export interface Payment {
  id: number;
  order_id: number;
  amount: number; // Or string, check API response
  payment_method: string;
  status: string; // e.g., 'pending', 'paid', 'failed', 'refunded'
  transaction_id?: string | null;
  payment_date?: string | null; // ISO date string
  notes?: string | null;
  created_at: string; // ISO date string
  updated_at: string; // ISO date string
}

// --- Order Type ---
export interface Order {
  id: number;
  order_code?: string; // From OrderResource
  order_date: string; // Typically created_at from Laravel model
  status: string; // e.g., 'pending', 'processing', 'shipped', 'completed', 'cancelled'
  total_price: number; // Or string, check API response
  customer_name?: string;
  customer_email?: string;
  commission_id?: number;
  commission_title?: string;
  artist_name?: string | null;
  artist_email?: string | null;
  payment_status?: string; // Derived, e.g., 'pending', 'paid', 'failed'
  payment_method?: string | null; // Derived
  notes?: string | null;
  created_at: string; // ISO date string
  updated_at: string; // ISO date string
  user?: User; // Customer details
  commission?: Commission; // Commission details
  payments?: Payment[]; // Array of payment details
}

export interface PaginatedOrdersResponse extends PaginatedResponse<Order> {}

// --- Review Type (Minimal stub for future) ---
// export interface Review {
//   id: number;
//   // ... other review fields
//   commission_id?: number;
//   user_id?: number; // Reviewer
//   rating: number;
//   comment: string | null;
//   created_at: string;
// }
