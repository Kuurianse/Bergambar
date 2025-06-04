"use client"; // Add this to make it a Client Component

import type React from "react";
import { useEffect } from "react"; // Import useEffect
import { useRouter } from "next/navigation"; // Use useRouter for client-side navigation
import { AdminSidebar } from "@/components/admin/admin-sidebar";
import { SidebarProvider, SidebarInset } from "@/components/ui/sidebar";
import { AdminHeader } from "@/components/admin/admin-header";
import { useAuth } from "@/context/AuthContext"; // Import useAuth
import { Skeleton } from "@/components/ui/skeleton"; // For loading state

export default function AdminLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  const { user, isLoading } = useAuth();
  const router = useRouter();

  useEffect(() => {
    if (!isLoading && (!user || user.role !== "admin")) {
      router.push("/login"); // Use router.push for client-side redirect
    }
  }, [user, isLoading, router]);

  if (isLoading) {
    // Show a loading state while checking auth
    // This can be a more sophisticated skeleton screen
    return (
      <div className="flex h-screen w-full">
        <div className="w-64 border-r bg-background p-4">
          <Skeleton className="h-8 w-3/4 mb-6" />
          <Skeleton className="h-6 w-full mb-2" />
          <Skeleton className="h-6 w-full mb-2" />
          <Skeleton className="h-6 w-full" />
        </div>
        <div className="flex-1 p-6">
          <Skeleton className="h-10 w-1/4 mb-6" />
          <Skeleton className="h-screen w-full" />
        </div>
      </div>
    );
  }

  if (!user || user.role !== "admin") {
    // This will be briefly visible before redirect, or if redirect fails.
    // Or, you can return null or a "Redirecting..." message.
    return null;
  }

  return (
    <SidebarProvider>
      <AdminSidebar />
      <SidebarInset>
        {/* Pass the authenticated user to AdminHeader */}
        <AdminHeader user={user} />
        <main className="flex-1 space-y-4 p-4 md:p-8 pt-6">{children}</main>
      </SidebarInset>
    </SidebarProvider>
  );
}
