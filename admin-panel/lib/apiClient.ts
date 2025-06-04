import axios from 'axios';

const apiClient = axios.create({
  // baseURL removed; we will construct full paths starting with /api-proxy
  withCredentials: true, // Important for Sanctum's cookie-based authentication
  headers: {
    'X-Requested-With': 'XMLHttpRequest', // Standard header for AJAX requests
    'Accept': 'application/json', // We expect JSON responses from Laravel
  },
  xsrfCookieName: 'XSRF-TOKEN', // Tell Axios explicitly what cookie name to look for
  xsrfHeaderName: 'X-XSRF-TOKEN', // Tell Axios explicitly what header name to set
});

/**
 * Function to ensure the CSRF cookie is set before making state-changing requests (POST, PUT, DELETE).
 * Laravel Sanctum uses this cookie for CSRF protection with SPAs.
 * This should be called once, typically after login or when the application initializes,
 * especially before any state-changing API calls are made.
 */
export const ensureCsrfCookie = async () => {
  try {
    // NEXT_PUBLIC_LARAVEL_API_URL is /api-proxy/api (defined in .env.local)
    // The /sanctum/csrf-cookie endpoint is at the root of the proxied destination.
    // So, we construct the path to be /api-proxy/sanctum/csrf-cookie
    const csrfCookieUrl = `/api-proxy/sanctum/csrf-cookie`;
    
    // Use the apiClient instance. Since csrfCookieUrl starts with '/',
    // Axios will treat it as a path relative to the current host (localhost:3000),
    // which then gets caught by the Next.js proxy rewrite.
    await apiClient.get(csrfCookieUrl);
    console.log("CSRF cookie request sent via apiClient to proxied endpoint: " + csrfCookieUrl);
  } catch (error) {
    console.error('Failed to fetch CSRF cookie via proxy:', error);
  }
};

// Optional: Add interceptors for global error handling or token refresh if needed later.
// For example, to handle 401 Unauthorized responses globally:
// apiClient.interceptors.response.use(
//   response => response,
//   error => {
//     if (error.response && error.response.status === 401) {
//       // Handle unauthorized access, e.g., redirect to login
//       // window.location.href = '/login';
//       console.error("Unauthorized access - 401");
//     }
//     return Promise.reject(error);
//   }
// );

// --- User Management Specific ---
export const promoteUserToArtist = async (userId: number, data: { portfolio_link?: string }) => {
  await ensureCsrfCookie(); // Ensure CSRF cookie for POST request
  const response = await apiClient.post(`/api-proxy/api/v1/admin/users/${userId}/promote-to-artist`, data);
  return response.data; // Assuming backend returns the new artist resource
};

// --- Artist Management ---
export const fetchArtists = async (page = 1, limit = 15) => {
  const response = await apiClient.get(`/api-proxy/api/v1/admin/artists?page=${page}&limit=${limit}`);
  return response.data; // Expects PaginatedArtistsResponse
};

export const fetchArtist = async (artistId: number) => {
  const response = await apiClient.get(`/api-proxy/api/v1/admin/artists/${artistId}`);
  return response.data.data; // Expects the artist data directly from {data: Artist}
};

export const updateArtist = async (artistId: number, data: { portfolio_link?: string | null; rating?: number | null; is_verified?: boolean }) => {
  await ensureCsrfCookie(); // Ensure CSRF cookie for PUT request
  const response = await apiClient.put(`/api-proxy/api/v1/admin/artists/${artistId}`, data);
  return response.data.data; // Expects the updated artist data
};

export const toggleArtistVerification = async (artistId: number) => {
  await ensureCsrfCookie(); // Ensure CSRF cookie for PUT request
  const response = await apiClient.put(`/api-proxy/api/v1/admin/artists/${artistId}/toggle-verification`);
  return response.data.data; // Expects the updated artist data
};
// --- Commission Management ---
import { PaginatedCommissionsResponse, Commission, PaginatedCategoriesResponse, Category, PaginatedServicesResponse, PaginatedOrdersResponse, Order } from './types';

export const fetchCommissions = async (page = 1, limit = 15, filters: any = {}): Promise<PaginatedCommissionsResponse> => {
  // Construct query parameters, including filters if provided
  const params = new URLSearchParams({
    page: page.toString(),
    limit: limit.toString(),
    ...filters, // Spread filter object into params
  });
  const response = await apiClient.get(`/api-proxy/api/v1/admin/commissions?${params.toString()}`);
  return response.data; // Expects PaginatedCommissionsResponse
};

export const fetchCommission = async (commissionId: number): Promise<Commission> => {
  const response = await apiClient.get(`/api-proxy/api/v1/admin/commissions/${commissionId}`);
  return response.data.data; // Expects the commission data directly from {data: Commission}
};

// Optional: deleteCommission can be added later if functionality is implemented
// export const deleteCommission = async (commissionId: number) => {
//   await ensureCsrfCookie(); // Ensure CSRF cookie for DELETE request
//   const response = await apiClient.delete(`/api-proxy/api/v1/admin/commissions/${commissionId}`);
//   return response.data; // Expects a success message or updated list/status
// };

// --- Category Management ---
export const fetchCategories = async (page = 1, limit = 15): Promise<PaginatedCategoriesResponse> => {
  const response = await apiClient.get(`/api-proxy/api/v1/admin/categories?page=${page}&limit=${limit}`);
  return response.data;
};

export const fetchCategory = async (categoryId: number): Promise<Category> => {
  const response = await apiClient.get(`/api-proxy/api/v1/admin/categories/${categoryId}`);
  return response.data.data; // Assuming API returns { data: Category }
};

export const createCategory = async (data: { name: string; description?: string; slug?: string }): Promise<Category> => {
  await ensureCsrfCookie();
  const response = await apiClient.post('/api-proxy/api/v1/admin/categories', data);
  return response.data.data; // Assuming API returns { data: Category }
};

export const updateCategory = async (categoryId: number, data: { name?: string; description?: string; slug?: string }): Promise<Category> => {
  await ensureCsrfCookie();
  const response = await apiClient.put(`/api-proxy/api/v1/admin/categories/${categoryId}`, data);
  return response.data.data; // Assuming API returns { data: Category }
};

export const deleteCategory = async (categoryId: number): Promise<void> => {
  await ensureCsrfCookie();
  await apiClient.delete(`/api-proxy/api/v1/admin/categories/${categoryId}`);
  // No specific content expected on 204 No Content response
};

// --- Service Management ---
export const fetchServices = async (page = 1, limit = 15): Promise<PaginatedServicesResponse> => {
  const response = await apiClient.get(`/api-proxy/api/v1/admin/services?page=${page}&limit=${limit}`);
  return response.data; // Expects PaginatedServicesResponse
};

// --- Order Management (Admin) ---
export const fetchAdminOrders = async (page = 1, limit = 15, filters: any = {}): Promise<PaginatedOrdersResponse> => {
  const params = new URLSearchParams({
    page: page.toString(),
    limit: limit.toString(),
    ...filters,
  });
  const response = await apiClient.get(`/api-proxy/api/v1/admin/orders?${params.toString()}`);
  return response.data; // Expects PaginatedOrdersResponse
};

export const fetchAdminOrder = async (orderId: number): Promise<Order> => {
  const response = await apiClient.get(`/api-proxy/api/v1/admin/orders/${orderId}`);
  return response.data.data; // Expects { data: Order }
};

// Optional: Update order status
// export const updateAdminOrderStatus = async (orderId: number, status: string): Promise<Order> => {
//   await ensureCsrfCookie();
//   const response = await apiClient.put(`/api-proxy/api/v1/admin/orders/${orderId}/status`, { status });
//   return response.data.data; // Expects { data: Order }
// };

export default apiClient;