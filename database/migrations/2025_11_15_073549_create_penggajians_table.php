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
        Schema::create('penggajian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tutor')->references('id')->on('tutor');
            $table->integer('periode_bulan');
            $table->integer('periode_tahun');
            $table->integer('total_pertemuan');
            $table->integer('total_honor');
            $table->integer('gaji_dibayar');
            $table->enum('status_pembayaran', ['Pending', 'Lunas']);
            $table->timestamp('tgl_dibayar')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggajian');
    }
};
