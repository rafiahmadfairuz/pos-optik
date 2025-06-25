<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferItem extends Model
{
    /** @use HasFactory<\Database\Factories\TransferItemFactory> */
    use HasFactory;
    protected $guarded = ["id"];

    public function itemable()
    {
        return $this->morphTo();
    }
    public function transfer()
    {
        return $this->belongsTo(Transfer::class);
    }
}
