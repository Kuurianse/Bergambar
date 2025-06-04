'use client';

import { useEffect, useState, useCallback } from 'react';
import Link from 'next/link';
import { useRouter } from 'next/navigation'; // Corrected import
import apiClient, { fetchArtists, toggleArtistVerification } from '@/lib/apiClient';
import { Artist, PaginatedArtistsResponse } from '@/lib/types';
import { toast } from 'sonner';

import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { Switch } from '@/components/ui/switch'; // For verification toggle
import { Skeleton } from '@/components/ui/skeleton';
import { Edit3, Eye, Trash2, UserPlus, ChevronLeft, ChevronRight, ChevronsLeft, ChevronsRight, ShieldCheck, ShieldOff, ExternalLink } from 'lucide-react';
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogTrigger,
} from "@/components/ui/alert-dialog";

export default function ArtistsPage() {
  const router = useRouter();
  const [artists, setArtists] = useState<Artist[]>([]);
  const [pagination, setPagination] = useState<PaginatedArtistsResponse['meta'] | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [currentPage, setCurrentPage] = useState(1);

  const loadArtists = useCallback(async (page: number) => {
    setLoading(true);
    setError(null);
    try {
      const response: PaginatedArtistsResponse = await fetchArtists(page);
      setArtists(response.data);
      setPagination(response.meta || null);
      setCurrentPage(response.meta?.current_page || 1);
    } catch (err: any) {
      console.error('Failed to fetch artists:', err);
      setError(err.response?.data?.message || 'Failed to load artists.');
      toast.error(err.response?.data?.message || 'Failed to load artists.');
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    loadArtists(currentPage);
  }, [loadArtists, currentPage]);

  const handleToggleVerification = async (artistId: number, currentStatus: boolean) => {
    try {
      await toggleArtistVerification(artistId);
      toast.success(`Artist verification status ${currentStatus ? 'removed' : 'granted'}.`);
      // Refresh the list to show updated status
      loadArtists(currentPage);
    } catch (err: any) {
      console.error('Failed to toggle artist verification:', err);
      toast.error(err.response?.data?.message || 'Failed to update verification status.');
    }
  };
  
  const renderPaginationControls = () => {
    if (!pagination || pagination.last_page <= 1) return null;

    const pageNumbers = [];
    const maxPagesToShow = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
    let endPage = Math.min(pagination.last_page, startPage + maxPagesToShow - 1);

    if (endPage - startPage + 1 < maxPagesToShow) {
        startPage = Math.max(1, endPage - maxPagesToShow + 1);
    }
    
    for (let i = startPage; i <= endPage; i++) {
      pageNumbers.push(i);
    }

    return (
      <div className="flex items-center justify-center space-x-2 mt-6">
        <Button
          variant="outline"
          size="sm"
          onClick={() => setCurrentPage(1)}
          disabled={currentPage === 1 || loading}
        >
          <ChevronsLeft className="h-4 w-4" />
        </Button>
        <Button
          variant="outline"
          size="sm"
          onClick={() => setCurrentPage(prev => Math.max(1, prev - 1))}
          disabled={currentPage === 1 || loading}
        >
          <ChevronLeft className="h-4 w-4" />
          Previous
        </Button>
        {pageNumbers.map(number => (
          <Button
            key={number}
            variant={currentPage === number ? 'default' : 'outline'}
            size="sm"
            onClick={() => setCurrentPage(number)}
            disabled={loading}
          >
            {number}
          </Button>
        ))}
        <Button
          variant="outline"
          size="sm"
          onClick={() => setCurrentPage(prev => Math.min(pagination.last_page, prev + 1))}
          disabled={currentPage === pagination.last_page || loading}
        >
          Next
          <ChevronRight className="h-4 w-4" />
        </Button>
         <Button
          variant="outline"
          size="sm"
          onClick={() => setCurrentPage(pagination.last_page)}
          disabled={currentPage === pagination.last_page || loading}
        >
          <ChevronsRight className="h-4 w-4" />
        </Button>
      </div>
    );
  };


  return (
    <div className="space-y-6 p-4 md:p-8 pt-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold tracking-tight">Artist Management</h1>
          <p className="text-muted-foreground">Monitor and manage all artist profiles on the platform.</p>
        </div>
        {/* Optional: Add "Create Artist" button if direct creation is needed */}
      </div>

      <Card>
        <CardHeader>
          <CardTitle>All Artists</CardTitle>
          {pagination && (
            <CardDescription>
              Page {pagination.current_page} of {pagination.last_page} (Total: {pagination.total})
            </CardDescription>
          )}
        </CardHeader>
        <CardContent>
          {loading && !artists.length ? (
            <div className="space-y-2">
              {[...Array(5)].map((_, i) => (
                <Skeleton key={i} className="h-12 w-full" />
              ))}
            </div>
          ) : error ? (
            <p className="text-red-600 text-center">{error}</p>
          ) : artists.length === 0 ? (
            <p className="text-center text-muted-foreground">No artists found.</p>
          ) : (
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>ID</TableHead>
                  <TableHead>Name</TableHead>
                  <TableHead>Email</TableHead>
                  <TableHead>Portfolio</TableHead>
                  <TableHead>Rating</TableHead>
                  <TableHead className="text-center">Verified</TableHead>
                  <TableHead className="text-right">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {artists.map((artist) => (
                  <TableRow key={artist.id}>
                    <TableCell>{artist.id}</TableCell>
                    <TableCell>{artist.user?.name || 'N/A'}</TableCell>
                    <TableCell>{artist.user?.email || 'N/A'}</TableCell>
                    <TableCell>
                      {artist.portfolio_link ? (
                        <a href={artist.portfolio_link} target="_blank" rel="noopener noreferrer" className="text-blue-600 hover:underline">
                          View Portfolio <ExternalLink className="inline-block h-3 w-3 ml-1" />
                        </a>
                      ) : (
                        'N/A'
                      )}
                    </TableCell>
                    <TableCell>{artist.rating !== null && typeof artist.rating === 'number' ? artist.rating.toFixed(1) : (typeof artist.rating === 'string' ? parseFloat(artist.rating).toFixed(1) : 'N/A')}</TableCell>
                    <TableCell className="text-center">
                      <Switch
                        checked={artist.is_verified}
                        onCheckedChange={() => handleToggleVerification(artist.id, artist.is_verified)}
                        aria-label={`Toggle verification for ${artist.user?.name}`}
                      />
                       <Badge variant={artist.is_verified ? 'default' : 'outline'} className="ml-2">
                        {artist.is_verified ? <ShieldCheck className="h-4 w-4 mr-1"/> : <ShieldOff className="h-4 w-4 mr-1"/>}
                        {artist.is_verified ? 'Verified' : 'Not Verified'}
                      </Badge>
                    </TableCell>
                    <TableCell className="text-right">
                      <Link href={`/admin/artists/${artist.id}/edit`}>
                        <Button variant="ghost" size="icon" title="Edit Artist">
                          <Edit3 className="h-4 w-4" />
                        </Button>
                      </Link>
                      {/* Optional: View Artist Detail Page if one exists */}
                      {/* <Link href={`/admin/artists/${artist.id}`}>
                        <Button variant="ghost" size="icon" title="View Artist">
                          <Eye className="h-4 w-4" />
                        </Button>
                      </Link> */}
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          )}
          {renderPaginationControls()}
        </CardContent>
      </Card>
    </div>
  );
}
