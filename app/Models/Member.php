<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $table = "member";

    protected $fillable = [
        'nama',
        'alamat',
        'email',
        'nomor_handphone',
    ];

    public function memberCard()
    {
        return $this->hasOne(MemberCard::class, 'member_id');
    }
}
