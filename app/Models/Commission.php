<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // This is the client/customer
        'artist_id', // This is the artist performing the commission
        'title', // Added title
        'status',
        'total_price',
        'description',
        'image',
        'service_id', // Added for linking to a specific service
    ];
    
    
    public function user() // Client/Customer
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function artist() // Artist performing the work
    {
        return $this->belongsTo(Artist::class, 'artist_id');
    }

    public function loves()
    {
        return $this->belongsToMany(User::class, 'commission_loves', 'commission_id', 'user_id')
                    ->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
/**
     * Get the public-facing status of the commission.
     *
     * @return string
     */
    public function getPublicStatusAttribute()
    {
        $internalStatus = $this->attributes['status'] ?? null;

        switch ($internalStatus) {
            case 'pending':
            case 'accepted': // Assuming 'accepted' by artist means it's listed and available
                return 'Available';

            case 'ordered_pending_artist_action':
            case 'in_progress':
            case 'submitted_for_client_review':
            case 'needs_revision':
                return 'Ordered'; // Publicly, it's taken/in process

            case 'completed':
                return 'Completed';
            
            // Add more cases here if other internal statuses exist
            // e.g., 'cancelled', 'on_hold', 'closed_by_artist'
            // case 'cancelled_by_artist':
            //     return 'Unavailable';

            default:
                // If status is null (e.g., new commission not yet saved with a status) or unmapped
                if (is_null($internalStatus)) {
                     // Default for a new commission before any status is set by artist
                    return 'Available'; // Or 'Pending Review' if there's an admin approval step first
                }
                // For any other unmapped status, it's safer to not assume availability.
                return 'Status Undefined'; // Or 'Unavailable'
        }
    }
}
