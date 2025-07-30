<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeminjamanRuang extends Model
{
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable = [
        'nama_peminjam',
        'tgl_peminjaman',
        'matkul_id',
        'jam_mulai_id',
        'jam_selesai_id',
        'dosen_id',
        'ruang_id',
        'prodi_id',
        'angkatan_id',
        'status_peminjaman'
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
}
