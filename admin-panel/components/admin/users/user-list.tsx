"use client";

import Link from "next/link";
import { useEffect, useState, useCallback } from "react";
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Badge } from "@/components/ui/badge";
import { Eye, Edit, Trash2 } from "lucide-react";
import apiClient from "@/lib/apiClient";
import { User, PaginatedResponse } from "@/lib/types";
import { useAuth } from "@/context/AuthContext"; // Assuming AuthContext provides currentUser
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from "@/components/ui/alert-dialog";
import { useToast } from "@/hooks/use-toast";
// Consider adding a Pagination component if you build one, or use a library

export default function UserList() {
  const { user: currentUser } = useAuth(); // Correctly alias user to currentUser
  const { toast } = useToast();

  const [users, setUsers] = useState<User[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  
  // Pagination state
  const [currentPage, setCurrentPage] = useState(1);
  const [lastPage, setLastPage] = useState(1);
  const [totalUsers, setTotalUsers] = useState(0);
  const [fromUser, setFromUser] = useState<number | null>(null);
  const [toUser, setToUser] = useState<number | null>(null);

  // Delete state
  const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState(false);
  const [userIdToDelete, setUserIdToDelete] = useState<number | null>(null);
  const [userNameForDialog, setUserNameForDialog] = useState<string>("");
  const [isDeleting, setIsDeleting] = useState(false);

  const fetchUsers = useCallback(async (page: number) => {
    try {
      setLoading(true);
      const rootViaProxy = process.env.NEXT_PUBLIC_LARAVEL_ROOT_VIA_PROXY;
      if (!rootViaProxy) {
        console.warn("NEXT_PUBLIC_LARAVEL_ROOT_VIA_PROXY is not set. Cannot fetch users.");
        setError("Configuration error: Proxy URL not set.");
        setLoading(false);
        return;
      }
      const usersApiUrl = `${rootViaProxy}/api/v1/admin/users?page=${page}`;
      const response = await apiClient.get<PaginatedResponse<User>>(usersApiUrl);
      
      const fetchedUsers = response.data.data;
      const meta = response.data.meta;

      if (fetchedUsers.length === 0 && page > 1 && meta && meta.total > 0) {
        // If current page is empty after fetch (e.g., last item deleted)
        // and it's not the first page, and there are still total items
        // then go to the previous page.
        setCurrentPage(p => p - 1); // This will trigger another fetchUsers
      } else {
        setUsers(fetchedUsers);
        if (meta) {
          setCurrentPage(meta.current_page);
          setLastPage(meta.last_page);
          setTotalUsers(meta.total);
          setFromUser(meta.from);
          setToUser(meta.to);
        }
      }
      setError(null);
    } catch (err: any) {
      setError(err.response?.data?.message || err.message || "Failed to fetch users.");
      console.error("Failed to fetch users:", err);
    } finally {
      setLoading(false);
    }
  }, [setLoading, setError, setUsers, setCurrentPage, setLastPage, setTotalUsers, setFromUser, setToUser]); // Added dependencies

  useEffect(() => {
    fetchUsers(currentPage);
  }, [currentPage, fetchUsers]);

  const handlePageChange = (newPage: number) => {
    if (newPage >= 1 && newPage <= lastPage) {
      setCurrentPage(newPage);
    }
  };

  if (loading) {
    // You can use Skeleton components here for a better UX
    // Example: <Skeleton className="h-4 w-[250px]" /> for each cell
    return <p>Loading users...</p>;
  }

  if (error) {
    return <p className="text-red-500">Error: {error}</p>;
  }

  if (users.length === 0 && !loading) { // Ensure not to show "No users" while initially loading
    return <p>No users found.</p>;
  }

  const handleDeleteClick = (userId: number, userName: string) => {
    setUserIdToDelete(userId);
    setUserNameForDialog(userName);
    setIsDeleteDialogOpen(true);
  };

  const handleConfirmDelete = async () => {
    if (!userIdToDelete) return;
    setIsDeleting(true);
    try {
      const rootViaProxy = process.env.NEXT_PUBLIC_LARAVEL_ROOT_VIA_PROXY;
      await apiClient.delete(`${rootViaProxy}/api/v1/admin/users/${userIdToDelete}`);
      toast({
        title: "Success",
        description: `User '${userNameForDialog}' (ID: ${userIdToDelete}) has been deleted.`,
      });
      fetchUsers(currentPage); // Re-fetch users for the current page
    } catch (err: any) {
      console.error("Failed to delete user:", err);
      toast({
        title: "Error",
        description: err.response?.data?.message || `Failed to delete user '${userNameForDialog}'.`,
        variant: "destructive",
      });
    } finally {
      setIsDeleting(false);
      setIsDeleteDialogOpen(false);
      setUserIdToDelete(null);
      setUserNameForDialog("");
    }
  };

  return (
    <>
      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>ID</TableHead>
            <TableHead>Name</TableHead>
            <TableHead>Email</TableHead>
            <TableHead>Role</TableHead>
            <TableHead>Status</TableHead>
            <TableHead>Created</TableHead>
            <TableHead className="text-right">Actions</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          {users.map((user) => (
            <TableRow key={user.id}>
              <TableCell className="font-medium">{user.id}</TableCell>
              <TableCell>{user.name}</TableCell>
              <TableCell>{user.email}</TableCell>
              <TableCell>
                <Badge
                  variant={user.role === "admin" ? "default" : user.role === "artist" ? "secondary" : "outline"}
                >
                  {user.role}
                </Badge>
              </TableCell>
              <TableCell>
                <Badge variant={user.is_active ? "default" : "destructive"}>
                  {user.is_active ? "Active" : "Inactive"}
                </Badge>
              </TableCell>
              <TableCell>{new Date(user.created_at).toLocaleDateString()}</TableCell>
              <TableCell className="text-right">
                <div className="flex items-center justify-end gap-2">
                  <Button asChild variant="ghost" size="icon" className="h-8 w-8">
                    <Link href={`/admin/users/${user.id}`}>
                      <Eye className="h-4 w-4" />
                    </Link>
                  </Button>
                  <Button asChild variant="ghost" size="icon" className="h-8 w-8">
                    <Link href={`/admin/users/${user.id}/edit`}>
                      <Edit className="h-4 w-4" />
                    </Link>
                  </Button>
                  <Button
                    variant="ghost"
                    size="icon"
                    className="h-8 w-8 text-red-500 hover:text-red-700"
                    onClick={() => handleDeleteClick(user.id, user.name)}
                    disabled={currentUser?.id === user.id || isDeleting}
                    title={currentUser?.id === user.id ? "Cannot delete own account" : "Delete user"}
                  >
                    <Trash2 className="h-4 w-4" />
                  </Button>
                </div>
              </TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
      
      <AlertDialog open={isDeleteDialogOpen} onOpenChange={setIsDeleteDialogOpen}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Are you absolutely sure?</AlertDialogTitle>
            <AlertDialogDescription>
              This action cannot be undone. This will permanently delete the user account
              for <span className="font-semibold">{userNameForDialog}</span> (ID: {userIdToDelete})
              and remove their data from our servers.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel disabled={isDeleting}>Cancel</AlertDialogCancel>
            <AlertDialogAction
              onClick={handleConfirmDelete}
              disabled={isDeleting}
              className="bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600"
            >
              {isDeleting ? "Deleting..." : "Yes, delete user"}
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>

      {/* Basic Pagination Controls - Consider replacing with a dedicated Pagination component */}
      <div className="mt-6 flex justify-between items-center">
        <div>
          {fromUser && toUser && totalUsers > 0 && (
            <p className="text-sm text-muted-foreground">
              Showing {fromUser} to {toUser} of {totalUsers} users.
            </p>
          )}
           {totalUsers === 0 && (
            <p className="text-sm text-muted-foreground">
              No users to display.
            </p>
           )}
        </div>
        {lastPage > 1 && (
          <div className="flex gap-2">
            <Button onClick={() => handlePageChange(currentPage - 1)} disabled={currentPage === 1} variant="outline" size="sm">
              Previous
            </Button>
            <Button onClick={() => handlePageChange(currentPage + 1)} disabled={currentPage === lastPage} variant="outline" size="sm">
              Next
            </Button>
          </div>
        )}
      </div>
    </>
  );
}