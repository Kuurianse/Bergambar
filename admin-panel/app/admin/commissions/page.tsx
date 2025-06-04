'use client';

import Link from "next/link";
import { useEffect, useState } from "react";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Badge } from "@/components/ui/badge";
import { Eye, AlertCircle } from "lucide-react";
import { fetchCommissions } from "@/lib/apiClient";
import type { Commission, PaginatedCommissionsResponse } from "@/lib/types"; // Ensure this path is correct

// Helper to format date strings (optional, can be expanded)
const formatDate = (dateString: string | undefined) => {
  if (!dateString) return 'N/A';
  return new Date(dateString).toLocaleDateString();
};

// Helper to format price (optional)
const formatPrice = (price: number | string | undefined) => {
  if (price === undefined || price === null) return 'N/A';
  const numericPrice = typeof price === 'string' ? parseFloat(price) : price;
  return `Rp${numericPrice.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}`;
};

// Function to determine badge color based on status (can be customized)
function getStatusBadgeVariant(status: string | undefined): "default" | "secondary" | "outline" | "destructive" {
  switch (status?.toLowerCase()) {
    case "pending":
      return "outline";
    case "accepted":
    case "in_progress":
    case "active": // Example public status
      return "secondary";
    case "completed":
    case "published": // Example public status
      return "default";
    case "revision_requested":
    case "on_hold":
      return "destructive"; // Or another color like 'warning' if you add it
    case "cancelled":
    case "rejected":
    case "hidden": // Example public status
      return "destructive";
    default:
      return "outline";
  }
}


export default function CommissionsPage() {
  const [commissionsData, setCommissionsData] = useState<PaginatedCommissionsResponse | null>(null);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [currentPage, setCurrentPage] = useState(1);
  const itemsPerPage = 15; // Or get from commissionsData.meta.per_page

  useEffect(() => {
    const loadCommissions = async () => {
      setIsLoading(true);
      setError(null);
      try {
        const data = await fetchCommissions(currentPage, itemsPerPage);
        setCommissionsData(data);
      } catch (err) {
        console.error("Failed to fetch commissions:", err);
        setError("Failed to load commissions. Please try again later.");
      } finally {
        setIsLoading(false);
      }
    };
    loadCommissions();
  }, [currentPage]);

  const handleNextPage = () => {
    if (commissionsData?.links?.next) {
      setCurrentPage(prev => prev + 1);
    }
  };

  const handlePreviousPage = () => {
    if (commissionsData?.links?.prev) {
      setCurrentPage(prev => prev - 1);
    }
  };

  if (isLoading) {
    return <div className="flex justify-center items-center h-64">Loading commissions...</div>;
  }

  if (error) {
    return (
      <div className="flex flex-col items-center justify-center h-64 text-red-600">
        <AlertCircle className="w-12 h-12 mb-4" />
        <p className="text-xl">{error}</p>
      </div>
    );
  }

  if (!commissionsData || commissionsData.data.length === 0) {
    return (
      <div className="space-y-6">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold tracking-tight">Commission Management</h1>
            <p className="text-muted-foreground">Monitor and manage all commissions.</p>
          </div>
        </div>
        <Card>
          <CardHeader>
            <CardTitle>All Commissions</CardTitle>
            <CardDescription>No commissions found.</CardDescription>
          </CardHeader>
          <CardContent>
            <p>There are currently no commissions to display.</p>
          </CardContent>
        </Card>
      </div>
    );
  }

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold tracking-tight">Commission Management</h1>
          <p className="text-muted-foreground">Monitor and manage all commissions across the platform.</p>
        </div>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>All Commissions</CardTitle>
          <CardDescription>
            Page {commissionsData.meta?.current_page} of {commissionsData.meta?.last_page} (Total: {commissionsData.meta?.total})
          </CardDescription>
        </CardHeader>
        <CardContent>
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>ID</TableHead>
                <TableHead>Title</TableHead>
                <TableHead>Artist</TableHead>
                <TableHead>Service</TableHead>
                <TableHead>Status (Internal)</TableHead>
                <TableHead>Status (Public)</TableHead>
                <TableHead>Price</TableHead>
                <TableHead>Created At</TableHead>
                <TableHead>Actions</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {commissionsData.data.map((commission: Commission) => (
                <TableRow key={commission.id}>
                  <TableCell className="font-medium">{commission.id}</TableCell>
                  <TableCell>{commission.title}</TableCell>
                  <TableCell>{commission.user?.name || 'N/A'}</TableCell>
                  <TableCell>{commission.service?.name || 'N/A'}</TableCell>
                  <TableCell>
                    <Badge variant={getStatusBadgeVariant(commission.status)}>{commission.status || 'N/A'}</Badge>
                  </TableCell>
                  <TableCell>
                    <Badge variant={getStatusBadgeVariant(commission.public_status)}>{commission.public_status || 'N/A'}</Badge>
                  </TableCell>
                  <TableCell>{formatPrice(commission.total_price)}</TableCell>
                  <TableCell>{formatDate(commission.created_at)}</TableCell>
                  <TableCell>
                    <Button asChild variant="ghost" size="sm">
                      <Link href={`/admin/commissions/${commission.id}`}>
                        <Eye className="h-4 w-4" />
                        <span className="sr-only">View Details</span>
                      </Link>
                    </Button>
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
          <div className="flex items-center justify-end space-x-2 py-4">
            <Button
              variant="outline"
              size="sm"
              onClick={handlePreviousPage}
              disabled={!commissionsData.links?.prev}
            >
              Previous
            </Button>
            <Button
              variant="outline"
              size="sm"
              onClick={handleNextPage}
              disabled={!commissionsData.links?.next}
            >
              Next
            </Button>
          </div>
        </CardContent>
      </Card>
    </div>
  );
}
