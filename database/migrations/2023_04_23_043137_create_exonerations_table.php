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
            $table->foreignId('instrument_id')->constrained('instruments');
            $table->date('date_event');
            $table->string('service_place_name')->nullable();
            $table->integer('number_hour')->nullable();
            $table->integer('number_people')->nullable();
            $table->string('non_tariff_concept')->nullable();
            $table->string('tariff_type_charge')->nullable();
            $table->double('non_tariff_amount', 8, 2)->nullable()->default(null);
            $table->tinyInteger('is_tariffed')->nullable()->comment('0 = Tarifado, 1 = No Tarifado')->default(null);
            $table->double('tariff_amount', 8, 2)->nullable()->default(null);
            $table->double('total_amount', 8, 2)->nullable()->default(null);
            $table->string('concept', 200)->nullable();
            $table->integer('quantity')->nullable();
            $table->double('estimated_price', 8, 2)->nullable()->default(null);
            $table->string('exonerated_description')->nullable();
            $table->foreignId('service_places_id')->constrained('service_places')->nullable();
            // $table->softDeletes();
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
