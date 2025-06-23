<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Resep extends Model
{
    use HasFactory;
    protected $guarded = ["id"];
    public function orderan()
    {
        return $this->belongsTo(Orderan::class, 'orderan_id');
    }
}
