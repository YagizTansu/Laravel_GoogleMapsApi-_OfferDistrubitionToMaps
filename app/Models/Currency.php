<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $table='currency';

    public function currencyExchangeRate()
    {
        return $this->hasOne(CurrencyExchangeRate::class)->latest();
    }

    public function currencyExchangeRates()
    {
        return $this->hasMany(CurrencyExchangeRate::class);
    }
}
