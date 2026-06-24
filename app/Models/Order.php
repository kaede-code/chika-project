<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'orders';


    protected $fillable = [
        'user_id',
        'status',
        'total_amount',

        // Kolom pengiriman yang dipakai project ini.
        'shipping_address',
        'alamat',

        'recipient_no_hp',
        'recipient_name',

        'reference',
        'payment_method',
        'payment_reference',
        'payment_qris_image',

        'rejection_reason',
    ];



    public function getFormattedAlamatAttribute(): string
    {
        $alamat = $this->alamat ?? '';
        if ($alamat === '' || $alamat === '-') return '-';
        if (!str_contains($alamat, '||')) return $alamat;
        $parts = explode('||', $alamat);
        $result = [];
        if (trim($parts[0] ?? '')) $result[] = trim($parts[0]);
        if (trim($parts[1] ?? '')) $result[] = 'Kec. ' . trim($parts[1]);
        if (trim($parts[2] ?? '')) $result[] = 'Kab./Kota. ' . trim($parts[2]);
        if (trim($parts[3] ?? '')) $result[] = 'Prov. ' . trim($parts[3]);
        return implode(', ', $result) ?: '-';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function paymentProofs(): HasMany
    {
        return $this->hasMany(\App\Models\PaymentProof::class, 'order_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(\App\Models\OrderItem::class, 'order_id');
    }
}





