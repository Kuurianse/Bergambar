'use client';

import React, { useState, useEffect, useCallback } from 'react';
import { useRouter, useParams } from 'next/navigation';
import Link from 'next/link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import { toast } from 'sonner';
import { fetchCategory, updateCategory } from '@/lib/apiClient';
import { Category } from '@/lib/types';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { ArrowLeft, Loader2 } from 'lucide-react';

const EditCategoryPage = () => {
  const router = useRouter();
  const params = useParams();
  const categoryId = params.id as string;

  const [category, setCategory] = useState<Category | null>(null);
  const [name, setName] = useState('');
  const [description, setDescription] = useState('');
  const [slug, setSlug] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const [isFetching, setIsFetching] = useState(true);
  const [errors, setErrors] = useState<{ [key: string]: string[] }>({});

  const loadCategory = useCallback(async () => {
    if (!categoryId) return;
    setIsFetching(true);
    try {
      const fetchedCategory = await fetchCategory(Number(categoryId));
      setCategory(fetchedCategory);
      setName(fetchedCategory.name);
      setDescription(fetchedCategory.description || '');
      setSlug(fetchedCategory.slug || '');
    } catch (err) {
      console.error('Failed to fetch category:', err);
      toast.error('Failed to load category details.');
      router.push('/admin/categories'); // Redirect if category not found or error
    } finally {
      setIsFetching(false);
    }
  }, [categoryId, router]);

  useEffect(() => {
    loadCategory();
  }, [loadCategory]);

  const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    if (!category) return;

    setIsLoading(true);
    setErrors({});

    try {
      const categoryData: { name?: string; description?: string; slug?: string } = {};
      if (name !== category.name) categoryData.name = name;
      if (description !== (category.description || '')) categoryData.description = description;
      // Allow sending an empty string for slug to let backend regenerate or clear it
      if (slug !== (category.slug || '')) categoryData.slug = slug;


      if (Object.keys(categoryData).length === 0) {
        toast.info("No changes detected.");
        setIsLoading(false);
        return;
      }

      await updateCategory(category.id, categoryData);
      toast.success('Category updated successfully!');
      router.push('/admin/categories');
    } catch (err: any) {
      console.error('Failed to update category:', err);
      if (err.response && err.response.data && err.response.data.errors) {
        setErrors(err.response.data.errors);
        toast.error('Failed to update category. Please check the form for errors.');
      } else {
        toast.error(err.response?.data?.message || 'An unexpected error occurred.');
      }
    } finally {
      setIsLoading(false);
    }
  };

  if (isFetching) {
    return (
      <div className="flex justify-center items-center h-screen">
        <Loader2 className="h-8 w-8 animate-spin text-primary" />
        <p className="ml-2">Loading category details...</p>
      </div>
    );
  }

  if (!category) {
    // This case should ideally be handled by the redirect in loadCategory's catch block
    return <div className="p-4 text-red-500 text-center">Category not found or failed to load.</div>;
  }

  return (
    <div className="container mx-auto py-10">
      <div className="mb-6">
        <Button variant="outline" size="sm" onClick={() => router.push('/admin/categories')}>
          <ArrowLeft className="mr-2 h-4 w-4" />
          Back to Categories
        </Button>
      </div>
      <Card className="max-w-2xl mx-auto">
        <CardHeader>
          <CardTitle>Edit Category: {category.name}</CardTitle>
          <CardDescription>Update the details for this category.</CardDescription>
        </CardHeader>
        <form onSubmit={handleSubmit}>
          <CardContent className="space-y-6">
            <div className="space-y-2">
              <Label htmlFor="name">Name</Label>
              <Input
                id="name"
                value={name}
                onChange={(e) => setName(e.target.value)}
                placeholder="e.g., Digital Art"
                required
              />
              {errors.name && <p className="text-sm text-red-500 mt-1">{errors.name.join(', ')}</p>}
            </div>

            <div className="space-y-2">
              <Label htmlFor="slug">Slug</Label>
              <Input
                id="slug"
                value={slug}
                onChange={(e) => setSlug(e.target.value)}
                placeholder="e.g., digital-art (auto-updated if name changes and slug is empty)"
              />
              {errors.slug && <p className="text-sm text-red-500 mt-1">{errors.slug.join(', ')}</p>}
               <p className="text-xs text-muted-foreground">
                If left empty and name changes, slug might be auto-regenerated. To clear, submit an empty value.
              </p>
            </div>

            <div className="space-y-2">
              <Label htmlFor="description">Description (Optional)</Label>
              <Textarea
                id="description"
                value={description}
                onChange={(e) => setDescription(e.target.value)}
                placeholder="A brief description of the category."
                rows={4}
              />
              {errors.description && <p className="text-sm text-red-500 mt-1">{errors.description.join(', ')}</p>}
            </div>
          </CardContent>
          <CardFooter>
            <Button type="submit" disabled={isLoading || isFetching}>
              {isLoading ? 'Updating...' : 'Update Category'}
            </Button>
          </CardFooter>
        </form>
      </Card>
    </div>
  );
};

export default EditCategoryPage;