<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'notes',
        'requested_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'requested_at' => 'datetime',
    ];

    /**
     * Get the order that this revision belongs to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the user (client) who requested this revision.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
