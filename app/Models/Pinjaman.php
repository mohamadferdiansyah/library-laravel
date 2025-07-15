<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    use HasFactory;

    protected $table = 'pinjaman';

    protected $fillable = [
        'member_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'tanggal_kadaluarsa',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}
