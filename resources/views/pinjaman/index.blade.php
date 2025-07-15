@extends('layout')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Daftar Pinjaman</h2>
                <div class="flex space-x-4">
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('pinjaman.index') }}" class="flex">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Cari pinjaman..." 
                                   class="w-64 px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <button type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200">
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('pinjaman.index') }}" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                                Reset
                            </a>
                        @endif
                    </form>
                    
                    <!-- Status Filter -->
                    <form method="GET" action="{{ route('pinjaman.index') }}" class="flex">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <select name="status" onchange="this.form.submit()"
                                class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Status</option>
                            <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        </select>
                    </form>
                    
                    <!-- Export Button -->
                    <a href="{{ route('pinjaman.export') }}" 
                       class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Excel
                    </a>
                    
                    <!-- Add Button -->
                    <button onclick="openModal('createPinjamanModal')"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        Tambah Pinjaman
                    </button>
                </div>
            </div>

            <!-- Search Results Info -->
            @if(request('search'))
                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-blue-800">
                            Menampilkan {{ $pinjamans->count() }} hasil pencarian untuk "<strong>{{ request('search') }}</strong>"
                        </span>
                    </div>
                </div>
            @endif

            <!-- Status Filter Info -->
            @if(request('status'))
                <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <span class="text-yellow-800">
                            Menampilkan {{ $pinjamans->count() }} pinjaman dengan status: <strong>{{ ucfirst(request('status')) }}</strong>
                        </span>
                    </div>
                </div>
            @endif

            <div class="overflow-x-auto">
                @if(session('success'))
                    <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 border border-green-300">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('fail'))
                    <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 border border-red-300">
                        {{ session('fail') }}
                    </div>
                @endif

                @if($pinjamans->count() > 0)
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                                    Member
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                                    Buku
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                                    Tanggal Pinjam
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                                    Tanggal Kadaluarsa
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($pinjamans as $pinjaman)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center space-x-2">
                                            <span>
                                                @if(request('search'))
                                                    {!! str_ireplace(request('search'), '<mark class="bg-yellow-200">' . request('search') . '</mark>', $pinjaman->member ? $pinjaman->member->nama : 'Member tidak ditemukan') !!}
                                                @else
                                                    {{ $pinjaman->member ? $pinjaman->member->nama : 'Member tidak ditemukan' }}
                                                @endif
                                            </span>
                                            @if($pinjaman->member)
                                                <button onclick="openModal('memberDetailModal{{ $pinjaman->member->id }}')"
                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition duration-200"
                                                    title="Detail Member">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center space-x-2">
                                            <span>
                                                @if(request('search'))
                                                    {!! str_ireplace(request('search'), '<mark class="bg-yellow-200">' . request('search') . '</mark>', $pinjaman->buku ? $pinjaman->buku->judul : 'Buku tidak ditemukan') !!}
                                                @else
                                                    {{ $pinjaman->buku ? $pinjaman->buku->judul : 'Buku tidak ditemukan' }}
                                                @endif
                                            </span>
                                            @if($pinjaman->buku)
                                                <button onclick="openModal('bukuDetailModal{{ $pinjaman->buku->id }}')"
                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition duration-200"
                                                    title="Detail Buku">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if(request('search'))
                                            {!! str_ireplace(request('search'), '<mark class="bg-yellow-200">' . request('search') . '</mark>', \Carbon\Carbon::parse($pinjaman->tanggal_pinjam)->format('d/m/Y')) !!}
                                        @else
                                            {{ \Carbon\Carbon::parse($pinjaman->tanggal_pinjam)->format('d/m/Y') }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                                            @if($pinjaman->status == 'dipinjam') bg-yellow-100 text-yellow-800 
                                            @else bg-green-100 text-green-800 @endif">
                                            @if(request('search'))
                                                {!! str_ireplace(request('search'), '<mark class="bg-yellow-200">' . request('search') . '</mark>', ucfirst($pinjaman->status)) !!}
                                            @else
                                                {{ ucfirst($pinjaman->status) }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if(request('search'))
                                            {!! str_ireplace(request('search'), '<mark class="bg-yellow-200">' . request('search') . '</mark>', \Carbon\Carbon::parse($pinjaman->tanggal_kadaluarsa)->format('d/m/Y')) !!}
                                        @else
                                            {{ \Carbon\Carbon::parse($pinjaman->tanggal_kadaluarsa)->format('d/m/Y') }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($pinjaman->status == 'dipinjam')
                                            <button onclick="openModal('returnBookModal{{ $pinjaman->id }}')"
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm mr-2 transition duration-200">
                                                Kembalikan
                                            </button>
                                        @endif

                                        <button onclick="openModal('deletePinjamanModal{{ $pinjaman->id }}')"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition duration-200">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">
                            @if(request('search'))
                                Tidak ada pinjaman yang ditemukan
                            @else
                                Belum ada pinjaman
                            @endif
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if(request('search'))
                                Coba gunakan kata kunci yang berbeda
                            @else
                                Mulai dengan menambahkan pinjaman baru
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Keep all your existing modals here -->
    <!-- Modal Detail Member -->
    @foreach($members as $member)
        <div id="memberDetailModal{{ $member->id }}"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Detail Member</h3>
                        <button onclick="closeModal('memberDetailModal{{ $member->id }}')"
                            class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                                </path>
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                            <p class="text-sm text-gray-900">{{ $member->nama }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <p class="text-sm text-gray-900">{{ $member->email }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <p class="text-sm text-gray-900">{{ $member->alamat }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Handphone</label>
                            <p class="text-sm text-gray-900">{{ $member->nomor_handphone }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bergabung Sejak</label>
                            <p class="text-sm text-gray-900">{{ $member->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button onclick="closeModal('memberDetailModal{{ $member->id }}')"
                            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Modal Detail Buku -->
    @foreach($bukus as $buku)
        <div id="bukuDetailModal{{ $buku->id }}"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Detail Buku</h3>
                        <button onclick="closeModal('bukuDetailModal{{ $buku->id }}')"
                            class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                                </path>
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                            <p class="text-sm text-gray-900">{{ $buku->judul }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Buku</label>
                            <p class="text-sm text-gray-900">{{ $buku->kode_buku }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Terbit</label>
                            <p class="text-sm text-gray-900">{{ $buku->tahun_terbit }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Penulis</label>
                            <p class="text-sm text-gray-900">{{ $buku->penulis }}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ditambahkan Pada</label>
                            <p class="text-sm text-gray-900">{{ $buku->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button onclick="closeModal('bukuDetailModal{{ $buku->id }}')"
                            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Modals -->
    @foreach($pinjamans as $pinjaman)
        <!-- Return Book Modal -->
        @if($pinjaman->status == 'dipinjam')
            <div id="returnBookModal{{ $pinjaman->id }}"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Kembalikan Buku</h3>
                        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin mengubah status pinjaman buku
                            <strong>{{ $pinjaman->buku ? $pinjaman->buku->judul : 'Buku tidak ditemukan' }}</strong> menjadi
                            <strong>dikembalikan</strong>?</p>
                        <form method="POST" action="{{ route('pinjaman.return', $pinjaman->id) }}">
                            @csrf
                            @method('PATCH')
                            <div class="flex justify-end space-x-2">
                                <button type="button" onclick="closeModal('returnBookModal{{ $pinjaman->id }}')"
                                    class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition duration-200">
                                    Ya, Kembalikan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Delete Modal -->
        <div id="deletePinjamanModal{{ $pinjaman->id }}"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Hapus Pinjaman</h3>
                    <p class="text-gray-600 mb-6">Yakin ingin menghapus pinjaman buku
                        <strong>{{ $pinjaman->buku ? $pinjaman->buku->judul : 'Buku tidak ditemukan' }}</strong> oleh
                        <strong>{{ $pinjaman->member ? $pinjaman->member->nama : 'Member tidak ditemukan' }}</strong>?</p>
                    <form method="POST" action="{{ route('pinjaman.destroy', $pinjaman->id) }}">
                        @csrf
                        @method('DELETE')
                        <div class="flex justify-end space-x-2">
                            <button type="button" onclick="closeModal('deletePinjamanModal{{ $pinjaman->id }}')"
                                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition duration-200">
                                Hapus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Create Modal -->
    <div id="createPinjamanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tambah Pinjaman</h3>
                <form method="POST" action="{{ route('pinjaman.store') }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Member</label>
                            <select name="member_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                                <option value="">Pilih Member</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}">{{ $member->nama }} ({{ $member->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Buku</label>
                            <select name="buku_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                                <option value="">Pilih Buku</option>
                                @foreach($bukus as $buku)
                                    <option value="{{ $buku->id }}">{{ $buku->judul }} ({{ $buku->kode_buku }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Meminjam</label>
                            <input type="date" name="tanggal_pinjam"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Tanggal Pinjam" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kadaluarsa</label>
                            <input type="date" name="tanggal_kadaluarsa"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Tanggal Kadaluarsa" required>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" onclick="closeModal('createPinjamanModal')"
                            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200">
                            Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Close modal when clicking outside
        window.onclick = function (event) {
            const modals = document.querySelectorAll('[id$="Modal"]');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        }

        // Auto-submit search on Enter key
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        this.closest('form').submit();
                    }
                });
            }
        });
    </script>
@endsection