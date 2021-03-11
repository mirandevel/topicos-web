<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->char('estado');
            $table->unsignedBigInteger('trabajador_id');
            $table->unsignedBigInteger('persona_id');


            $table->foreign('trabajador_id')
                ->references('id')
                ->on('trabajadores')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();


            $table->foreign('persona_id')
                ->references('id')
                ->on('personas')
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
        Schema::dropIfExists('solicitudes');
    }
}
