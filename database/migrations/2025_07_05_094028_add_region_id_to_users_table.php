<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom region_id yang dapat bernilai null
            $table->foreignId('region_id')->nullable()->after('sales_office_id')->constrained('regions')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus foreign key dan kolom region_id jika rollback
            $table->dropForeign(['region_id']);
            $table->dropColumn('region_id');
        });
    }
};
