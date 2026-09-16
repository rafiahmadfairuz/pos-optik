<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transfer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke cabang asal pengirim (bisa Gudang Pusat ID 0 atau cabang retail)
    public function fromCabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'from_cabang_id');
    }

    // Relasi ke cabang tujuan penerima
    public function toCabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'to_cabang_id');
    }

    // Relasi ke daftar item/produk yang ditransfer
    public function items(): HasMany
    {
        return $this->hasMany(TransferItem::class);
    }
}
