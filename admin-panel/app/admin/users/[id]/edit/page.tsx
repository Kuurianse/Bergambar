'use client';

import { useEffect, useState, FormEvent } from 'react';
import { useParams, useRouter } from 'next/navigation';
import Link from 'next/link';
import apiClient from '@/lib/apiClient';
import { User } from '@/lib/types';
import { ArrowLeft, Save } from 'lucide-react';

import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Switch } from '@/components/ui/switch'; // Added Switch
import { Skeleton } from '@/components/ui/skeleton';
import { useToast } from '@/hooks/use-toast'; // Assuming useToast is set up

interface UserEditFormData {
  role: User['role'];
  is_active: boolean;
  // Add other fields here if they become editable
}

const availableRoles: User['role'][] = ['admin', 'user', 'artist']; // Ensure this matches User type and backend validation

export default function UserEditPage() {
  const params = useParams();
  const router = useRouter();
  const { toast } = useToast();
  const userId = params.id as string;

  const [user, setUser] = useState<User | null>(null);
  const [formData, setFormData] = useState<UserEditFormData>({ role: 'user', is_active: false }); // Initial default
  const [loadingInitial, setLoadingInitial] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    if (userId) {
      const fetchUser = async () => {
        setLoadingInitial(true);
        setError(null);
        try {
          const response = await apiClient.get<{ data: User }>(`/api-proxy/api/v1/admin/users/${userId}`);
          const fetchedUser = response.data.data;
          setUser(fetchedUser);
          setFormData({ role: fetchedUser.role, is_active: fetchedUser.is_active });
        } catch (err: any) {
          console.error('Failed to fetch user for editing:', err);
          setError(err.response?.data?.message || 'Failed to load user data for editing.');
          if (err.response?.status === 404) {
            toast({
              title: 'Error',
              description: `User with ID ${userId} not found.`,
              variant: 'destructive',
            });
          }
        } finally {
          setLoadingInitial(false);
        }
      };
      fetchUser();
    }
  }, [userId, toast]);

  const handleRoleChange = (value: string) => {
    setFormData((prev) => ({ ...prev, role: value as User['role'] }));
  };

  const handleStatusChange = (checked: boolean) => {
    setFormData((prev) => ({ ...prev, is_active: checked }));
  };

  const handleSubmit = async (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setSubmitting(true);
    setError(null);

    try {
      const response = await apiClient.put<{ data: User }>(`/api-proxy/api/v1/admin/users/${userId}`, {
        role: formData.role,
        is_active: formData.is_active,
      });
      const updatedUser = response.data.data;
      setUser(updatedUser); // Update local user state
      // Also update formData to reflect the saved state, especially if is_active was derived
      setFormData({ role: updatedUser.role, is_active: updatedUser.is_active });
      toast({
        title: 'Success!',
        description: `User ${response.data.data.name} (ID: ${userId}) has been updated.`,
      });
      router.push(`/admin/users/${userId}`); // Redirect to detail page
    } catch (err: any) {
      console.error('Failed to update user:', err);
      let errorMessage = 'Failed to update user.';
      if (err.response?.data?.errors) {
        // Handle Laravel validation errors
        const validationErrors = err.response.data.errors;
        const firstErrorKey = Object.keys(validationErrors)[0];
        errorMessage = validationErrors[firstErrorKey][0] || errorMessage;
      } else if (err.response?.data?.message) {
        errorMessage = err.response.data.message;
      }
      setError(errorMessage);
      toast({
        title: 'Update Failed',
        description: errorMessage,
        variant: 'destructive',
      });
    } finally {
      setSubmitting(false);
    }
  };

  if (loadingInitial) {
    return (
      <div className="space-y-4 p-4 md:p-8">
        <Skeleton className="h-10 w-40 mb-4" /> {/* Back button skeleton */}
        <Card>
          <CardHeader>
            <Skeleton className="h-8 w-3/4" />
            <Skeleton className="h-4 w-1/2" />
          </CardHeader>
          <CardContent className="space-y-6">
            <div className="space-y-2">
              <Skeleton className="h-4 w-16" /> {/* Label skeleton */}
              <Skeleton className="h-10 w-full" /> {/* Select skeleton */}
            </div>
          </CardContent>
          <CardFooter>
            <Skeleton className="h-10 w-28" /> {/* Save button skeleton */}
          </CardFooter>
        </Card>
      </div>
    );
  }

  if (!user) {
    // Error message for user not found is handled by toast in useEffect
    // Or a more prominent message can be shown here if setError was called
    return (
      <div className="space-y-4 p-4 md:p-8">
        <Button variant="outline" size="sm" onClick={() => router.push(`/admin/users`)} className="mb-4">
          <ArrowLeft className="mr-2 h-4 w-4" />
          Back to User List
        </Button>
        <p className="text-red-600">{error || 'User data could not be loaded.'}</p>
      </div>
    );
  }

  return (
    <div className="space-y-6 p-4 md:p-8 pt-6">
      <Button variant="outline" size="sm" onClick={() => router.push(`/admin/users/${userId}`)} className="mb-4">
        <ArrowLeft className="mr-2 h-4 w-4" />
        Back to User Details
      </Button>

      <Card>
        <form onSubmit={handleSubmit}>
          <CardHeader>
            <CardTitle className="text-2xl">Edit User: {user.name}</CardTitle>
            <CardDescription>Update details for user ID: {user.id}</CardDescription>
          </CardHeader>
          <CardContent className="space-y-6">
            <div className="space-y-2">
              <Label htmlFor="role">Role</Label>
              <Select value={formData.role} onValueChange={handleRoleChange} name="role" disabled={submitting}>
                <SelectTrigger id="role">
                  <SelectValue placeholder="Select a role" />
                </SelectTrigger>
                <SelectContent>
                  {availableRoles.map((roleOption) => (
                    <SelectItem key={roleOption} value={roleOption}>
                      {roleOption.charAt(0).toUpperCase() + roleOption.slice(1)}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>

            {/* Status Field */}
            <div className="space-y-2">
              <Label htmlFor="status" className="flex items-center">
                Status
                <span className={`ml-2 text-xs font-normal px-2 py-0.5 rounded-full ${formData.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`}>
                  {formData.is_active ? 'Active' : 'Inactive'}
                </span>
              </Label>
              <Switch
                id="status"
                checked={formData.is_active}
                onCheckedChange={handleStatusChange}
                disabled={submitting}
                aria-label="User status"
              />
            </div>

            {error && <p className="text-sm font-medium text-destructive">{error}</p>}
          </CardContent>
          <CardFooter>
            <Button
              type="submit"
              disabled={submitting || (formData.role === user.role && formData.is_active === user.is_active)}
            >
              {submitting ? (
                <>
                  <Save className="mr-2 h-4 w-4 animate-spin" /> Saving...
                </>
              ) : (
                <>
                  <Save className="mr-2 h-4 w-4" /> Save Changes
                </>
              )}
            </Button>
          </CardFooter>
        </form>
      </Card>
    </div>
  );
}
