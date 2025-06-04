'use client';

import React, { useEffect, useState, useCallback, useMemo } from 'react';
import { useRouter, useSearchParams } from 'next/navigation';
import Link from 'next/link';
import { Button } from '@/components/ui/button';
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
import { MoreHorizontal, PlusCircle, ArrowUpDown } from 'lucide-react';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Badge } from '@/components/ui/badge';
import { toast } from 'sonner'; // Assuming sonner is used for toasts

import { fetchCategories, deleteCategory } from '@/lib/apiClient';
import type { Category, PaginatedCategoriesResponse } from '@/lib/types';
import { PaginationControls } from '@/components/admin/pagination-controls';
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

const CategoriesPage = () => {
  const router = useRouter();
  const searchParams = useSearchParams();
  const [categoriesResponse, setCategoriesResponse] = useState<PaginatedCategoriesResponse | null>(null);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [showDeleteDialog, setShowDeleteDialog] = useState(false);
  const [categoryToDelete, setCategoryToDelete] = useState<Category | null>(null);
  const [sorting, setSorting] = useState<SortingState>([]);

  const page = searchParams.get('page') ? parseInt(searchParams.get('page') as string, 10) : 1;
  const limit = searchParams.get('limit') ? parseInt(searchParams.get('limit') as string, 10) : 10;

  const loadCategories = useCallback(async (currentPage: number, currentLimit: number) => {
    setIsLoading(true);
    setError(null);
    try {
      const data = await fetchCategories(currentPage, currentLimit);
      setCategoriesResponse(data);
    } catch (err) {
      console.error('Failed to fetch categories:', err);
      setError('Failed to load categories. Please try again.');
      toast.error('Failed to load categories.');
    } finally {
      setIsLoading(false);
    }
  }, []);

  useEffect(() => {
    loadCategories(page, limit);
  }, [page, limit, loadCategories]);

  const handlePageChange = (newPage: number) => {
    router.push(`/admin/categories?page=${newPage}&limit=${limit}`);
  };

  const handleDeleteConfirmation = (category: Category) => {
    setCategoryToDelete(category);
    setShowDeleteDialog(true);
  };

  const handleDelete = async () => {
    if (!categoryToDelete) return;
    try {
      await deleteCategory(categoryToDelete.id);
      toast.success(`Category "${categoryToDelete.name}" deleted successfully.`);
      setShowDeleteDialog(false);
      setCategoryToDelete(null);
      // Refresh categories list
      loadCategories(page, limit);
    } catch (err: any) {
      console.error('Failed to delete category:', err);
      const errorMessage = err.response?.data?.message || 'Failed to delete category. Please try again.';
      toast.error(errorMessage);
      setShowDeleteDialog(false);
      setCategoryToDelete(null);
    }
  };

  const columns = useMemo<ColumnDef<Category>[]>(() => [
    {
      accessorKey: 'id',
      header: ({ column }) => (
        <Button
          variant="ghost"
          onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}
        >
          ID
          <ArrowUpDown className="ml-2 h-4 w-4" />
        </Button>
      ),
      cell: ({ row }) => <div className="text-center">{row.original.id}</div>,
    },
    {
      accessorKey: 'name',
      header: ({ column }) => (
        <Button
          variant="ghost"
          onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}
        >
          Name
          <ArrowUpDown className="ml-2 h-4 w-4" />
        </Button>
      ),
    },
    {
      accessorKey: 'slug',
      header: 'Slug',
    },
    {
      accessorKey: 'description',
      header: 'Description',
      cell: ({ row }: { row: { original: Category } }) => {
        const description = row.original.description;
        return description && description.length > 50
          ? `${description.substring(0, 50)}...`
          : description || '-';
      },
    },
    {
      accessorKey: 'services_count',
      header: 'Services',
      cell: ({ row }: { row: { original: Category } }) => row.original.services_count ?? 0,
    },
    {
      accessorKey: 'created_at',
      header: 'Created At',
      cell: ({ row }: { row: { original: Category } }) => new Date(row.original.created_at!).toLocaleDateString(),
    },
    {
      id: 'actions',
      cell: ({ row }: { row: { original: Category } }) => {
        const category = row.original;
        return (
          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button variant="ghost" className="h-8 w-8 p-0">
                <span className="sr-only">Open menu</span>
                <MoreHorizontal className="h-4 w-4" />
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
              <DropdownMenuLabel>Actions</DropdownMenuLabel>
              <DropdownMenuItem
                onClick={() => router.push(`/admin/categories/${category.id}/edit`)}
              >
                Edit
              </DropdownMenuItem>
              <DropdownMenuItem
                onClick={() => handleDeleteConfirmation(category)}
                className="text-red-600"
              >
                Delete
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        );
      },
    },
  ], [router]); // Added router to dependency array

  const memoizedData = useMemo(() => categoriesResponse?.data || [], [categoriesResponse?.data]);

  const table = useReactTable({
    data: memoizedData,
    columns,
    state: { sorting },
    onSortingChange: setSorting,
    getCoreRowModel: getCoreRowModel(),
    getSortedRowModel: getSortedRowModel(),
  });

  if (isLoading && !categoriesResponse) {
    return <div className="container mx-auto py-10 text-center">Loading categories...</div>;
  }

  if (error) {
    return <div className="p-4 text-red-500 text-center">{error}</div>;
  }
  
  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold tracking-tight">Category Management</h1>
          <p className="text-muted-foreground">Monitor and manage all categories on the platform.</p>
        </div>
        <Link href="/admin/categories/create" passHref>
          <Button>
            <PlusCircle className="mr-2 h-4 w-4" /> Add New Category
          </Button>
        </Link>
      </div>
      
      <Card>
        <CardHeader>
          <CardTitle>All Categories</CardTitle>
          {categoriesResponse?.meta && (
            <CardDescription>
              Page {categoriesResponse.meta.current_page} of {categoriesResponse.meta.last_page} (Total: {categoriesResponse.meta.total})
            </CardDescription>
          )}
        </CardHeader>
        <CardContent>
          {(!categoriesResponse || memoizedData.length === 0) && !isLoading ? (
            <div className="py-10 text-center">No categories found.</div>
          ) : (
            <DataTable
              table={table}
              columns={columns}
            />
          )}

          {categoriesResponse?.meta && categoriesResponse.meta.last_page > 1 && (
            <PaginationControls
              currentPage={categoriesResponse.meta.current_page}
              totalPages={categoriesResponse.meta.last_page}
              onPageChange={handlePageChange}
              itemsPerPage={categoriesResponse.meta.per_page}
              totalItems={categoriesResponse.meta.total}
            />
          )}
        </CardContent>
      </Card>

      {categoryToDelete && (
        <AlertDialog open={showDeleteDialog} onOpenChange={setShowDeleteDialog}>
          <AlertDialogContent>
            <AlertDialogHeader>
              <AlertDialogTitle>Are you absolutely sure?</AlertDialogTitle>
              <AlertDialogDescription>
                This action cannot be undone. This will permanently delete the
                category "{categoryToDelete.name}".
              </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
              <AlertDialogCancel onClick={() => setShowDeleteDialog(false)}>Cancel</AlertDialogCancel>
              <AlertDialogAction
                onClick={handleDelete}
                className="bg-red-600 hover:bg-red-700"
              >
                Delete
              </AlertDialogAction>
            </AlertDialogFooter>
          </AlertDialogContent>
        </AlertDialog>
      )}
    </div>
  );
};

export default CategoriesPage;
