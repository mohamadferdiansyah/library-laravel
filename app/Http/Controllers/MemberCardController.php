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
            'member_id' => 'required|integer',
            'nomor_kartu' => 'required|string',
            'tanggal_aktif' => 'required|date|before_or_equal:today',
            'tanggal_kadaluarsa' => 'required|date|after:tanggal_aktif',
        ], [
            'member_id.required' => 'Member wajib dipilih',
            'nomor_kartu.required' => 'Nomor kartu wajib diisi',
            'tanggal_aktif.required' => 'Tanggal aktif wajib diisi',
            'tanggal_kadaluarsa.required' => 'Tanggal kadaluarsa wajib diisi',
        ]);

        MemberCard::create($request->all());
        return redirect()->route('member-card.index')->with('success', 'Member card berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'member_id' => 'required|integer|exists:member,id|unique:member_card,member_id,' . $id,
            'nomor_kartu' => 'required|string|min:8|max:20|unique:member_card,nomor_kartu,' . $id . '|regex:/^[0-9A-Z\-]+$/',
            'tanggal_aktif' => 'required|date|before_or_equal:today',
            'tanggal_kadaluarsa' => 'required|date|after:tanggal_aktif',
        ], [
            'member_id.required' => 'Member wajib dipilih',
            'nomor_kartu.required' => 'Nomor kartu wajib diisi',
            'tanggal_aktif.required' => 'Tanggal aktif wajib diisi',
            'tanggal_kadaluarsa.required' => 'Tanggal kadaluarsa wajib diisi',
        ]);

        $memberCard = MemberCard::findOrFail($id);
        $memberCard->update($request->all());
        return redirect()->route('member-card.index')->with('success', 'Member card berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $memberCard = MemberCard::findOrFail($id);
        $memberCard->delete();
        return redirect()->route('member-card.index')->with('success', 'Member card berhasil dihapus!');
    }
}