<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Negotiation extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'nama_klien',
        'kontak',
        'email',
        'harga_pengajuan',
        'catatan',
        'status',
    ];

    /**
     * Get the offer that owns the negotiation.
     */
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
