<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instruments_dependecies_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instrument_id')->constrained('instruments');
            $table->foreignId('dependency_id')->constrained('dependences');
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
        Schema::dropIfExists('instruments_dependecies');
    }
};
