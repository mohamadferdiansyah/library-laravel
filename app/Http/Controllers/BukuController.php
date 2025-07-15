<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Penerbit;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with('penerbit');
        
        // Search functionality
        if ($request->has('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('judul', 'like', "%{$searchTerm}%")
                  ->orWhere('kode_buku', 'like', "%{$searchTerm}%")
                  ->orWhere('penulis', 'like', "%{$searchTerm}%")
                  ->orWhere('tahun_terbit', 'like', "%{$searchTerm}%")
                  ->orWhereHas('penerbit', function($penerbitQuery) use ($searchTerm) {
                      $penerbitQuery->where('name', 'like', "%{$searchTerm}%");
                  });
            });
        }
        
        $bukus = $query->get();
        $penerbits = Penerbit::all();
        return view("buku.index", compact("bukus", "penerbits"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'kode_buku' => 'required|string',
            'tahun_terbit' => 'required|integer',
            'penulis' => 'required|string',
            'penerbit_id' => 'required|integer',
        ], [
            'judul.required' => 'Judul buku wajib diisi',
            'kode_buku.required' => 'Kode buku wajib diisi',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi',
            'penulis.required' => 'Nama penulis wajib diisi',
            'penerbit_id.required' => 'Penerbit wajib dipilih',
        ]);

        Buku::create($request->all());
        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string',
            'kode_buku' => 'required|string',
            'tahun_terbit' => 'required|integer',
            'penulis' => 'required|string',
            'penerbit_id' => 'required|integer',
        ], [
            'judul.required' => 'Judul buku wajib diisi',
            'kode_buku.required' => 'Kode buku wajib diisi',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi',
            'penulis.required' => 'Nama penulis wajib diisi',
            'penerbit_id.required' => 'Penerbit wajib dipilih',
        ]);

        $buku = Buku::findOrFail($id);
        $buku->update($request->all());
        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}