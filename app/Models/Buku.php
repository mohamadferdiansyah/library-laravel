<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'judul',
        'kode_buku',
        'tahun_terbit',
        'penulis',
        'penerbit_id',
    ];

    public function penerbit()
    {
        return $this->belongsTo(Penerbit::class, 'penerbit_id');
    }
    
}
