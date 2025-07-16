<?php

namespace App\Http\Controllers;

use App\Models\MemberCard;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberCardController extends Controller
{
    public function index(Request $request)
    {
        $query = MemberCard::with('member');
        
        // Search functionality
        if ($request->has('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nomor_kartu', 'like', "%{$searchTerm}%")
                  ->orWhere('tanggal_aktif', 'like', "%{$searchTerm}%")
                  ->orWhere('tanggal_kadaluarsa', 'like', "%{$searchTerm}%")
                  ->orWhereHas('member', function($memberQuery) use ($searchTerm) {
                      $memberQuery->where('nama', 'like', "%{$searchTerm}%")
                                 ->orWhere('email', 'like', "%{$searchTerm}%");
                  });
            });
        }
        
        $memberCards = $query->get();
        $members = Member::all();
        
        return view("member_card.index", compact("memberCards", "members"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:member,id',
            'nomor_kartu' => 'required|unique:member_card,nomor_kartu',
            'tanggal_aktif' => 'required|date',
            'tanggal_kadaluarsa' => 'required|date|after:tanggal_aktif',
        ], [
            'member_id.required' => 'Member wajib dipilih',
            'member_id.exists' => 'Member tidak ditemukan',
            'nomor_kartu.required' => 'Nomor kartu wajib diisi',
            'nomor_kartu.unique' => 'Nomor kartu sudah digunakan',
            'tanggal_aktif.required' => 'Tanggal aktif wajib diisi',
            'tanggal_aktif.date' => 'Format tanggal aktif tidak valid',
            'tanggal_kadaluarsa.required' => 'Tanggal kadaluarsa wajib diisi',
            'tanggal_kadaluarsa.date' => 'Format tanggal kadaluarsa tidak valid',
            'tanggal_kadaluarsa.after' => 'Tanggal kadaluarsa harus setelah tanggal aktif',
        ]);

        // Cek apakah member sudah punya kartu
        $existingCard = MemberCard::where('member_id', $request->member_id)->first();
        if ($existingCard) {
            return redirect()->route('member-card.index')
                ->with('fail', 'Member sudah memiliki kartu member');
        }

        MemberCard::create($request->all());
        return redirect()->route('member-card.index')->with('success', 'Member card berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'member_id' => 'required|unique:member,id~|exists:member,id',
            'nomor_kartu' => 'required|unique:member_card,nomor_kartu,' . $id,
            'tanggal_aktif' => 'required|date',
            'tanggal_kadaluarsa' => 'required|date|after:tanggal_aktif',
        ], [
            'member_id.required' => 'Member wajib dipilih',
            'member_id.unique' => 'Member sudah memiliki kartu',
            'member_id.exists' => 'Member tidak ditemukan',
            'nomor_kartu.required' => 'Nomor kartu wajib diisi',
            'nomor_kartu.unique' => 'Nomor kartu sudah digunakan',
            'tanggal_aktif.required' => 'Tanggal aktif wajib diisi',
            'tanggal_aktif.date' => 'Format tanggal aktif tidak valid',
            'tanggal_kadaluarsa.required' => 'Tanggal kadaluarsa wajib diisi',
            'tanggal_kadaluarsa.date' => 'Format tanggal kadaluarsa tidak valid',
            'tanggal_kadaluarsa.after' => 'Tanggal kadaluarsa harus setelah tanggal aktif',
        ]);

        // Cek apakah member sudah punya kartu lain (kecuali yang sedang diedit)
        $existingCard = MemberCard::where('member_id', $request->member_id)
            ->where('id', '!=', $id)
            ->first();
        if ($existingCard) {
            return redirect()->route('member-card.index')
                ->with('fail', 'Member sudah memiliki kartu member lain');
        }

        $memberCard = MemberCard::findOrFail($id);
        $memberCard->update($request->all());
        return redirect()->route('member-card.index')->with('success', 'Member card berhasil diperbarui!');
    }
}