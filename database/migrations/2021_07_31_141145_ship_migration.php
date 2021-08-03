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
            $table->string('latitude');
            $table->string('longitude');
            $table->string('radius');
            $table->integer('price');

            $table->timestamps();
            $table->foreignId('currency_id');
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
