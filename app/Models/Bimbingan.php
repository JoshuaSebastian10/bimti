<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $mahasiswa_id
 * @property int $dosen_id
 * @property string|null $topik
 * @property string $status
 * @property string $jenis_bimbingan
 * @property string|null $pesan
 * @property string|null $judul
 * @property string|null $lampiran_path
 * @property string $tanggal_pengajuan
 * @property string $tanggal_bimbingan
 * @property string $jam_mulai
 * @property string $jam_selesai
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Dosen $dosen
 * @property-read mixed $jam_mulai_format
 * @property-read mixed $jam_selesai_format
 * @property-read mixed $tanggal_bimbingan_format
 * @property-read mixed $tanggal_pengajuan_format
 * @property-read \App\Models\Mahasiswa $mahasiswa
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bimbingan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bimbingan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bimbingan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bimbingan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bimbingan whereDosenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bimbingan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bimbingan whereJamMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bimbingan whereJamSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bimbingan whereJenisBimbingan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bimbingan whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bimbingan whereLampiranPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bimbingan whereMahasiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bimbingan wherePesan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bimbingan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bimbingan whereTanggalBimbingan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bimbingan whereTanggalPengajuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bimbingan whereTopik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bimbingan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Bimbingan extends Model
{
//     protected $fillable = [
//   'mahasiswa_id','dosen_id','topik','status','jenis_bimbingan','pesan',
//   'tanggal_pengajuan','tanggal_bimbingan','jam_mulai','jam_selesai',
//   'tanggal_disetujui','tanggal_ditolak','tanggal_dibatalkan','tanggal_selesai',
//   // kolom usulan:
//   'usulan_tanggal_bimbingan','usulan_jam_mulai','usulan_jam_selesai',
//   'status_perubahan','waktu_perubahan_diajukan',
// ];
    protected $fillable = ['mahasiswa_id','dosen_id', 'topik','judul', 'status' ,'jenis_bimbingan','pesan','lampiran_path','tanggal_pengajuan' ,'tanggal_bimbingan','jam_mulai','jam_selesai','tanggal_disetujui','tanggal_ditolak','tanggal_dibatalkan','tanggal_selesai','usulan_tanggal_bimbingan','usulan_jam_mulai','usulan_jam_selesai','status_perubahan','waktu_perubahan_diajukan',];

    public function mahasiswa(){
        return $this->belongsTo(Mahasiswa::class);
    }

    public function dosen(){
        return $this->belongsTo(Dosen::class);
    }

    public function getTanggalPengajuanFormatAttribute()
    {
        return $this->tanggal_pengajuan ? \Carbon\Carbon::parse($this->tanggal_pengajuan)->locale('id')->translatedFormat('l, d F Y') : '-';
    }



    public function getTanggalBimbinganFormatAttribute()
    {
        return $this->tanggal_bimbingan ? \Carbon\Carbon::parse($this->tanggal_bimbingan)->locale('id')->translatedFormat('l, d F Y') : '-';
    }
    
    public function getJamMulaiFormatAttribute()
    {
        return $this->jam_mulai ? \Carbon\Carbon::parse($this->jam_mulai)->format('H:i') : '-';
    }
    public function getJamSelesaiFormatAttribute()
    {
        return $this->jam_selesai ? \Carbon\Carbon::parse($this->jam_selesai)->format('H:i') : '-';
    }

       protected $casts = [
        'tanggal_pengajuan'      => 'datetime',
        'tanggal_disetujui'      => 'datetime',
        'tanggal_ditolak'        => 'datetime',
        'tanggal_dibatalkan'     => 'datetime',
        'tanggal_selesai'        => 'datetime',
        'reschedule_expires_at'  => 'datetime',
    ];

}
