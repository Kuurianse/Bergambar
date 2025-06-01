<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['commission_id', 'user_id', 'review'];

    // Relasi ke commission yang direview
    public function commission()
    {
        return $this->belongsTo(Commission::class);
    }

    // Relasi ke user yang memberikan review
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
