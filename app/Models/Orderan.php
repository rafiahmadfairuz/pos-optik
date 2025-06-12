<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orderan extends Model
{
    protected $guarded = ["id"];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(OrderItems::class, 'order_id');
    }

    public function asuransi()
    {
        return $this->belongsTo(Asuransi::class, 'asuransi_id');
    }


public function staff()
{
    return $this->belongsTo(Staff::class);
}



    public function resep()
    {
        return $this->hasOne(Resep::class, 'orderan_id');
    }
}
