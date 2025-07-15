<?php

namespace App\Exports;

use App\Models\Pinjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Http\Request;

class PinjamanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $request;

    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Pinjaman::with(['member', 'buku']);
        
        // Apply same filters as in controller
        if ($this->request) {
            // Filter berdasarkan status
            if ($this->request->has('status') && $this->request->status !== '') {
                $query->where('status', $this->request->status);
            }
            
            // Sort berdasarkan parameter
            $sortBy = $this->request->get('sort', 'tanggal_pinjam');
            $sortOrder = $this->request->get('order', 'desc');
            
            switch ($sortBy) {
                case 'member':
                    $query->join('member', 'pinjaman.member_id', '=', 'member.id')
                          ->orderBy('member.nama', $sortOrder)
                          ->select('pinjaman.*');
                    break;
                case 'buku':
                    $query->join('buku', 'pinjaman.buku_id', '=', 'buku.id')
                          ->orderBy('buku.judul', $sortOrder)
                          ->select('pinjaman.*');
                    break;
                case 'tanggal_pinjam':
                    $query->orderBy('tanggal_pinjam', $sortOrder);
                    break;
                case 'tanggal_kadaluarsa':
                    $query->orderBy('tanggal_kadaluarsa', $sortOrder);
                    break;
                case 'status':
                    $query->orderBy('status', $sortOrder);
                    break;
                default:
                    $query->orderBy('tanggal_pinjam', 'desc');
            }
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Member',
            'Email Member',
            'Judul Buku',
            'Kode Buku',
            'Penulis',
            'Tanggal Pinjam',
            'Tanggal Kadaluarsa',
            'Status',
            'Lama Pinjam (Hari)',
            'Status Keterlambatan',
            'Denda (Rp)',
            'Tanggal Export'
        ];
    }

    public function map($pinjaman): array
    {
        static $no = 1;
        
        // Hitung lama pinjam
        $tanggalPinjam = \Carbon\Carbon::parse($pinjaman->tanggal_pinjam);
        $tanggalKadaluarsa = \Carbon\Carbon::parse($pinjaman->tanggal_kadaluarsa);
        $lamaPinjam = $tanggalPinjam->diffInDays($tanggalKadaluarsa);
        
        // Hitung keterlambatan dan denda
        $today = \Carbon\Carbon::now();
        $statusKeterlambatan = 'Tidak Terlambat';
        $denda = 0;
        
        if ($pinjaman->status == 'dipinjam' && $today->gt($tanggalKadaluarsa)) {
            $hariTerlambat = $tanggalKadaluarsa->diffInDays($today);
            $statusKeterlambatan = "Terlambat {$hariTerlambat} hari";
            $denda = $hariTerlambat * 1000; // Rp 1000 per hari
        } elseif ($pinjaman->status == 'dikembalikan') {
            $statusKeterlambatan = 'Sudah Dikembalikan';
        }

        return [
            $no++,
            $pinjaman->member ? $pinjaman->member->nama : 'Member tidak ditemukan',
            $pinjaman->member ? $pinjaman->member->email : '-',
            $pinjaman->buku ? $pinjaman->buku->judul : 'Buku tidak ditemukan',
            $pinjaman->buku ? $pinjaman->buku->kode_buku : '-',
            $pinjaman->buku ? $pinjaman->buku->penulis : '-',
            $tanggalPinjam->format('d/m/Y'),
            $tanggalKadaluarsa->format('d/m/Y'),
            ucfirst($pinjaman->status),
            $lamaPinjam,
            $statusKeterlambatan,
            $denda > 0 ? number_format($denda, 0, ',', '.') : '0',
            now()->format('d/m/Y H:i:s')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk header
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFE2E8F0',
                    ],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
            // Style untuk semua data
            'A:M' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }
}