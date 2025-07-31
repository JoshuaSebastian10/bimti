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
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim')->unique();
            $table->enum('status_bimbingan', ['akademik','proposal', 'skripsi']); 
            $table->enum('status_akun', ['aktif', 'nonAktif']);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dosen_pa_id')->nullable()->constrained('dosens')->onDelete('set null');
            $table->foreignId('pembimbing_skripsi_1_id')->nullable()->constrained('dosens')->onDelete('set null');
            $table->foreignId('pembimbing_skripsi_2_id')->nullable()->constrained('dosens')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};