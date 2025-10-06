<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukCabang extends Model
{
    /** @use HasFactory<\Database\Factories\ProdukCabangFactory> */
    use HasFactory;
    protected $guarded = ["id"];
    public function itemable()
    {
        return $this->morphTo();
    }
}
