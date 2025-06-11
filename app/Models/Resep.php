<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    protected $guarded = ["id"];
    public function orderan()
{
    return $this->belongsTo(Orderan::class, 'orderan_id');
}

}
