<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ship extends Model
{
    protected $fillable=['currency_id'];
    protected $table='ships';

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
