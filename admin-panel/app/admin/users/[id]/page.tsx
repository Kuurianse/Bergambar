'use client';

import { useEffect, useState } from 'react';
import { useParams, useRouter } from 'next/navigation';
import Link from 'next/link';
import apiClient, { promoteUserToArtist } from '@/lib/apiClient';
import { User, Artist } from '@/lib/types'; // Assuming User type is defined here
import { ArrowLeft, UserPlus, Edit3, ExternalLink } from 'lucide-react'; // Changed from @radix-ui/react-icons
import { toast } from 'sonner';

import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Skeleton } from '@/components/ui/skeleton'; // For loading state
import Image from 'next/image';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
  DialogClose,
} from "@/components/ui/dialog";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

interface UserDetails extends User {
  // Add any specific fields from UserResource if not in base User type
  // For example, if UserResource has more detailed timestamps or related data
  // For now, we assume User type in types.ts is sufficient
}

export default function UserDetailPage() {
  const params = useParams();
  const router = useRouter();
  const userId = params.id as string;

  const [user, setUser] = useState<UserDetails | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  // State for Promote to Artist Dialog
  const [isPromoteDialogOpen, setIsPromoteDialogOpen] = useState(false);
  const [portfolioLink, setPortfolioLink] = useState('');
  const [promoteLoading, setPromoteLoading] = useState(false);
  const [promoteError, setPromoteError] = useState<string | null>(null);

  const fetchUserDetails = async () => {
    if (!userId) return;
    setLoading(true);
    setError(null);
    try {
      const response = await apiClient.get<{ data: UserDetails }>(`/api-proxy/api/v1/admin/users/${userId}`);
      setUser(response.data.data);
    } catch (err: any) {
      console.error('Error fetching user:', err);
      if (err.response) {
        if (err.response.status === 404) {
          setError(`User with ID ${userId} not found.`);
        } else {
          setError(err.response.data?.message || `API error: ${err.response.status}`);
        }
      } else {
        setError('Failed to load user details. Check network or API proxy.');
      }
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchUserDetails();
  }, [userId]);

  const handlePromoteToArtist = async () => {
    if (!user) return;
    setPromoteLoading(true);
    setPromoteError(null);
    try {
      const newArtist = await promoteUserToArtist(user.id, { portfolio_link: portfolioLink });
      toast.success(`User ${user.name} has been promoted to artist.`);
      setUser(prevUser => prevUser ? { ...prevUser, is_artist: true, artist_id: newArtist.data.id } : null);
      setIsPromoteDialogOpen(false);
      setPortfolioLink('');
      // Optionally, refetch user details to get the most up-to-date artist info
      // fetchUserDetails();
    } catch (err: any) {
      console.error('Error promoting user:', err);
      const errorMessage = err.response?.data?.message || 'Failed to promote user.';
      setPromoteError(errorMessage);
      toast.error(errorMessage);
    } finally {
      setPromoteLoading(false);
    }
  };


  if (loading) {
    return (
      <div className="space-y-4 p-4 md:p-8">
        <Button variant="outline" size="sm" onClick={() => router.back()} className="mb-4">
          <ArrowLeft className="mr-2 h-4 w-4" />
          Back
        </Button>
        <Card>
          <CardHeader>
            <Skeleton className="h-8 w-3/4" />
            <Skeleton className="h-4 w-1/2" />
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="flex items-center space-x-4">
              <Skeleton className="h-24 w-24 rounded-full" />
              <div className="space-y-2">
                <Skeleton className="h-6 w-48" />
                <Skeleton className="h-4 w-64" />
              </div>
            </div>
            <Skeleton className="h-4 w-full" />
            <Skeleton className="h-4 w-full" />
            <Skeleton className="h-4 w-3/4" />
            <Skeleton className="h-4 w-1/2" />
          </CardContent>
          <CardFooter>
            <Skeleton className="h-10 w-24" />
          </CardFooter>
        </Card>
      </div>
    );
  }

  if (error) {
    return (
      <div className="space-y-4 p-4 md:p-8">
        <Button variant="outline" size="sm" onClick={() => router.back()} className="mb-4">
          <ArrowLeft className="mr-2 h-4 w-4" />
          Back
        </Button>
        <Card>
          <CardHeader>
            <CardTitle>Error</CardTitle>
          </CardHeader>
          <CardContent>
            <p className="text-red-600">{error}</p>
          </CardContent>
        </Card>
      </div>
    );
  }

  if (!user) {
    return (
      <div className="space-y-4 p-4 md:p-8">
         <Button variant="outline" size="sm" onClick={() => router.back()} className="mb-4">
          <ArrowLeft className="mr-2 h-4 w-4" />
          Back
        </Button>
        <p>User not found.</p>
      </div>
    );
  }

  return (
    <div className="space-y-6 p-4 md:p-8 pt-6">
      <Button variant="outline" size="sm" onClick={() => router.push('/admin/users')} className="mb-4">
        <ArrowLeft className="mr-2 h-4 w-4" />
        Back to User List
      </Button>

      <Card>
        <CardHeader>
          <CardTitle className="text-2xl">User Details: {user.name}</CardTitle>
          <CardDescription>Viewing information for user ID: {user.id}</CardDescription>
        </CardHeader>
        <CardContent className="space-y-6">
          <div className="flex flex-col sm:flex-row items-start space-y-4 sm:space-y-0 sm:space-x-6">
            {user.profile_picture ? (
              <Image
                src={user.profile_picture} // Assuming this is a full URL or can be resolved
                alt={`${user.name}'s profile picture`}
                width={128}
                height={128}
                className="rounded-full object-cover border"
              />
            ) : (
              <div className="h-32 w-32 rounded-full bg-muted flex items-center justify-center text-muted-foreground">
                No Image
              </div>
            )}
            <div className="space-y-1 flex-1">
              <h2 className="text-xl font-semibold">{user.name}</h2>
              <p className="text-sm text-muted-foreground">{user.email}</p>
              {user.username && <p className="text-sm text-muted-foreground">@{user.username}</p>}
            </div>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <h3 className="font-medium mb-1">Role</h3>
              <div><Badge variant={user.role === 'admin' ? 'default' : 'secondary'}>{user.role}</Badge></div>
            </div>
            <div>
              <h3 className="font-medium mb-1">Status</h3>
              <p>
                <Badge variant={user.is_active ? 'default' : 'destructive'}>
                  {user.is_active ? 'Active' : 'Inactive'}
                </Badge>
              </p>
            </div>
            {user.bio && (
              <div className="md:col-span-2">
                <h3 className="font-medium mb-1">Bio</h3>
                <p className="text-sm text-muted-foreground whitespace-pre-wrap">{user.bio}</p>
              </div>
            )}
            <div>
              <h3 className="font-medium mb-1">Joined</h3>
              <p className="text-sm text-muted-foreground">{new Date(user.created_at).toLocaleDateString()}</p>
            </div>
            <div>
              <h3 className="font-medium mb-1">Last Updated</h3>
              <p className="text-sm text-muted-foreground">{new Date(user.updated_at).toLocaleDateString()}</p>
            </div>
            {/* Artist Information Section */}
            <div className="md:col-span-2 border-t pt-4 mt-4">
              <h3 className="text-lg font-semibold mb-2">Artist Information</h3>
              {user.is_artist && user.artist_id ? (
                <div className="space-y-2">
                  <p className="text-sm">
                    This user is an Artist.
                  </p>
                  <Link href={`/admin/artists/${user.artist_id}/edit`}>
                    <Button variant="outline" size="sm">
                      <Edit3 className="mr-2 h-4 w-4" />
                      Edit Artist Profile
                    </Button>
                  </Link>
                </div>
              ) : (
                <div className="space-y-2">
                  <p className="text-sm text-muted-foreground">This user is not yet an artist.</p>
                  <Dialog open={isPromoteDialogOpen} onOpenChange={setIsPromoteDialogOpen}>
                    <DialogTrigger asChild>
                      <Button variant="default" size="sm">
                        <UserPlus className="mr-2 h-4 w-4" />
                        Promote to Artist
                      </Button>
                    </DialogTrigger>
                    <DialogContent className="sm:max-w-[425px]">
                      <DialogHeader>
                        <DialogTitle>Promote {user.name} to Artist</DialogTitle>
                        <DialogDescription>
                          Enter the artist's portfolio link (optional). Click promote when you're done.
                        </DialogDescription>
                      </DialogHeader>
                      <div className="grid gap-4 py-4">
                        <div className="grid grid-cols-4 items-center gap-4">
                          <Label htmlFor="portfolioLink" className="text-right">
                            Portfolio Link
                          </Label>
                          <Input
                            id="portfolioLink"
                            value={portfolioLink}
                            onChange={(e) => setPortfolioLink(e.target.value)}
                            className="col-span-3"
                            placeholder="https://example.com/portfolio"
                          />
                        </div>
                        {promoteError && <p className="col-span-4 text-sm text-red-600">{promoteError}</p>}
                      </div>
                      <DialogFooter>
                        <DialogClose asChild>
                           <Button type="button" variant="outline" onClick={() => { setPortfolioLink(''); setPromoteError(null);}}>Cancel</Button>
                        </DialogClose>
                        <Button type="button" onClick={handlePromoteToArtist} disabled={promoteLoading}>
                          {promoteLoading ? 'Promoting...' : 'Promote'}
                        </Button>
                      </DialogFooter>
                    </DialogContent>
                  </Dialog>
                </div>
              )}
            </div>
          </div>
        </CardContent>
        <CardFooter className="flex justify-start gap-2">
          <Link href={`/admin/users/${user.id}/edit`}>
            <Button>
              <Edit3 className="mr-2 h-4 w-4" />
              Edit User
            </Button>
          </Link>
        </CardFooter>
      </Card>
    </div>
  );
}
