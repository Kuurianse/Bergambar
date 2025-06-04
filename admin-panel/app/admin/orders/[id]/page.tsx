"use client"

import { useEffect, useState } from 'react';
import { useParams, useRouter } from 'next/navigation';
import Link from 'next/link';
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle, CardFooter } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { AlertCircle, ArrowLeft, Loader2 } from 'lucide-react';
import { fetchAdminOrder } from '@/lib/apiClient';
import { Order, Payment } from '@/lib/types'; // Ensure User and Commission types are also available if needed directly
import { format } from 'date-fns';

// Helper function to determine badge variant based on order status (can be shared or defined locally)
function getOrderStatusVariant(status?: string): "default" | "secondary" | "outline" | "destructive" {
  switch (status?.toLowerCase()) {
    case "completed": case "paid": return "default";
    case "processing": case "in_progress": case "shipped": return "secondary";
    case "pending": return "outline";
    case "cancelled": case "failed": case "revision": return "destructive";
    default: return "outline";
  }
}

// Helper function to determine badge variant based on payment status
function getPaymentStatusVariant(status?: string): "default" | "secondary" | "outline" | "destructive" {
  switch (status?.toLowerCase()) {
    case "paid": return "default";
    case "pending": return "secondary";
    case "failed": case "refunded": return "destructive";
    default: return "outline";
  }
}

export default function OrderDetailPage() {
  const router = useRouter();
  const params = useParams();
  const orderId = params.id as string;

  const [order, setOrder] = useState<Order | null>(null);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    if (orderId) {
      const loadOrderDetails = async () => {
        setIsLoading(true);
        setError(null);
        try {
          const numericOrderId = parseInt(orderId, 10);
          if (isNaN(numericOrderId)) {
            setError("Invalid Order ID.");
            setIsLoading(false);
            return;
          }
          const data = await fetchAdminOrder(numericOrderId);
          setOrder(data);
        } catch (err) {
          console.error(`Failed to fetch order ${orderId}:`, err);
          setError("Failed to load order details. Please try again later.");
        } finally {
          setIsLoading(false);
        }
      };
      loadOrderDetails();
    }
  }, [orderId]);

  if (isLoading) {
    return (
      <div className="flex items-center justify-center min-h-[calc(100vh-200px)]">
        <Loader2 className="h-12 w-12 animate-spin text-muted-foreground" />
        <p className="ml-3 text-lg">Loading order details...</p>
      </div>
    );
  }

  if (error) {
    return (
      <div className="flex flex-col items-center justify-center min-h-[calc(100vh-200px)] text-red-600">
        <AlertCircle className="h-12 w-12" />
        <p className="mt-3 text-lg">{error}</p>
        <Button onClick={() => router.back()} variant="outline" className="mt-6">
          <ArrowLeft className="mr-2 h-4 w-4" /> Go Back
        </Button>
      </div>
    );
  }

  if (!order) {
    return (
      <div className="flex flex-col items-center justify-center min-h-[calc(100vh-200px)]">
        <AlertCircle className="h-12 w-12 text-muted-foreground" />
        <p className="mt-3 text-lg text-muted-foreground">Order not found.</p>
        <Button onClick={() => router.push('/admin/orders')} variant="outline" className="mt-6">
          <ArrowLeft className="mr-2 h-4 w-4" /> Back to Orders List
        </Button>
      </div>
    );
  }

  return (
    <div className="space-y-6">
      <Button onClick={() => router.push('/admin/orders')} variant="outline" size="sm">
        <ArrowLeft className="mr-2 h-4 w-4" /> Back to Orders List
      </Button>

      <Card>
        <CardHeader>
          <div className="flex justify-between items-start">
            <div>
              <CardTitle>Order Details: {order.order_code || `#${order.id}`}</CardTitle>
              <CardDescription>
                Detailed information for order placed on {order.order_date ? format(new Date(order.order_date), 'PPP') : 'N/A'}.
              </CardDescription>
            </div>
            <Badge variant={getOrderStatusVariant(order.status)} className="text-sm capitalize">
              {order.status?.replace("_", " ") || 'N/A'}
            </Badge>
          </div>
        </CardHeader>
        <CardContent className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <div className="space-y-2">
            <h3 className="font-semibold">Customer Information</h3>
            <p><strong>Name:</strong> {order.customer_name || order.user?.name || 'N/A'}</p>
            <p><strong>Email:</strong> {order.customer_email || order.user?.email || 'N/A'}</p>
            {order.user?.id && (
                 <Link href={`/admin/users/${order.user.id}`} className="text-sm text-blue-500 hover:underline">
                    View Customer Profile
                 </Link>
            )}
          </div>

          <div className="space-y-2">
            <h3 className="font-semibold">Commission Details</h3>
            <p><strong>Title:</strong> {order.commission_title || order.commission?.title || 'N/A'}</p>
            <p><strong>Artist:</strong> {order.artist_name || order.commission?.user?.name || 'N/A'}</p>
            {order.commission?.id && (
                 <Link href={`/admin/commissions/${order.commission.id}`} className="text-sm text-blue-500 hover:underline">
                    View Commission Details
                 </Link>
            )}
          </div>
          
          <div className="space-y-2">
            <h3 className="font-semibold">Order Summary</h3>
            <p><strong>Total Price:</strong> ${typeof order.total_price === 'number' ? order.total_price.toFixed(2) : order.total_price || '0.00'}</p>
            <p><strong>Order Date:</strong> {order.order_date ? format(new Date(order.order_date), 'PPpp') : (order.created_at ? format(new Date(order.created_at), 'PPpp') : 'N/A')}</p>
            <p><strong>Last Updated:</strong> {order.updated_at ? format(new Date(order.updated_at), 'PPpp') : 'N/A'}</p>
          </div>

          {order.notes && (
            <div className="space-y-2 md:col-span-2 lg:col-span-3">
              <h3 className="font-semibold">Order Notes</h3>
              <p className="text-sm text-muted-foreground p-3 bg-slate-50 rounded-md">{order.notes}</p>
            </div>
          )}

          <div className="space-y-2 md:col-span-2 lg:col-span-3">
            <h3 className="font-semibold">Payment Information</h3>
            {order.payments && order.payments.length > 0 ? (
              order.payments.map((payment: Payment, index: number) => (
                <Card key={payment.id || index} className="p-4 bg-slate-50">
                  <div className="flex justify-between items-center">
                     <p><strong>Method:</strong> {payment.payment_method || 'N/A'}</p>
                     <Badge variant={getPaymentStatusVariant(payment.status)} className="capitalize">
                        {payment.status || 'N/A'}
                     </Badge>
                  </div>
                  <p><strong>Amount:</strong> ${typeof payment.amount === 'number' ? payment.amount.toFixed(2) : payment.amount}</p>
                  <p><strong>Date:</strong> {payment.payment_date ? format(new Date(payment.payment_date), 'PPpp') : (payment.created_at ? format(new Date(payment.created_at), 'PPpp') : 'N/A')}</p>
                  {payment.transaction_id && <p><strong>Transaction ID:</strong> {payment.transaction_id}</p>}
                  {payment.notes && <p className="text-xs mt-1"><i>Notes: {payment.notes}</i></p>}
                </Card>
              ))
            ) : (
              <p>
                <strong>Status:</strong> 
                <Badge variant={getPaymentStatusVariant(order.payment_status)} className="ml-2 capitalize">
                  {order.payment_status || 'Pending'}
                </Badge>
              </p>
            )}
             {!order.payments && (
                <p>
                    <strong>Overall Payment Status:</strong> 
                    <Badge variant={getPaymentStatusVariant(order.payment_status)} className="ml-2 capitalize">
                    {order.payment_status || 'Pending'}
                    </Badge>
                </p>
             )}
          </div>
        </CardContent>
        <CardFooter className="flex justify-end">
          {/* Optional: Add actions like "Update Status" here if implemented */}
          {/* <Button variant="outline">Update Status</Button> */}
        </CardFooter>
      </Card>
    </div>
  );
}