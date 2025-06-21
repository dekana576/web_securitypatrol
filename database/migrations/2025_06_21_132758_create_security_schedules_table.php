<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('security_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->foreignId('sales_office_id')->constrained()->onDelete('cascade');
            $table->string('shift');
            $table->time('jam_mulai');
            $table->time('jam_berakhir');
            $table->foreignId('senin')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('selasa')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('rabu')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('kamis')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('jumat')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('sabtu')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('minggu')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_schedules');
    }
};
