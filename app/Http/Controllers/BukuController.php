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
            'judul' => 'required|string|max:255|min:2',
            'kode_buku' => 'required|string|max:50|min:3|unique:buku,kode_buku|regex:/^[A-Z0-9\-]+$/',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'penulis' => 'required|string|max:255|min:2|regex:/^[a-zA-Z\s\.]+$/',
            'penerbit_id' => 'required|integer|exists:penerbit,id',
        ], [
            'judul.required' => 'Judul buku wajib diisi',
            'judul.min' => 'Judul buku minimal 2 karakter',
            'judul.max' => 'Judul buku maksimal 255 karakter',
            'kode_buku.required' => 'Kode buku wajib diisi',
            'kode_buku.min' => 'Kode buku minimal 3 karakter',
            'kode_buku.max' => 'Kode buku maksimal 50 karakter',
            'kode_buku.unique' => 'Kode buku sudah ada, gunakan kode lain',
            'kode_buku.regex' => 'Kode buku hanya boleh berisi huruf besar, angka, dan tanda hubung',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa angka',
            'tahun_terbit.min' => 'Tahun terbit minimal 1900',
            'tahun_terbit.max' => 'Tahun terbit maksimal ' . date('Y'),
            'penulis.required' => 'Nama penulis wajib diisi',
            'penulis.min' => 'Nama penulis minimal 2 karakter',
            'penulis.max' => 'Nama penulis maksimal 255 karakter',
            'penulis.regex' => 'Nama penulis hanya boleh berisi huruf, spasi, dan titik',
            'penerbit_id.required' => 'Penerbit wajib dipilih',
            'penerbit_id.exists' => 'Penerbit yang dipilih tidak valid',
        ]);

        Buku::create($request->all());
        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255|min:2',
            'kode_buku' => 'required|string|max:50|min:3|unique:buku,kode_buku,' . $id . '|regex:/^[A-Z0-9\-]+$/',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'penulis' => 'required|string|max:255|min:2|regex:/^[a-zA-Z\s\.]+$/',
            'penerbit_id' => 'required|integer|exists:penerbit,id',
        ], [
            'judul.required' => 'Judul buku wajib diisi',
            'judul.min' => 'Judul buku minimal 2 karakter',
            'judul.max' => 'Judul buku maksimal 255 karakter',
            'kode_buku.required' => 'Kode buku wajib diisi',
            'kode_buku.min' => 'Kode buku minimal 3 karakter',
            'kode_buku.max' => 'Kode buku maksimal 50 karakter',
            'kode_buku.unique' => 'Kode buku sudah ada, gunakan kode lain',
            'kode_buku.regex' => 'Kode buku hanya boleh berisi huruf besar, angka, dan tanda hubung',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa angka',
            'tahun_terbit.min' => 'Tahun terbit minimal 1900',
            'tahun_terbit.max' => 'Tahun terbit maksimal ' . date('Y'),
            'penulis.required' => 'Nama penulis wajib diisi',
            'penulis.min' => 'Nama penulis minimal 2 karakter',
            'penulis.max' => 'Nama penulis maksimal 255 karakter',
            'penulis.regex' => 'Nama penulis hanya boleh berisi huruf, spasi, dan titik',
            'penerbit_id.required' => 'Penerbit wajib dipilih',
            'penerbit_id.exists' => 'Penerbit yang dipilih tidak valid',
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