<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckpointCriteriasTable extends Migration
{
    public function up()
    {
        Schema::create('checkpoint_criterias', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->foreignId('sales_office_id')->constrained()->onDelete('cascade');
            $table->foreignId('checkpoint_id')->constrained()->onDelete('cascade');

            // Kriteria Fields
            $table->string('nama_kriteria');
            $table->string('positive_answer');
            $table->string('negative_answer');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('checkpoint_criterias');
    }
}
