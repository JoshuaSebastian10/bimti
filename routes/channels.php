<?php
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('mahasiswa.{mahasiswaId}', function ($user, $mahasiswaId) {
    return optional($user->mahasiswa)->id === (int) $mahasiswaId;
});
Broadcast::channel('dosen.{dosenId}', function ($user, $dosenId) {
    return optional($user->dosen)->id === (int) $dosenId;
});
