'use client';

import Link from 'next/link';
import { useParams } from 'next/navigation';
import { useEffect, useState } from 'react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle, CardFooter } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { ArrowLeft, AlertCircle, UserCircle, Briefcase, ImageIcon, FileText, DollarSign, CalendarDays } from 'lucide-react';
import { fetchCommission } from '@/lib/apiClient';
import type { Commission } from '@/lib/types';

// Helper to format date strings (can be shared if used elsewhere)
const formatDate = (dateString: string | undefined | null, options?: Intl.DateTimeFormatOptions) => {
  if (!dateString) return 'N/A';
  const defaultOptions: Intl.DateTimeFormatOptions = {
    year: 'numeric', month: 'long', day: 'numeric',
    hour: '2-digit', minute: '2-digit',
    ...options
  };
  return new Date(dateString).toLocaleDateString(undefined, defaultOptions);
};

// Helper to format price (can be shared)
const formatPrice = (price: number | string | undefined | null) => {
  if (price === undefined || price === null) return 'N/A';
  const numericPrice = typeof price === 'string' ? parseFloat(price) : price;
  return `Rp${numericPrice.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}`;
};

// Function to determine badge color based on status (can be shared or customized)
function getStatusBadgeVariant(status: string | undefined | null): "default" | "secondary" | "outline" | "destructive" {
  switch (status?.toLowerCase()) {
    case "pending": return "outline";
    case "accepted": case "in_progress": case "active": return "secondary";
    case "completed": case "published": return "default";
    case "revision_requested": case "on_hold": case "cancelled": case "rejected": case "hidden": return "destructive";
    default: return "outline";
  }
}

interface DetailItemProps {
  icon: React.ElementType;
  label: string;
  value: React.ReactNode;
}

const DetailItem: React.FC<DetailItemProps> = ({ icon: Icon, label, value }) => (
  <div className="flex items-start space-x-3">
    <Icon className="h-5 w-5 text-muted-foreground mt-1" />
    <div>
      <p className="text-sm font-medium text-muted-foreground">{label}</p>
      <div className="text-base">{value || 'N/A'}</div>
    </div>
  </div>
);


export default function CommissionDetailPage() {
  const params = useParams();
  const commissionId = params.id as string; // Assuming id is always a string from params

  const [commission, setCommission] = useState<Commission | null>(null);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    if (commissionId) {
      const loadCommissionDetails = async () => {
        setIsLoading(true);
        setError(null);
        try {
          const numericId = parseInt(commissionId, 10);
          if (isNaN(numericId)) {
            setError("Invalid Commission ID.");
            return;
          }
          const data = await fetchCommission(numericId);
          setCommission(data);
        } catch (err) {
          console.error(`Failed to fetch commission ${commissionId}:`, err);
          setError("Failed to load commission details. Please try again later or check the ID.");
        } finally {
          setIsLoading(false);
        }
      };
      loadCommissionDetails();
    }
  }, [commissionId]);

  if (isLoading) {
    return <div className="flex justify-center items-center h-64">Loading commission details...</div>;
  }

  if (error) {
    return (
      <div className="flex flex-col items-center justify-center h-64 text-red-600">
        <AlertCircle className="w-12 h-12 mb-4" />
        <p className="text-xl">{error}</p>
        <Button asChild variant="outline" className="mt-4">
          <Link href="/admin/commissions">
            <ArrowLeft className="mr-2 h-4 w-4" /> Back to Commission List
          </Link>
        </Button>
      </div>
    );
  }

  if (!commission) {
    return (
      <div className="flex flex-col items-center justify-center h-64">
        <p className="text-xl text-muted-foreground">Commission not found.</p>
        <Button asChild variant="outline" className="mt-4">
          <Link href="/admin/commissions">
            <ArrowLeft className="mr-2 h-4 w-4" /> Back to Commission List
          </Link>
        </Button>
      </div>
    );
  }

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <h1 className="text-3xl font-bold tracking-tight">Commission Details</h1>
        <Button asChild variant="outline">
          <Link href="/admin/commissions">
            <ArrowLeft className="mr-2 h-4 w-4" /> Back to Commission List
          </Link>
        </Button>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>{commission.title}</CardTitle>
          <CardDescription>ID: {commission.id}</CardDescription>
        </CardHeader>
        <CardContent className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <div className="space-y-4">
            <DetailItem icon={FileText} label="Description" value={commission.description || 'No description provided.'} />
            <DetailItem icon={DollarSign} label="Total Price" value={formatPrice(commission.total_price)} />
            <DetailItem icon={CalendarDays} label="Created At" value={formatDate(commission.created_at)} />
            <DetailItem icon={CalendarDays} label="Last Updated" value={formatDate(commission.updated_at)} />
          </div>

          <div className="space-y-4">
            <h3 className="text-lg font-semibold">Status</h3>
            <DetailItem 
              icon={Briefcase} // Placeholder, choose a better icon for status
              label="Internal Status" 
              value={<Badge variant={getStatusBadgeVariant(commission.status)}>{commission.status || 'N/A'}</Badge>} 
            />
            <DetailItem 
              icon={Briefcase} // Placeholder
              label="Public Status" 
              value={<Badge variant={getStatusBadgeVariant(commission.public_status)}>{commission.public_status || 'N/A'}</Badge>} 
            />
             {commission.image && (
              <DetailItem 
                icon={ImageIcon} 
                label="Commission Image" 
                value={
                  <Link href={commission.image} target="_blank" rel="noopener noreferrer" className="text-blue-600 hover:underline">
                    View Image
                  </Link>
                } 
              />
            )}
          </div>
          
          <div className="space-y-4">
            {commission.user && (
              <>
                <h3 className="text-lg font-semibold">Artist Details</h3>
                <DetailItem icon={UserCircle} label="Artist Name" value={commission.user.name} />
                <DetailItem icon={UserCircle} label="Artist Email" value={commission.user.email} />
              </>
            )}
            {commission.service && (
              <>
                <h3 className="text-lg font-semibold mt-4">Service Details</h3>
                <DetailItem icon={Briefcase} label="Service Name" value={commission.service.name} />
                <DetailItem icon={Briefcase} label="Service Category" value={commission.service.category_name || 'N/A'} />
              </>
            )}
          </div>
        </CardContent>
        <CardFooter>
          {/* Optional: Add action buttons here in the future, e.g., Edit, Delete (if implemented) */}
        </CardFooter>
      </Card>
    </div>
  );
}