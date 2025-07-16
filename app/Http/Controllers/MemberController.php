<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberCard;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::with('memberCard');
        
        // Search functionality
        if ($request->has('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('alamat', 'like', "%{$searchTerm}%")
                  ->orWhere('nomor_handphone', 'like', "%{$searchTerm}%");
            });
        }
        
        $members = $query->get();
        $memberCards = MemberCard::with('member')->get();
        
        return view("member.index", compact("members", "memberCards"));
    }

    public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'alamat' => 'required|string|max:500',
        'email' => 'required|email|unique:member,email',
        'nomor_handphone' => 'required|string|max:20|unique:member,nomor_handphone',
    ], [
        'nama.required' => 'Nama wajib diisi',
        'nama.max' => 'Nama maksimal 255 karakter',
        'alamat.required' => 'Alamat wajib diisi',
        'alamat.max' => 'Alamat maksimal 500 karakter',
        'email.required' => 'Email wajib diisi',
        'email.email' => 'Format email tidak valid',
        'email.unique' => 'Email sudah digunakan',
        'nomor_handphone.required' => 'Nomor handphone wajib diisi',
        'nomor_handphone.max' => 'Nomor handphone maksimal 20 karakter',
        'nomor_handphone.unique' => 'Nomor handphone sudah digunakan',
    ]);

    Member::create($request->all());
    return redirect()->route('member.index')->with('success', 'Member berhasil ditambahkan!');
}

public function update(Request $request, $id)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'alamat' => 'required|string|max:500',
        'email' => 'required|email|unique:member,email,' . $id,
        'nomor_handphone' => 'required|string|max:20|unique:member,nomor_handphone,' . $id,
    ], [
        'nama.required' => 'Nama wajib diisi',
        'nama.max' => 'Nama maksimal 255 karakter',
        'alamat.required' => 'Alamat wajib diisi',
        'alamat.max' => 'Alamat maksimal 500 karakter',
        'email.required' => 'Email wajib diisi',
        'email.email' => 'Format email tidak valid',
        'email.unique' => 'Email sudah digunakan',
        'nomor_handphone.required' => 'Nomor handphone wajib diisi',
        'nomor_handphone.max' => 'Nomor handphone maksimal 20 karakter',
        'nomor_handphone.unique' => 'Nomor handphone sudah digunakan',
    ]);

    $member = Member::findOrFail($id);
    $member->update($request->all());
    return redirect()->route('member.index')->with('success', 'Member berhasil diperbarui!');
}

    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $member->delete();
        return redirect()->route('member.index')->with('success', 'Member berhasil dihapus!');
    }
}