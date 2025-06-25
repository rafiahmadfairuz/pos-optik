<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    /** @use HasFactory<\Database\Factories\TransferFactory> */
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
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }
    public function items()
    {
        return $this->hasMany(TransferItem::class);
    }
}
