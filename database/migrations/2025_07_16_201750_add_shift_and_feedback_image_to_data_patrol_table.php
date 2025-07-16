<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('data_patrols', function (Blueprint $table) {
            $table->string('shift')->after('tanggal'); // shift ditaruh sebelum region_id (berarti setelah tanggal)
            $table->text('feedback_image')->nullable()->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('data_patrols', function (Blueprint $table) {
            $table->dropColumn(['shift', 'feedback_image']);
        });
    }
};

