<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ship extends Model
{
    use HasFactory;
    protected $fillable=['currency_id'];
    protected $table='ships';

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
