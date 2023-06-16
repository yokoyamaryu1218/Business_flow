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
        Schema::create('routines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('previous_procedure_id');
            $table->string('next_procedure_ids')->nullable();
            $table->unsignedBigInteger('next_procedure_id')->nullable();
            $table->timestamps();
        
            $table->foreign('task_id')->references('id')->on('tasks');
            $table->foreign('previous_procedure_id')->references('id')->on('procedures');
            $table->foreign('next_procedure_id')->references('id')->on('procedures');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routines');
    }
};
