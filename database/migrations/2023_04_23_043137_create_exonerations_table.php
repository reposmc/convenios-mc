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
        Schema::create('exonerations', function (Blueprint $table) {
            $table->id();
            $table->string('exonerated_description')->nullable();
            $table->foreignId('instrument_id')->constrained('instruments')->nullable();
            $table->integer('hour')->nullable();
            $table->integer('people')->nullable();
            $table->date('date_event')->nullable();
            $table->tinyInteger('is_tariffed')->nullable()->comment('0 = No Tarifado, 1 = Tarifado');
            $table->decimal('amount_hour', 8, 2)->nullable();
            $table->decimal('amount_people', 8, 2)->nullable();
            $table->string('service_place_name')->nullable();
            $table->unsignedBigInteger('tariff_id')->constrained('tariffs')->nullable();
            $table->decimal('tariff_people', 8, 2)->nullable();
            $table->decimal('tariff_hour', 8, 2)->nullable();
            $table->decimal('not_charged_hour', 8, 2)->nullable();
            $table->decimal('not_charged_people', 8, 2)->nullable();
            $table->string('concept')->nullable();
            $table->integer('worth')->nullable();
            $table->decimal('concept_amount', 8, 2)->nullable();
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
        Schema::dropIfExists('exonerations');
    }
};
