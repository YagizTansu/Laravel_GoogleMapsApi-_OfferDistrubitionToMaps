<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id');
            $table->foreignId('company_id');
            $table->Decimal('latitude',8,4);
            $table->Decimal('longitude',8,4);
            $table->unsignedDecimal('radius',11,3);
            $table->foreignId('currency_id');
            $table->unsignedDecimal('price',8,4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers');
    }
}
