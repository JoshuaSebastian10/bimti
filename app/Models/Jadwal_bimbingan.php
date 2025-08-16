<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $jadwal_dosen_id
 * @property string $hari
 * @property string $jam_mulai
 * @property string $jam_selesai
 * @property int $kuota
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Dosen $Dosen
 * @property-read mixed $jam_mulai_format
 * @property-read mixed $jam_selesai_format
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal_bimbingan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal_bimbingan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal_bimbingan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal_bimbingan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal_bimbingan whereHari($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal_bimbingan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal_bimbingan whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal_bimbingan whereJadwalDosenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal_bimbingan whereJamMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal_bimbingan whereJamSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal_bimbingan whereKuota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal_bimbingan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Jadwal_bimbingan extends Model
{
    protected $fillable = ['jadwal_dosen_id', 'hari', 'jam_mulai', 'jam_selesai', 'kuota','is_active'];

    public function Dosen(){
        return $this->belongsTo(Dosen::class, 'jadwal_dosen_id');
    }
    
    public function getJamMulaiFormatAttribute()
    {
        return Carbon::createFromFormat('H:i:s', $this->jam_mulai)->format('H:i');
    }
    public function getJamSelesaiFormatAttribute()
    {
        return Carbon::createFromFormat('H:i:s', $this->jam_selesai)->format('H:i');
    }

    

}
