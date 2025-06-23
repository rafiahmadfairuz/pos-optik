<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    /** @use HasFactory<\Database\Factories\PembelianFactory> */
    use HasFactory;
    protected $guarded = ["id"];
     public function itemable()
    {
        return $this->morphTo();
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
