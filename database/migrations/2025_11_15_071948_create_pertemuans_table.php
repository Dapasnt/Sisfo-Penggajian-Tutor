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
        Schema::create('pertemuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tutor')->references('id')->on('tutor');
            $table->string('id_kelas', 5);
            $table->foreign('id_kelas')
                ->references('id_kelas')
                ->on('kelas');
            // $table->foreign('id_kelas')
            //     ->references('id_kelas')
            //     ->on('kelas');
            // $table->foreignId('id_kelas')->references('id_kelas')->on('kelas');
            $table->date('tgl_pertemuan');
            $table->decimal('tarif', 10, 2);
            $table->foreignId('id_penggajian')->references('id')->on('penggajian');
            $table->string('bukti_foto')->nullable();
            $table->string('keterangan')->nullable();
            $table->enum('status', ['Pending', 'Hadir', 'Return']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertemuan');
    }
};
