<?php

namespace App\Http\Controllers;

use App\Models\Penerbit;
use Illuminate\Http\Request;

class PenerbitController extends Controller
{
    public function index(Request $request)
    {
        $query = Penerbit::query();
        
        // Search functionality
        if ($request->has('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('alamat', 'like', "%{$searchTerm}%")
                  ->orWhere('nomor_telepon', 'like', "%{$searchTerm}%");
            });
        }
        
        $penerbits = $query->get();
        return view("penerbit.index", compact("penerbits"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:2|regex:/^[a-zA-Z\s\.]+$/',
            'alamat' => 'required|string|max:500|min:5',
            'nomor_telepon' => 'required|string|min:10|max:15|regex:/^[0-9\+\-\s]+$/',
        ], [
            'name.required' => 'Nama penerbit wajib diisi',
            'name.min' => 'Nama penerbit minimal 2 karakter',
            'name.max' => 'Nama penerbit maksimal 255 karakter',
            'name.regex' => 'Nama penerbit hanya boleh berisi huruf, spasi, dan titik',
            'alamat.required' => 'Alamat wajib diisi',
            'alamat.min' => 'Alamat minimal 5 karakter',
            'alamat.max' => 'Alamat maksimal 500 karakter',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi',
            'nomor_telepon.min' => 'Nomor telepon minimal 10 karakter',
            'nomor_telepon.max' => 'Nomor telepon maksimal 15 karakter',
            'nomor_telepon.regex' => 'Nomor telepon hanya boleh berisi angka, +, -, dan spasi',
        ]);

        Penerbit::create($request->all());
        return redirect()->route('penerbit.index')->with('success', 'Penerbit berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:2|regex:/^[a-zA-Z\s\.]+$/',
            'alamat' => 'required|string|max:500|min:5',
            'nomor_telepon' => 'required|string|min:10|max:15|regex:/^[0-9\+\-\s]+$/',
        ], [
            'name.required' => 'Nama penerbit wajib diisi',
            'name.min' => 'Nama penerbit minimal 2 karakter',
            'name.max' => 'Nama penerbit maksimal 255 karakter',
            'name.regex' => 'Nama penerbit hanya boleh berisi huruf, spasi, dan titik',
            'alamat.required' => 'Alamat wajib diisi',
            'alamat.min' => 'Alamat minimal 5 karakter',
            'alamat.max' => 'Alamat maksimal 500 karakter',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi',
            'nomor_telepon.min' => 'Nomor telepon minimal 10 karakter',
            'nomor_telepon.max' => 'Nomor telepon maksimal 15 karakter',
            'nomor_telepon.regex' => 'Nomor telepon hanya boleh berisi angka, +, -, dan spasi',
        ]);

        $penerbit = Penerbit::findOrFail($id);
        $penerbit->update($request->all());
        return redirect()->route('penerbit.index')->with('success', 'Penerbit berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $penerbit = Penerbit::findOrFail($id);
        $penerbit->delete();
        return redirect()->route('penerbit.index')->with('success', 'Penerbit berhasil dihapus!');
    }
}