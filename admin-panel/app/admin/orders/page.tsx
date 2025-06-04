"use client"

import Link from "next/link"
import { useEffect, useState } from "react"
import { Button } from "@/components/ui/button"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table"
import { Badge } from "@/components/ui/badge"
import { Eye, AlertCircle, Loader2 } from "lucide-react"
import { fetchAdminOrders } from "@/lib/apiClient"
import { Order, PaginatedOrdersResponse } from "@/lib/types" // Make sure Order and PaginatedOrdersResponse are correctly defined and imported
import { format } from 'date-fns';

// Helper function to determine badge variant based on order status
function getOrderStatusVariant(status?: string): "default" | "secondary" | "outline" | "destructive" {
  switch (status?.toLowerCase()) {
    case "completed":
    case "paid": // Assuming 'paid' can be an order status if payment directly completes it
      return "default" // Green or success like
    case "processing":
    case "in_progress": 
    case "shipped":
      return "secondary" // Blue or info like
    case "pending":
      return "outline" // Yellow or warning like
    case "cancelled":
    case "failed": // Assuming 'failed' can be an order status
    case "revision": 
      return "destructive" // Red or error like
    default:
      return "outline"
  }
}

// Helper function to determine badge variant based on payment status
function getPaymentStatusVariant(status?: string): "default" | "secondary" | "outline" | "destructive" {
  switch (status?.toLowerCase()) {
    case "paid":
      return "default"
    case "pending":
      return "secondary"
    case "failed":
    case "refunded": 
      return "destructive"
    default:
      return "outline"
  }
}

export default function OrdersPage() {
  const [ordersResponse, setOrdersResponse] = useState<PaginatedOrdersResponse | null>(null);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [currentPage, setCurrentPage] = useState(1);
  const itemsPerPage = 10; // Or make this configurable

  useEffect(() => {
    const loadOrders = async () => {
      setIsLoading(true);
      setError(null);
      try {
        const data = await fetchAdminOrders(currentPage, itemsPerPage);
        setOrdersResponse(data);
      } catch (err) {
        console.error("Failed to fetch orders:", err);
        setError("Failed to load orders. Please try again later.");
      } finally {
        setIsLoading(false);
      }
    };
    loadOrders();
  }, [currentPage]);

  const orders = ordersResponse?.data || [];

  const handleNextPage = () => {
    if (ordersResponse?.meta && currentPage < ordersResponse.meta.last_page) {
      setCurrentPage(currentPage + 1);
    }
  };

  const handlePreviousPage = () => {
    if (currentPage > 1) {
      setCurrentPage(currentPage - 1);
    }
  };

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold tracking-tight">Order Management</h1>
          <p className="text-muted-foreground">Monitor and track all orders and their payment status.</p>
        </div>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>All Orders</CardTitle>
          <CardDescription>
            A comprehensive overview of all orders with their current status and payment information.
          </CardDescription>
        </CardHeader>
        <CardContent>
          {isLoading && (
            <div className="flex items-center justify-center py-10">
              <Loader2 className="h-8 w-8 animate-spin text-muted-foreground" />
              <p className="ml-2">Loading orders...</p>
            </div>
          )}
          {error && (
            <div className="flex flex-col items-center justify-center py-10 text-red-600">
              <AlertCircle className="h-8 w-8" />
              <p className="mt-2">{error}</p>
              <Button onClick={() => {
                  setIsLoading(true); // Re-trigger loading state
                  fetchAdminOrders(currentPage, itemsPerPage)
                    .then(setOrdersResponse)
                    .catch(err => {
                        console.error("Failed to refetch orders:", err);
                        setError("Failed to load orders. Please try again later.");
                    })
                    .finally(() => setIsLoading(false));
                }} 
                variant="outline" 
                className="mt-4"
              >
                Try Again
              </Button>
            </div>
          )}
          {!isLoading && !error && orders.length === 0 && (
            <div className="text-center py-10 text-muted-foreground">
              <p>No orders found.</p>
            </div>
          )}
          {!isLoading && !error && orders.length > 0 && (
            <>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>Order ID</TableHead>
                    <TableHead>Customer</TableHead>
                    <TableHead>Commission Title</TableHead>
                    <TableHead>Artist</TableHead>
                    <TableHead>Order Status</TableHead>
                    <TableHead>Payment Status</TableHead>
                    <TableHead>Total Price</TableHead>
                    <TableHead>Order Date</TableHead>
                    <TableHead>Actions</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {orders.map((order: Order) => (
                    <TableRow key={order.id}>
                      <TableCell className="font-medium">{order.order_code || order.id}</TableCell>
                      <TableCell>{order.customer_name || order.user?.name || 'N/A'}</TableCell>
                      <TableCell>
                        <div>
                          <p className="font-medium">{order.commission_title || order.commission?.title || 'N/A'}</p>
                          {order.commission?.id && 
                            <Link href={`/admin/commissions/${order.commission.id}`} className="text-xs text-blue-500 hover:underline">
                              View Commission
                            </Link>
                          }
                        </div>
                      </TableCell>
                      <TableCell>{order.artist_name || order.commission?.user?.name || 'N/A'}</TableCell>
                      <TableCell>
                        <Badge variant={getOrderStatusVariant(order.status)} className="capitalize">
                          {order.status?.replace("_", " ") || 'N/A'}
                        </Badge>
                      </TableCell>
                      <TableCell>
                        <Badge variant={getPaymentStatusVariant(order.payment_status)} className="capitalize">
                          {order.payment_status?.replace("_", " ") || 'Pending'}
                        </Badge>
                      </TableCell>
                      <TableCell>${typeof order.total_price === 'number' ? order.total_price.toFixed(2) : order.total_price || '0.00'}</TableCell>
                      <TableCell>
                        {order.order_date ? format(new Date(order.order_date), 'PPpp') : (order.created_at ? format(new Date(order.created_at), 'PPpp') : 'N/A')}
                      </TableCell>
                      <TableCell>
                        <Button asChild variant="ghost" size="sm">
                          <Link href={`/admin/orders/${order.id}`}>
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
                  disabled={currentPage === 1 || isLoading}
                >
                  Previous
                </Button>
                <Button
                  variant="outline"
                  size="sm"
                  onClick={handleNextPage}
                  disabled={!ordersResponse?.meta || currentPage === ordersResponse.meta.last_page || isLoading}
                >
                  Next
                </Button>
                {ordersResponse?.meta && (
                   <span className="text-sm text-muted-foreground">
                     Page {ordersResponse.meta.current_page} of {ordersResponse.meta.last_page}
                   </span>
                )}
              </div>
            </>
          )}
        </CardContent>
      </Card>
    </div>
  )
}
