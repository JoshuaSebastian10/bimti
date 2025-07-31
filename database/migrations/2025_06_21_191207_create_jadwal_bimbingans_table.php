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
        Schema::create('jadwal_bimbingans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_dosen_id')->constrained('dosens')->onDelete('cascade');
            $table->enum('hari',['Senin','Selasa','Rabu','Kamis', 'Jumat']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->unsignedTinyInteger('kuota')->default(1);
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_bimbingans');
    }
};
