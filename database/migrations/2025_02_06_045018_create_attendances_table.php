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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->date("date")->nullable();
            $table->unsignedBigInteger("user_id")->nullable();

            $table->string("check_in_full_address")->nullable();
            $table->string("check_in_latitude")->nullable();
            $table->string("check_in_longitude")->nullable();
            $table->time("check_in_time")->nullable();

            $table->string("check_out_full_address")->nullable();
            $table->string("check_out_latitude")->nullable();
            $table->string("check_out_longitude")->nullable();
            $table->time("check_out_time")->nullable();

            $table->string("status")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
