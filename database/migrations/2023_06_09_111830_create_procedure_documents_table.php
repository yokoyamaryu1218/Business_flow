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
        Schema::create('procedure_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('procedure_id');
            $table->unsignedBigInteger('document_id');
            $table->foreign('procedure_id')->references('id')->on('procedures');
            $table->foreign('document_id')->references('id')->on('documents');
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
        Schema::dropIfExists('procedure_documents');
    }
};
