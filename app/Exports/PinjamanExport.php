<?php

namespace App\Exports;

use App\Models\Pinjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PinjamanExport implements FromCollection, WithHeadings, WithMapping
{
    private int $rowNumber = 1;

    public function collection()
    {
        return Pinjaman::with(['member', 'buku'])
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Member',
            'Judul Buku',
            'Tanggal Pinjam',
            'Status',
            'Tanggal Kadaluarsa',
        ];
    }

    public function map($pinjaman): array
    {
        $namaMember = $pinjaman->member ? $pinjaman->member->nama : 'Member tidak ditemukan';
        $judulBuku = $pinjaman->buku ? $pinjaman->buku->judul : 'Buku tidak ditemukan';
        $tanggalPinjam = $pinjaman->tanggal_pinjam ? $pinjaman->tanggal_pinjam : '-';
        $tanggalKadaluarsa = $pinjaman->tanggal_kadaluarsa ? $pinjaman->tanggal_kadaluarsa : '-';

        return [
            $this->rowNumber++,
            $namaMember,
            $judulBuku,
            $tanggalPinjam,
            ucfirst($pinjaman->status),
            $tanggalKadaluarsa,
        ];
    }
}
