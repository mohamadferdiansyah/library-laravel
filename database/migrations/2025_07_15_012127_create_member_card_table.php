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
        Schema::create('member_card', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('member_id');
            $table->bigInteger('nomor_kartu')->unique();
            $table->date('tanggal_aktif');
            $table->date('tanggal_kadaluarsa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_card');
    }
};
