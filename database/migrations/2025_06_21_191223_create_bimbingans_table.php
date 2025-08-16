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
        Schema::create('bimbingans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('dosen_id');
            $table->string('topik')->nullable();
            $table->enum('status',['menunggu','disetujui','ditolak', 'selesai','dibatalkan', 'kedaluwarsa']);
            $table->enum('jenis_bimbingan',['akademik','proposal','skripsi']);
            $table->string('pesan')->nullable();
            $table->string('judul')->nullable();
            $table->string('lampiran_path')->nullable();
            $table->timestamp('tanggal_pengajuan')->nullable(); // Diubah menjadi timestamp
            $table->date('tanggal_bimbingan');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamp('tanggal_disetujui')->nullable();
            $table->timestamp('tanggal_ditolak')->nullable();
            $table->timestamp('tanggal_dibatalkan')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->date('usulan_tanggal_bimbingan')->nullable();
            $table->time('usulan_jam_mulai')->nullable();
            $table->time('usulan_jam_selesai')->nullable();
            $table->enum('status_perubahan', ['menunggu_mahasiswa', 'disetujui_mahasiswa', 'ditolak_mahasiswa'])->nullable();
            $table->timestamp('waktu_perubahan_diajukan')->nullable();
            $table->timestamps();
            $table->foreign('dosen_id')->references('id')->on('dosens')->onDelete('cascade');
            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswas')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bimbingans');
    }
};
