"use client";

import React, { createContext, useContext, useState, useEffect, ReactNode } from 'react';
import apiClient, { ensureCsrfCookie } from '@/lib/apiClient';
import { User } from '@/lib/types'; // Assuming User type is defined in types.ts

interface AuthContextType {
  user: User | null;
  isLoading: boolean;
  login: (credentials: Record<string, string>) => Promise<void>;
  logout: () => Promise<void>;
  fetchUser: () => Promise<User | null>; // Exposed for manual refresh if needed
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider = ({ children }: { children: ReactNode }) => {
  const [user, setUser] = useState<User | null>(null);
  const [isLoading, setIsLoading] = useState(true);

  const fetchUser = async (): Promise<User | null> => {
    try {
      const rootViaProxy = process.env.NEXT_PUBLIC_LARAVEL_ROOT_VIA_PROXY; // Should be /api-proxy
      if (!rootViaProxy) {
        console.warn("NEXT_PUBLIC_LARAVEL_ROOT_VIA_PROXY is not set. Cannot fetch user.");
        setUser(null);
        return null;
      }
      // Laravel's Sanctum user endpoint is /api/user. We access it via the proxy.
      const fetchUserUrl = `${rootViaProxy}/api/user`;
      const response = await apiClient.get<User>(fetchUserUrl);
      setUser(response.data);
      return response.data;
    } catch (error) {
      setUser(null);
      // console.error('No authenticated user or failed to fetch user', error);
      return null;
    }
  };

  useEffect(() => {
    const initializeAuth = async () => {
      setIsLoading(true);
      try {
        await ensureCsrfCookie(); // Call CSRF cookie function on initial load
        await fetchUser(); // Then try to fetch user (will fail if not logged in, that's fine)
      } catch (e) {
        // Errors here are expected if not logged in or CSRF fails initially
        console.info("Initialization error (expected if not logged in):", e);
      } finally {
        setIsLoading(false);
      }
    };
    initializeAuth();
  }, []);

  const login = async (credentials: Record<string, string>) => {
    setIsLoading(true);
    try {
      // With the proxy, web routes like /login are accessed via /api-proxy/login
      const loginUrl = `/api-proxy/login`;
      
      // ensureCsrfCookie() is called on init and will use the proxied path for /sanctum/csrf-cookie.
      // Calling again here is a good safeguard.
      await ensureCsrfCookie();
      
      await apiClient.post(loginUrl, credentials, { withCredentials: true });

      await fetchUser(); // fetchUser uses apiClient, which is configured with baseURL /api-proxy/api
                         // so this will correctly hit /api-proxy/api/user
    } catch (error) {
      setUser(null);
      console.error('Login failed:', error);
      throw error; // Re-throw to handle in login form
    } finally {
      setIsLoading(false);
    }
  };

  const logout = async () => {
    setIsLoading(true);
    try {
      const logoutUrl = `/api-proxy/logout`;
      // No need to call ensureCsrfCookie for logout if it's just invalidating session.
      // If Laravel's /logout POST route is CSRF protected, then ensureCsrfCookie() might be needed.
      await apiClient.post(logoutUrl, { withCredentials: true }); // Ensure credentials for logout too
      setUser(null);
    } catch (error) {
      console.error('Logout failed:', error);
      setUser(null); // Ensure client state is cleared
      throw error;
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <AuthContext.Provider value={{ user, isLoading, login, logout, fetchUser }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};