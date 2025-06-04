'use client';

import { useEffect, useState } from 'react';
import { useParams, useRouter } from 'next/navigation';
import Link from 'next/link';
import apiClient, { fetchArtist, updateArtist } from '@/lib/apiClient';
import { Artist } from '@/lib/types';
import { useForm, Controller } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { toast } from 'sonner';

import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import { Skeleton } from '@/components/ui/skeleton';
import { ArrowLeft, Save } from 'lucide-react';
import { Textarea } from '@/components/ui/textarea'; // Assuming you might want a textarea for portfolio or bio later

const artistFormSchema = z.object({
  portfolio_link: z.string().url({ message: "Please enter a valid URL." }).max(255).nullable().or(z.literal('')),
  rating: z.preprocess(
    (val) => (val === '' || val === null || val === undefined) ? null : Number(val),
    z.number().min(0).max(5, { message: "Rating must be between 0 and 5." }).nullable()
  ),
  is_verified: z.boolean(),
});

type ArtistFormData = z.infer<typeof artistFormSchema>;

export default function EditArtistPage() {
  const params = useParams();
  const router = useRouter();
  const artistId = params.id as string;

  const [artist, setArtist] = useState<Artist | null>(null);
  const [loading, setLoading] = useState(true);
  const [submitLoading, setSubmitLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const { control, handleSubmit, reset, formState: { errors } } = useForm<ArtistFormData>({
    resolver: zodResolver(artistFormSchema),
    defaultValues: {
      portfolio_link: '',
      rating: null,
      is_verified: false,
    },
  });

  useEffect(() => {
    if (artistId) {
      const loadArtist = async () => {
        setLoading(true);
        setError(null);
        try {
          const fetchedArtist = await fetchArtist(Number(artistId));
          setArtist(fetchedArtist);
          reset({
            portfolio_link: fetchedArtist.portfolio_link || '',
            rating: fetchedArtist.rating,
            is_verified: fetchedArtist.is_verified,
          });
        } catch (err: any) {
          console.error('Failed to fetch artist:', err);
          setError(err.response?.data?.message || 'Failed to load artist details.');
          toast.error(err.response?.data?.message || 'Failed to load artist details.');
        } finally {
          setLoading(false);
        }
      };
      loadArtist();
    }
  }, [artistId, reset]);

  const onSubmit = async (data: ArtistFormData) => {
    if (!artist) return;
    setSubmitLoading(true);
    setError(null);
    try {
      const dataToSubmit = {
        ...data,
        portfolio_link: data.portfolio_link === '' ? null : data.portfolio_link,
        rating: data.rating === null || isNaN(Number(data.rating)) ? null : Number(data.rating)
      };
      await updateArtist(artist.id, dataToSubmit);
      toast.success('Artist profile updated successfully!');
      router.push('/admin/artists'); // Or back to artist detail if one exists
    } catch (err: any) {
      console.error('Failed to update artist:', err);
      const apiError = err.response?.data?.message || 'Failed to update artist profile.';
      setError(apiError);
      toast.error(apiError);
      if (err.response?.data?.errors) {
        // Handle validation errors from Laravel if needed
        // e.g., setFormError('portfolio_link', { type: 'manual', message: err.response.data.errors.portfolio_link[0] })
      }
    } finally {
      setSubmitLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="space-y-4 p-4 md:p-8">
        <Skeleton className="h-10 w-32 mb-4" />
        <Card>
          <CardHeader><Skeleton className="h-8 w-1/2" /></CardHeader>
          <CardContent className="space-y-4">
            <Skeleton className="h-10 w-full" />
            <Skeleton className="h-10 w-full" />
            <Skeleton className="h-10 w-1/4" />
          </CardContent>
          <CardFooter><Skeleton className="h-10 w-24" /></CardFooter>
        </Card>
      </div>
    );
  }

  if (error && !artist) { // Show main error if artist data couldn't be loaded
    return (
      <div className="space-y-4 p-4 md:p-8">
        <Button variant="outline" size="sm" onClick={() => router.push('/admin/artists')} className="mb-4">
          <ArrowLeft className="mr-2 h-4 w-4" />
          Back to Artist List
        </Button>
        <p className="text-red-600">{error}</p>
      </div>
    );
  }
  
  if (!artist) {
     return (
      <div className="space-y-4 p-4 md:p-8">
         <Button variant="outline" size="sm" onClick={() => router.push('/admin/artists')} className="mb-4">
          <ArrowLeft className="mr-2 h-4 w-4" />
          Back to Artist List
        </Button>
        <p>Artist not found.</p>
      </div>
    );
  }

  return (
    <div className="space-y-6 p-4 md:p-8 pt-6">
      <Button variant="outline" size="sm" onClick={() => router.push('/admin/artists')} className="mb-4">
        <ArrowLeft className="mr-2 h-4 w-4" />
        Back to Artist List
      </Button>

      <Card>
        <CardHeader>
          <CardTitle className="text-2xl">Edit Artist: {artist.user?.name || `ID: ${artist.id}`}</CardTitle>
          <CardDescription>Update the artist's profile information.</CardDescription>
        </CardHeader>
        <form onSubmit={handleSubmit(onSubmit)}>
          <CardContent className="space-y-6">
            {error && <p className="text-sm text-red-600 bg-red-100 p-3 rounded-md">{error}</p>}
            
            <div>
              <Label htmlFor="portfolio_link">Portfolio Link</Label>
              <Controller
                name="portfolio_link"
                control={control}
                render={({ field }) => (
                  <Input 
                    id="portfolio_link" 
                    placeholder="https://example.com/artist-portfolio" 
                    {...field} 
                    value={field.value ?? ''}
                  />
                )}
              />
              {errors.portfolio_link && <p className="text-sm text-red-600 mt-1">{errors.portfolio_link.message}</p>}
            </div>

            <div>
              <Label htmlFor="rating">Rating (0-5)</Label>
              <Controller
                name="rating"
                control={control}
                render={({ field }) => (
                  <Input 
                    id="rating" 
                    type="number" 
                    step="0.1" 
                    placeholder="e.g., 4.5" 
                    {...field} 
                    value={field.value ?? ''}
                    onChange={e => field.onChange(e.target.value === '' ? null : parseFloat(e.target.value))}
                  />
                )}
              />
              {errors.rating && <p className="text-sm text-red-600 mt-1">{errors.rating.message}</p>}
            </div>

            <div className="flex items-center space-x-2">
              <Controller
                name="is_verified"
                control={control}
                render={({ field }) => (
                  <Switch
                    id="is_verified"
                    checked={field.value}
                    onCheckedChange={field.onChange}
                  />
                )}
              />
              <Label htmlFor="is_verified" className="cursor-pointer">
                Verified Artist
              </Label>
            </div>
             {errors.is_verified && <p className="text-sm text-red-600 mt-1">{errors.is_verified.message}</p>}


          </CardContent>
          <CardFooter>
            <Button type="submit" disabled={submitLoading}>
              <Save className="mr-2 h-4 w-4" />
              {submitLoading ? 'Saving...' : 'Save Changes'}
            </Button>
          </CardFooter>
        </form>
      </Card>
    </div>
  );
}