<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Add this line

class User extends Authenticatable
{
    use HasApiTokens, Notifiable; // Add HasApiTokens here

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username', 'bio', 'profile_picture', 'role', 'email_verified_at', // Pastikan 'username' ditambahkan
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function messagesSent()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function messagesReceived()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function lovedCommissions()
    {
        return $this->belongsToMany(Commission::class, 'commission_loves', 'user_id', 'commission_id')->withTimestamps();
    }

    public function artist()
    {
        return $this->hasOne(Artist::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            // Check if the user has an artist profile and delete it
            if ($user->artist) {
                $user->artist->delete();
            }
        });
    }
}
