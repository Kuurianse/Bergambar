'use client';

import React, { useState } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea'; // Assuming Textarea component exists
import { Label } from '@/components/ui/label';
import { toast } from 'sonner';
import { createCategory } from '@/lib/apiClient';
import { Category } from '@/lib/types'; // Assuming Category type is defined
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { ArrowLeft } from 'lucide-react';

const CreateCategoryPage = () => {
  const router = useRouter();
  const [name, setName] = useState('');
  const [description, setDescription] = useState('');
  const [slug, setSlug] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const [errors, setErrors] = useState<{ [key: string]: string[] }>({});

  const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setIsLoading(true);
    setErrors({});

    try {
      const categoryData: { name: string; description?: string; slug?: string } = { name };
      if (description) categoryData.description = description;
      if (slug) categoryData.slug = slug;
      // If slug is empty, backend will generate it based on the name

      await createCategory(categoryData);
      toast.success('Category created successfully!');
      router.push('/admin/categories');
    } catch (err: any) {
      console.error('Failed to create category:', err);
      if (err.response && err.response.data && err.response.data.errors) {
        setErrors(err.response.data.errors);
        toast.error('Failed to create category. Please check the form for errors.');
      } else {
        toast.error(err.response?.data?.message || 'An unexpected error occurred.');
      }
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="container mx-auto py-10">
      <div className="mb-6">
        <Button variant="outline" size="sm" onClick={() => router.back()}>
          <ArrowLeft className="mr-2 h-4 w-4" />
          Back to Categories
        </Button>
      </div>
      <Card className="max-w-2xl mx-auto">
        <CardHeader>
          <CardTitle>Create New Category</CardTitle>
          <CardDescription>Fill in the details for the new category.</CardDescription>
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
              <Label htmlFor="slug">Slug (Optional)</Label>
              <Input
                id="slug"
                value={slug}
                onChange={(e) => setSlug(e.target.value)}
                placeholder="e.g., digital-art (auto-generated if empty)"
              />
              {errors.slug && <p className="text-sm text-red-500 mt-1">{errors.slug.join(', ')}</p>}
              <p className="text-xs text-muted-foreground">
                If left empty, the slug will be automatically generated from the name.
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
            <Button type="submit" disabled={isLoading}>
              {isLoading ? 'Creating...' : 'Create Category'}
            </Button>
          </CardFooter>
        </form>
      </Card>
    </div>
  );
};

export default CreateCategoryPage;