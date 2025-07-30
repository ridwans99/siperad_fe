<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalRuangan extends Model
{
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable = [
        'ruang_id',
        'matkul_id',
        'dosen_id',
        'hari',
        'jam_mulai_id',
        'jam_selesai_id',
        'prodi_id',
        'angkatan_id',
        'status_ruang'
    ];

    public function Prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function Angkatan()
    {
        return $this->belongsTo(Angkatan::class);
    }

    public function Ruang()
    {
        return $this->belongsTo(Ruang::class);
    }

    public function Matkul()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function Dosen()
    {
        return $this->belongsTo(NamaDosen::class);
    }

    public function Jamx()
    {
        return $this->belongsTo(Jam::class, 'jam_mulai_id');
    }

    public function Jamy()
    {
        return $this->belongsTo(Jam::class, 'jam_selesai_id');
    }

    // public function peminjamanruang(): HasMany {
    //     return $this->hasMany(PeminjamanRuang::class);
    // }
}
