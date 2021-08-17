<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ShipMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ships', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('name');
            $table->Decimal('latitude',8,4);
            $table->Decimal('longitude',8,4);
            $table->unsignedDecimal('radius',11,3);
            $table->unsignedDecimal('price',8,4);
            $table->foreignId('currency_id');
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
        Schema::dropIfExists('ships');
    }
}
