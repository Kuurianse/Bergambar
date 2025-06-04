'use client';

import { useState, FormEvent } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import apiClient from '@/lib/apiClient';
import { User } from '@/lib/types'; // Assuming User type has 'role'
import { ArrowLeft, Save } from 'lucide-react';

import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea'; // For bio
import { useToast } from '@/hooks/use-toast';

interface UserCreateFormData {
  name: string;
  email: string;
  username: string;
  password: string;
  password_confirmation: string;
  role: User['role'];
  bio: string;
}

const initialFormData: UserCreateFormData = {
  name: '',
  email: '',
  username: '',
  password: '',
  password_confirmation: '',
  role: 'user', // Default role
  bio: '',
};

const availableRoles: User['role'][] = ['admin', 'user', 'artist'];

export default function UserCreatePage() {
  const router = useRouter();
  const { toast } = useToast();

  const [formData, setFormData] = useState<UserCreateFormData>(initialFormData);
  const [submitting, setSubmitting] = useState(false);
  const [errors, setErrors] = useState<Partial<Record<keyof UserCreateFormData, string[]>>>({});

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
    if (errors[name as keyof UserCreateFormData]) {
      setErrors((prev) => ({ ...prev, [name]: undefined }));
    }
  };

  const handleRoleChange = (value: string) => {
    setFormData((prev) => ({ ...prev, role: value as User['role'] }));
     if (errors.role) {
      setErrors((prev) => ({ ...prev, role: undefined }));
    }
  };

  const handleSubmit = async (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setSubmitting(true);
    setErrors({});

    try {
      const response = await apiClient.post<{ data: User }>('/api-proxy/api/v1/admin/users', formData);
      toast({
        title: 'Success!',
        description: `User ${response.data.data.name} has been created successfully.`,
      });
      router.push('/admin/users'); // Redirect to user list page
    } catch (err: any) {
      console.error('Failed to create user:', err);
      if (err.response?.status === 422 && err.response?.data?.errors) {
        setErrors(err.response.data.errors);
        toast({
          title: 'Validation Failed',
          description: 'Please check the form for errors.',
          variant: 'destructive',
        });
      } else {
        const errorMessage = err.response?.data?.message || 'An unexpected error occurred.';
        toast({
          title: 'Creation Failed',
          description: errorMessage,
          variant: 'destructive',
        });
      }
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="space-y-6 p-4 md:p-8 pt-6">
      <Button variant="outline" size="sm" asChild className="mb-4">
        <Link href="/admin/users">
          <ArrowLeft className="mr-2 h-4 w-4" />
          Back to User List
        </Link>
      </Button>

      <Card>
        <form onSubmit={handleSubmit}>
          <CardHeader>
            <CardTitle className="text-2xl">Create New User</CardTitle>
            <CardDescription>Fill in the details to create a new user account.</CardDescription>
          </CardHeader>
          <CardContent className="space-y-4">
            {/* Name Field */}
            <div className="space-y-1">
              <Label htmlFor="name">Full Name</Label>
              <Input id="name" name="name" value={formData.name} onChange={handleChange} disabled={submitting} required />
              {errors.name && <p className="text-xs text-destructive mt-1">{errors.name[0]}</p>}
            </div>

            {/* Email Field */}
            <div className="space-y-1">
              <Label htmlFor="email">Email Address</Label>
              <Input id="email" name="email" type="email" value={formData.email} onChange={handleChange} disabled={submitting} required />
              {errors.email && <p className="text-xs text-destructive mt-1">{errors.email[0]}</p>}
            </div>

            {/* Username Field */}
            <div className="space-y-1">
              <Label htmlFor="username">Username</Label>
              <Input id="username" name="username" value={formData.username} onChange={handleChange} disabled={submitting} required />
              {errors.username && <p className="text-xs text-destructive mt-1">{errors.username[0]}</p>}
            </div>

            {/* Password Field */}
            <div className="space-y-1">
              <Label htmlFor="password">Password</Label>
              <Input id="password" name="password" type="password" value={formData.password} onChange={handleChange} disabled={submitting} required />
              {errors.password && <p className="text-xs text-destructive mt-1">{errors.password[0]}</p>}
            </div>

            {/* Password Confirmation Field */}
            <div className="space-y-1">
              <Label htmlFor="password_confirmation">Confirm Password</Label>
              <Input id="password_confirmation" name="password_confirmation" type="password" value={formData.password_confirmation} onChange={handleChange} disabled={submitting} required />
              {errors.password_confirmation && <p className="text-xs text-destructive mt-1">{errors.password_confirmation[0]}</p>}
            </div>
            
            {/* Role Field */}
            <div className="space-y-1">
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
              {errors.role && <p className="text-xs text-destructive mt-1">{errors.role[0]}</p>}
            </div>

            {/* Bio Field */}
            <div className="space-y-1">
              <Label htmlFor="bio">Bio (Optional)</Label>
              <Textarea id="bio" name="bio" value={formData.bio} onChange={handleChange} disabled={submitting} placeholder="Tell us a little bit about this user" />
              {errors.bio && <p className="text-xs text-destructive mt-1">{errors.bio[0]}</p>}
            </div>
          </CardContent>
          <CardFooter>
            <Button type="submit" disabled={submitting}>
              {submitting ? (
                <>
                  <Save className="mr-2 h-4 w-4 animate-spin" /> Creating User...
                </>
              ) : (
                <>
                  <Save className="mr-2 h-4 w-4" /> Create User
                </>
              )}
            </Button>
          </CardFooter>
        </form>
      </Card>
    </div>
  );
}