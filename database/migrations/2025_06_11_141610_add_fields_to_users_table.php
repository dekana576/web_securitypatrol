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
        Schema::table('users', function (Blueprint $table) {

                $table->string('username')->unique()->after('name');
                $table->string('nik')->unique()->after('username');
                $table->string('phone_number')->after('nik');
                $table->enum('gender', ['male', 'female'])->nullable()->after('phone_number');
                $table->enum('role',['admin','security'])->default('security')->after('gender');;
                
                
    
                $table->foreignId('sales_office_id')->constrained()->onDelete('cascade');

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['sales_office_id']);
            $table->dropColumn(['username', 'nik', 'phone_number', 'gender', 'role', 'sales_office_id']);
        });
    }
};
