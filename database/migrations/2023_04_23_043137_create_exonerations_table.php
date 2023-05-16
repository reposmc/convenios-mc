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
            $table->string('service_place_name')->nullable();
            //$table->unsignedBigInteger('dependence_id')->constrained('dependences')->nullable();
            $table->integer('number_hour')->nullable();
            $table->integer('number_people')->nullable();
            //$table->unsignedBigInteger('tariff_type_charge')->constrained('tariffs')->nullable();
            $table->string('non_tariff_concept')->nullable();
            $table->decimal('non_tariff_amount', 8, 2)->nullable();
            $table->date('date_event')->nullable();
            $table->tinyInteger('is_tariffed')->nullable()->comment('0 = No Tarifado, 1 = Tarifado');
            //$table->unsignedBigInteger('tariff_id')->constrained('tariffs')->nullable();
            $table->decimal('tariff_amount', 8, 2)->nullable();
            $table->decimal('total_amount', 8, 2)->nullable();
            //$table->decimal('total_value', 8, 2)->nullable();
            $table->string('concept')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('estimated_price', 8, 2)->nullable();
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
