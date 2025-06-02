<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'commission_id',
        'status',
        'total_price',
        'delivery_link', // Ditambahkan
    ];

    public function commission()
    {
        return $this->belongsTo(Commission::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get all of the revisions for the Order.
     */
    public function revisions()
    {
        return $this->hasMany(OrderRevision::class, 'order_id')->orderBy('requested_at', 'desc');
    }
}
