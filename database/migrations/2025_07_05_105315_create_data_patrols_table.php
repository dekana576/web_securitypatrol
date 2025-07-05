<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataPatrolsTable extends Migration
{
    public function up()
    {
        Schema::create('data_patrols', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->foreignId('sales_office_id')->constrained()->onDelete('cascade');
            $table->foreignId('checkpoint_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Security
            $table->text('description');
            $table->json('kriteria_result'); // simpan hasil dari semua kriteria (id dan jawaban)
            $table->string('status')->default('submitted'); // submitted atau approved
            $table->string('image')->nullable();
            $table->string('lokasi')->nullable(); // format: "latitude,longitude"
            $table->text('feedback_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_patrols');
    }
}

