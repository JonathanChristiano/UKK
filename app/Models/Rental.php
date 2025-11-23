<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'amount',
        'start_date',
        'end_date',
        'status',
    ];

    // Relasi: Rental milik User siapa?
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Rental ini pinjam Produk apa?
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
