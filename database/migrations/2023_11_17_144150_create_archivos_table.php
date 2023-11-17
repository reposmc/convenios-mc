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
          $table->unsignedBigInteger('exonerations_id');
          $table->softDeletes();
          $table->timestamps();
 
          $table->foreign("exonerations_id")->references("id")->on("exonerations");
       });
    }
 
    public function down()
    {
       Schema::dropIfExists('archivos');
    }
};
