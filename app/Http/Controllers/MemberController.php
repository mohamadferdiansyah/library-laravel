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
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'email' => 'required|email',
            'nomor_handphone' => 'required',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'email.required' => 'Email wajib diisi',
            'nomor_handphone.required' => 'Nomor handphone wajib diisi',
        ]);

        Member::create($request->all());
        return redirect()->route('member.index')->with('success', 'Member berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'email' => 'required|email|unique',
            'nomor_handphone' => 'required',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'email.required' => 'Email wajib diisi',
            'nomor_handphone.required' => 'Nomor handphone wajib diisi',
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