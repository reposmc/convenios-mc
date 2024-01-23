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
        Schema::create('prorrogas', function (Blueprint $table) {
            $table->id();
            $table->text('documento');
            $table->string('nombre', 200);
            $table->date('dateStartExtension')->nullable();
            $table->date('dateFinishExtension')->nullable();
            $table->unsignedBigInteger('instrument_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign("instrument_id")->references("id")->on("instruments");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prorrogas');
    }
};
