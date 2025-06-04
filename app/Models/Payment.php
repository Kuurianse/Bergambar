<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory; // Added HasFactory

    protected $fillable = [
        'commission_id',
        'order_id', // Added order_id
        'payment_method',
        'amount',
        'payment_status',
        'payment_date',
        'notes', // Added notes as per PaymentResource
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'amount' => 'decimal:2', // Assuming amount should be treated as decimal
    ];

    public function commission()
    {
        return $this->belongsTo(Commission::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
