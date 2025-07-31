<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $nim
 * @property string $status_bimbingan
 * @property string $status_akun
 * @property string|null $profil_path
 * @property int $user_id
 * @property int|null $dosen_pa_id
 * @property int|null $pembimbing_skripsi_1_id
 * @property int|null $pembimbing_skripsi_2_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bimbingan> $bimbingan
 * @property-read int|null $bimbingan_count
 * @property-read \App\Models\Dosen|null $dosenPa
 * @property-read string $angkatan
 * @property-read \App\Models\Dosen|null $pembimbingSkripsi1
 * @property-read \App\Models\Dosen|null $pembimbingSkripsi2
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereDosenPaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNim($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa wherePembimbingSkripsi1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa wherePembimbingSkripsi2Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereProfilPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereStatusAkun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereStatusBimbingan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereUserId($value)
 * @mixin \Eloquent
 */
class Mahasiswa extends Model
{
 
    protected $fillable = [
    'nim',
    'user_id',
    'dosen_pa_id',
    'status_bimbingan',
    'status_akun',
    'pembimbing_skripsi_1_id',
    'pembimbing_skripsi_2_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


        public function dosenPa():BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_pa_id');
    }

    public function pembimbingSkripsi1():BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'pembimbing_skripsi_1_id');
    }

    public function pembimbingSkripsi2():BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'pembimbing_skripsi_2_id');
    }
    
    public function bimbingan(){
        return $this->hasMany(Bimbingan::class);
    }

       public function getAngkatanAttribute(): string
    {

        if ($this->nim && strlen($this->nim) >= 2) {
            return '20' . substr($this->nim, 0, 2);
        }

        return 'N/A';
    }


    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($mahasiswa) {
            if ($mahasiswa->user) {
                $mahasiswa->user->delete();
            }
        });
    }
}
