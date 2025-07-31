<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nip
 * @property string $status_akun
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bimbingan> $bimbingan
 * @property-read int|null $bimbingan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Jadwal_bimbingan> $jadwalBimbingan
 * @property-read int|null $jadwal_bimbingan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Mahasiswa> $mahasiswaPembimbingSkripsi1
 * @property-read int|null $mahasiswa_pembimbing_skripsi1_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Mahasiswa> $mahasiswaPembimbingSkripsi2
 * @property-read int|null $mahasiswa_pembimbing_skripsi2_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Mahasiswa> $mahasiswaWali
 * @property-read int|null $mahasiswa_wali_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereStatusAkun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereUserId($value)
 * @mixin \Eloquent
 */
class Dosen extends Model
{

    protected $fillable = [
        'nip',
        'status_akun',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mahasiswaWali(){
        return $this->hasMany(Mahasiswa::class, 'dosen_pa_id');
    }

    public function mahasiswaPembimbingSkripsi1(){
        return $this->hasMany(Mahasiswa::class, 'pembimbing_skripsi_1_id');
    }

    public function mahasiswaPembimbingSkripsi2(){
        return $this->hasMany(Mahasiswa::class, 'pembimbing_skripsi_2_id');
    }

    public function jadwalBimbingan() {
        return $this->hasMany(Jadwal_bimbingan::class, 'jadwal_dosen_id');
    }

    public function bimbingan(){
        return $this->hasMany(Bimbingan::class,'dosen_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($dosen) {
           
            if ($dosen->user) {
                $dosen->user->delete();
            }
        });
    }
}
