<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOfficesTable extends Migration
{
    public function up(): void
    {
        Schema::create('sales_offices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('region_id');
            $table->string('sales_office_name');
            $table->text('sales_office_address');
            $table->timestamps();

            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_offices');
    }
}
