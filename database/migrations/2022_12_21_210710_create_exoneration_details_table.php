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
        Schema::create('exoneration_details', function (Blueprint $table) {
            $table->id();
            $table->string('hour');
            $table->integer('people');
            $table->date('date');
            $table->decimal('exonerated_amount', 8, 2);
            $table->foreignId('exoneration_id')->constrained('exonerations');
            $table->foreignId('service_place_id')->constrained('service_places');
            $table->unsignedBigInteger('tariff_id')->constrained('tariffs')->nullable();
            $table->decimal('not_charged', 8, 2)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('exoneration_details');
    }
};
