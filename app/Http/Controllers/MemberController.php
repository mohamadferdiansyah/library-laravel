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
            'nama' => 'required|string|max:255|min:2|regex:/^[a-zA-Z\s\.]+$/',
            'alamat' => 'required|string|max:500|min:5',
            'email' => 'required|email|unique:member,email|max:255',
            'nomor_handphone' => 'required|string|min:10|max:15|regex:/^[0-9\+\-\s]+$/',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'nama.min' => 'Nama minimal 2 karakter',
            'nama.max' => 'Nama maksimal 255 karakter',
            'nama.regex' => 'Nama hanya boleh berisi huruf, spasi, dan titik',
            'alamat.required' => 'Alamat wajib diisi',
            'alamat.min' => 'Alamat minimal 5 karakter',
            'alamat.max' => 'Alamat maksimal 500 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar, gunakan email lain',
            'email.max' => 'Email maksimal 255 karakter',
            'nomor_handphone.required' => 'Nomor handphone wajib diisi',
            'nomor_handphone.min' => 'Nomor handphone minimal 10 karakter',
            'nomor_handphone.max' => 'Nomor handphone maksimal 15 karakter',
            'nomor_handphone.regex' => 'Nomor handphone hanya boleh berisi angka, +, -, dan spasi',
        ]);

        Member::create($request->all());
        return redirect()->route('member.index')->with('success', 'Member berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255|min:2|regex:/^[a-zA-Z\s\.]+$/',
            'alamat' => 'required|string|max:500|min:5',
            'email' => 'required|email|unique:member,email,' . $id . '|max:255',
            'nomor_handphone' => 'required|string|min:10|max:15|regex:/^[0-9\+\-\s]+$/',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'nama.min' => 'Nama minimal 2 karakter',
            'nama.max' => 'Nama maksimal 255 karakter',
            'nama.regex' => 'Nama hanya boleh berisi huruf, spasi, dan titik',
            'alamat.required' => 'Alamat wajib diisi',
            'alamat.min' => 'Alamat minimal 5 karakter',
            'alamat.max' => 'Alamat maksimal 500 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar, gunakan email lain',
            'email.max' => 'Email maksimal 255 karakter',
            'nomor_handphone.required' => 'Nomor handphone wajib diisi',
            'nomor_handphone.min' => 'Nomor handphone minimal 10 karakter',
            'nomor_handphone.max' => 'Nomor handphone maksimal 15 karakter',
            'nomor_handphone.regex' => 'Nomor handphone hanya boleh berisi angka, +, -, dan spasi',
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