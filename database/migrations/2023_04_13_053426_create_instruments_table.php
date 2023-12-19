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
        Schema::create('instruments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_instrument_id')->constrained('type_instruments');
            $table->string('instrument_name', 700);
            $table->date('date');
            $table->date('dateStart')->nullable();
            $table->date('dateFinish')->nullable();
            $table->enum('state', ['Vigente', 'Prórroga', 'Finalizado']);
            $table->foreignId('sector_id')->constrained('sectors');
            $table->foreignId('entity_id')->constrained('entities');
            $table->foreignId('direction_id')->constrained('national_directions');
            $table->text('description')->nullable();
            $table->date('dateStartExtension')->nullable();
            $table->date('dateFinishExtension')->nullable();
            $table->text('descriptionExtension')->nullable();
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
        Schema::dropIfExists('instruments');
    }
};
