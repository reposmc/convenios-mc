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
       Schema::create('archivos', function (Blueprint $table) {
 
          $table->id();
          $table->text('documento');
          $table->string('nombre', 45);
          $table->unsignedBigInteger('instrument_id');
          $table->softDeletes();
          $table->timestamps();
 
          $table->foreign("instrument_id")->references("id")->on("instruments");
       });
    }
 
    public function down()
    {
       Schema::dropIfExists('archivos');
    }
};
