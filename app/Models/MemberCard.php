<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberCard extends Model
{
    use HasFactory;

    protected $table = 'member_card';

    protected $fillable = [
        'member_id',
        'nomor_kartu',
        'tanggal_aktif',
        'tanggal_kadaluarsa',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
}
