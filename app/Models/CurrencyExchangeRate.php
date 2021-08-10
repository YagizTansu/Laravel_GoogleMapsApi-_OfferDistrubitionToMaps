<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class CurrencyExchangeRate extends Model
{
    use HasFactory;
    protected $fillable = ['currency_id', 'date', 'buyying', 'selling'];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
