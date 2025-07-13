<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('data_patrols', function (Blueprint $table) {
            $table->text('image')->change(); // Atau longText() jika Anda ingin lebih aman
        });
    }

    public function down()
    {
        Schema::table('data_patrols', function (Blueprint $table) {
            $table->string('image', 255)->change(); // Balik ke VARCHAR jika rollback
        });
    }

};
