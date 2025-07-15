<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use App\Models\Member;
use App\Models\Buku;
use App\Exports\PinjamanExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class PinjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pinjaman::with(['member', 'buku']);
        
        // Search functionality
        if ($request->has('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('tanggal_pinjam', 'like', "%{$searchTerm}%")
                  ->orWhere('tanggal_kadaluarsa', 'like', "%{$searchTerm}%")
                  ->orWhere('status', 'like', "%{$searchTerm}%")
                  ->orWhereHas('member', function($memberQuery) use ($searchTerm) {
                      $memberQuery->where('nama', 'like', "%{$searchTerm}%")
                                 ->orWhere('email', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('buku', function($bukuQuery) use ($searchTerm) {
                      $bukuQuery->where('judul', 'like', "%{$searchTerm}%")
                               ->orWhere('kode_buku', 'like', "%{$searchTerm}%")
                               ->orWhere('penulis', 'like', "%{$searchTerm}%");
                  });
            });
        }
        
        // Filter berdasarkan status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Sort berdasarkan parameter
        $sortBy = $request->get('sort', 'tanggal_pinjam');
        $sortOrder = $request->get('order', 'desc');
        
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
        
        $pinjamans = $query->get();
        $members = Member::all();
        $bukus = Buku::all();
        
        return view("pinjaman.index", compact("pinjamans", "members", "bukus"));
    }

    public function export(Request $request)
    {
        $filename = 'data-pinjaman-' . now()->format('Y-m-d-H-i-s') . '.xlsx';
        
        return Excel::download(new PinjamanExport($request), $filename);
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|integer|exists:member,id',
            'buku_id' => 'required|integer|exists:buku,id',
            'tanggal_pinjam' => 'required|date|before_or_equal:today',
            'tanggal_kadaluarsa' => 'required|date|after:tanggal_pinjam',
        ], [
            'member_id.required' => 'Member wajib dipilih',
            'member_id.exists' => 'Member yang dipilih tidak valid',
            'buku_id.required' => 'Buku wajib dipilih',
            'buku_id.exists' => 'Buku yang dipilih tidak valid',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi',
            'tanggal_pinjam.date' => 'Format tanggal pinjam tidak valid',
            'tanggal_pinjam.before_or_equal' => 'Tanggal pinjam tidak boleh lebih dari hari ini',
            'tanggal_kadaluarsa.required' => 'Tanggal kadaluarsa wajib diisi',
            'tanggal_kadaluarsa.date' => 'Format tanggal kadaluarsa tidak valid',
            'tanggal_kadaluarsa.after' => 'Tanggal kadaluarsa harus setelah tanggal pinjam',
        ]);

        // Cek apakah buku sudah dipinjam dan belum dikembalikan
        $existingPinjaman = Pinjaman::where('buku_id', $request->buku_id)
            ->where('status', 'dipinjam')
            ->first();

        if ($existingPinjaman) {
            return redirect()->route('pinjaman.index')
                ->with('fail', 'Buku sudah dipinjam oleh member lain dan belum dikembalikan!');
        }

        $data = $request->all();
        $data['status'] = 'dipinjam';

        Pinjaman::create($data);
        return redirect()->route('pinjaman.index')->with('success', 'Pinjaman berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'member_id' => 'required|integer|exists:member,id',
            'buku_id' => 'required|integer|exists:buku,id',
            'tanggal_pinjam' => 'required|date|before_or_equal:today',
            'status' => 'required|in:dipinjam,dikembalikan',
            'tanggal_kadaluarsa' => 'required|date|after:tanggal_pinjam',
        ], [
            'member_id.required' => 'Member wajib dipilih',
            'member_id.exists' => 'Member yang dipilih tidak valid',
            'buku_id.required' => 'Buku wajib dipilih',
            'buku_id.exists' => 'Buku yang dipilih tidak valid',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi',
            'tanggal_pinjam.date' => 'Format tanggal pinjam tidak valid',
            'tanggal_pinjam.before_or_equal' => 'Tanggal pinjam tidak boleh lebih dari hari ini',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status harus dipinjam atau dikembalikan',
            'tanggal_kadaluarsa.required' => 'Tanggal kadaluarsa wajib diisi',
            'tanggal_kadaluarsa.date' => 'Format tanggal kadaluarsa tidak valid',
            'tanggal_kadaluarsa.after' => 'Tanggal kadaluarsa harus setelah tanggal pinjam',
        ]);

        $pinjaman = Pinjaman::findOrFail($id);
        
        // Cek apakah buku sudah dipinjam oleh member lain (kecuali pinjaman yang sedang diedit)
        if ($request->buku_id != $pinjaman->buku_id && $request->status == 'dipinjam') {
            $existingPinjaman = Pinjaman::where('buku_id', $request->buku_id)
                ->where('status', 'dipinjam')
                ->where('id', '!=', $id)
                ->first();

            if ($existingPinjaman) {
                return redirect()->route('pinjaman.index')
                    ->with('fail', 'Buku sudah dipinjam oleh member lain dan belum dikembalikan!');
            }
        }

        $pinjaman->update($request->all());
        return redirect()->route('pinjaman.index')->with('success', 'Pinjaman berhasil diperbarui!');
    }

    public function returnBook($id)
    {
        $pinjaman = Pinjaman::findOrFail($id);
        
        if ($pinjaman->status !== 'dipinjam') {
            return redirect()->route('pinjaman.index')->with('fail', 'Buku sudah dikembalikan sebelumnya!');
        }

        $pinjaman->update(['status' => 'dikembalikan']);
        return redirect()->route('pinjaman.index')->with('success', 'Buku berhasil dikembalikan!');
    }

    public function destroy($id)
    {
        $pinjaman = Pinjaman::findOrFail($id);
        $pinjaman->delete();
        return redirect()->route('pinjaman.index')->with('success', 'Pinjaman berhasil dihapus!');
    }
}