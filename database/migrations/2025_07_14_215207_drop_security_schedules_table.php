<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DropSecuritySchedulesTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('security_schedules');
    }

    public function down()
    {
        // Optional: Jika ingin bisa rollback dan mengembalikan struktur lama, bisa tambahkan struktur di sini
        Schema::create('security_schedules', function ($table) {
            $table->id();
            $table->foreignId('region_id')->constrained('regions')->onDelete('cascade');
            $table->foreignId('sales_office_id')->constrained('sales_offices')->onDelete('cascade');
            $table->string('shift');
            $table->time('jam_mulai');
            $table->time('jam_berakhir');
            $table->string('senin')->nullable();
            $table->string('selasa')->nullable();
            $table->string('rabu')->nullable();
            $table->string('kamis')->nullable();
            $table->string('jumat')->nullable();
            $table->string('sabtu')->nullable();
            $table->string('minggu')->nullable();
            $table->timestamps();
        });
    }
}
