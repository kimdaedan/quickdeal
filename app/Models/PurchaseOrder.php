<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'user_id',
        'name',
        'detail_project',
        'alamat_detail',
        'phone',
        'email',
        'status',
        'custom_quantities',
        'custom_total',
    ];

    protected $casts = [
        'custom_quantities' => 'array',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
