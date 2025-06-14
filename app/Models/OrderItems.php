<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItems extends Model
{
    use HasFactory;
    protected $guarded = ["id"];
    public function orderan()
    {
        return $this->belongsTo(Orderan::class, 'order_id');
    }

    // Tambahan: jika itemable polymorphic, bisa tambahkan:
    public function itemable()
    {
        return $this->morphTo();
    }
}
