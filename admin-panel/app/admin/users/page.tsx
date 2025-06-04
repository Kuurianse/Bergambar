import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import UserList from "@/components/admin/users/user-list"; // Adjust path if necessary
import Link from "next/link"; // Activated for Create User button
import { Button } from "@/components/ui/button"; // Activated for Create User button


// This page remains a Server Component, delegating client-side logic to UserList
export default function UsersPage() {
  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold tracking-tight">User Management</h1>
          <p className="text-muted-foreground">Manage all users, their roles, and permissions.</p>
        </div>
        <Button asChild>
          <Link href="/admin/users/create">Create New User</Link>
        </Button>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>All Users</CardTitle>
          <CardDescription>A list of all users in your system with their roles and status.</CardDescription>
        </CardHeader>
        <CardContent>
          <UserList />
        </CardContent>
      </Card>
    </div>
  );
}
