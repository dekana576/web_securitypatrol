<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatrolSchedulesTable extends Migration
{
    public function up()
    {
        Schema::create('patrol_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained('regions')->onDelete('cascade');
            $table->foreignId('sales_office_id')->constrained('sales_offices')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('shift'); // pagi, siang, malam, non-shift
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->foreignId('security_1_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('security_2_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('patrol_schedules');
    }
}

