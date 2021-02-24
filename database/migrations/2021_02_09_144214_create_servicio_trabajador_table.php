<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicioTrabajadorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicio_trabajador', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('dias');
            $table->time('hora_inicio');
            $table->time('hora_fin');

            $table->unsignedBigInteger('trabajador_id');
            $table->unsignedBigInteger('servicio_id');


            $table->foreign('trabajador_id')
                ->references('id')
                ->on('trabajadores')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('servicio_id')
                ->references('id')
                ->on('servicios')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servicio_trabajador');
    }
}
