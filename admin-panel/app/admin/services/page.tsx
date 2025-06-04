'use client';

import React, { useEffect, useState, useCallback, useMemo } from 'react';
import { fetchServices } from '@/lib/apiClient';
import { Service, PaginatedServicesResponse } from '@/lib/types';
import { DataTable } from '@/components/ui/data-table';
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import {
  ColumnDef,
  SortingState,
  getCoreRowModel,
  getSortedRowModel,
  useReactTable
} from '@tanstack/react-table';
import { ArrowUpDown } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { toast } from 'sonner';
import { useRouter, useSearchParams } from 'next/navigation';
import { PaginationControls } from '@/components/admin/pagination-controls';

const ServicesPage = () => {
  const router = useRouter();
  const searchParams = useSearchParams();
  const [servicesResponse, setServicesResponse] = useState<PaginatedServicesResponse | null>(null);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [sorting, setSorting] = useState<SortingState>([]);

  const currentPage = Number(searchParams.get('page')) || 1;
  const currentLimit = Number(searchParams.get('limit')) || 10;

  const loadServices = useCallback(async (page: number, limit: number) => {
    setIsLoading(true);
    setError(null);
    try {
      const data = await fetchServices(page, limit);
      setServicesResponse(data);
    } catch (err) {
      console.error('Failed to fetch services:', err);
      setError('Failed to load services. Please try again.');
      toast.error('Failed to load services.');
    } finally {
      setIsLoading(false);
    }
  }, []);

  useEffect(() => {
    loadServices(currentPage, currentLimit);
  }, [currentPage, currentLimit, loadServices]);

  const handlePageChange = (newPage: number) => {
    router.push(`/admin/services?page=${newPage}&limit=${currentLimit}`);
  };

  const columns = useMemo<ColumnDef<Service>[]>(() => [
    {
      accessorKey: 'id',
      header: ({ column }) => (
        <div className="text-center">
          <Button
            variant="ghost"
            onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}
          >
            ID
            <ArrowUpDown className="ml-2 h-4 w-4" />
          </Button>
        </div>
      ),
      cell: ({ row }) => <div className="text-center">{row.getValue('id')}</div>,
    },
    {
      accessorKey: 'title',
      header: 'Service Title',
    },
    {
      accessorKey: 'artist_name',
      header: 'Artist',
      cell: ({ row }) => row.original.artist_name || 'N/A',
    },
    {
      accessorKey: 'category_name',
      header: 'Category',
      cell: ({ row }) => row.original.category_name || 'N/A',
    },
    {
      accessorKey: 'price',
      header: () => <div className="text-center">Price</div>,
      cell: ({ row }) => {
        const amount = parseFloat(String(row.getValue('price')));
        const formatted = new Intl.NumberFormat('id-ID', {
          style: 'currency',
          currency: 'IDR',
        }).format(amount);
        return <div className="text-center font-medium">{formatted}</div>;
      },
    },
    {
      accessorKey: 'created_at',
      header: 'Date Created',
      cell: ({ row }) => {
        const date = new Date(row.getValue('created_at'));
        return date.toLocaleDateString('id-ID', {
          year: 'numeric',
          month: 'long',
          day: 'numeric',
        });
      },
    },
  ], []);

  const memoizedData = useMemo(() => servicesResponse?.data || [], [servicesResponse?.data]);

  const table = useReactTable({
    data: memoizedData,
    columns,
    state: { sorting },
    onSortingChange: setSorting,
    getCoreRowModel: getCoreRowModel(),
    getSortedRowModel: getSortedRowModel(),
  });

  if (isLoading && !servicesResponse) {
    return <div className="container mx-auto py-10 text-center">Loading services...</div>;
  }

  if (error) {
    return <div className="container mx-auto py-10 text-red-500 text-center">{error}</div>;
  }

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold tracking-tight">Service Management</h1>
          <p className="text-muted-foreground">Monitor and manage all services on the platform.</p>
        </div>
        {/* TODO: Add "Add New Service" button if required */}
      </div>
      
      <Card>
        <CardHeader>
          <CardTitle>All Services</CardTitle>
          {servicesResponse?.meta && (
            <CardDescription>
              Page {servicesResponse.meta.current_page} of {servicesResponse.meta.last_page} (Total: {servicesResponse.meta.total})
            </CardDescription>
          )}
        </CardHeader>
        <CardContent>
          {(!servicesResponse || memoizedData.length === 0) && !isLoading ? (
            <div className="py-10 text-center">No services found.</div>
          ) : (
            <DataTable
              table={table}
              columns={columns}
            />
          )}
          {servicesResponse?.meta && servicesResponse.meta.last_page > 1 && (
            <PaginationControls
              currentPage={servicesResponse.meta.current_page}
              totalPages={servicesResponse.meta.last_page}
              onPageChange={handlePageChange}
              itemsPerPage={servicesResponse.meta.per_page}
              totalItems={servicesResponse.meta.total}
            />
          )}
        </CardContent>
      </Card>
    </div>
  );
};

export default ServicesPage;
