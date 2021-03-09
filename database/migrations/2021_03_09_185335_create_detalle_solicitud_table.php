<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleSolicitudTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_solicitud', function (Blueprint $table) {
            $table->id();
            $table->text('descripcion');
            $table->string('ubicacion');
            $table->float('costo');
            $table->date('fecha');

            $table->unsignedBigInteger('solicitud_id');
            $table->unsignedBigInteger('servicio_id');


            $table->foreign('solicitud_id')
                ->references('id')
                ->on('solicitudes')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();


            $table->foreign('servicio_id')
                ->references('id')
                ->on('servicios')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();


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
        Schema::dropIfExists('detalle_solicitud');
    }
}
