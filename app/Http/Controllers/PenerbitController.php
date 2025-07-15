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
            'name' => 'required|string',
            'alamat' => 'required|string',
            'nomor_telepon' => 'required|string',
        ], [
            'name.required' => 'Nama penerbit wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi',
        ]);

        Penerbit::create($request->all());
        return redirect()->route('penerbit.index')->with('success', 'Penerbit berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'alamat' => 'required|string',
            'nomor_telepon' => 'required|string',
        ], [
            'name.required' => 'Nama penerbit wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi',
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