@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Buku</h2>
            <div class="flex space-x-4">
                <!-- Search Form -->
                <form method="GET" action="{{ route('buku.index') }}" class="flex">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari buku..." 
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
                        <a href="{{ route('buku.index') }}" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                            Reset
                        </a>
                    @endif
                </form>
                
                <button onclick="openModal('createBukuModal')" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    Tambah Buku
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
                        Menampilkan {{ $bukus->count() }} hasil pencarian untuk "<strong>{{ request('search') }}</strong>"
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
            
            @if($bukus->count() > 0)
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Kode Buku</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Tahun Terbit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Penulis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Penerbit</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($bukus as $buku)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if(request('search'))
                                    {!! str_ireplace(request('search'), '<mark class="bg-yellow-200">' . request('search') . '</mark>', $buku->judul) !!}
                                @else
                                    {{ $buku->judul }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if(request('search'))
                                    {!! str_ireplace(request('search'), '<mark class="bg-yellow-200">' . request('search') . '</mark>', $buku->kode_buku) !!}
                                @else
                                    {{ $buku->kode_buku }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $buku->tahun_terbit }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if(request('search'))
                                    {!! str_ireplace(request('search'), '<mark class="bg-yellow-200">' . request('search') . '</mark>', $buku->penulis) !!}
                                @else
                                    {{ $buku->penulis }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center space-x-2">
                                    <span>
                                        @if(request('search'))
                                            {!! str_ireplace(request('search'), '<mark class="bg-yellow-200">' . request('search') . '</mark>', $buku->penerbit ? $buku->penerbit->name : 'Penerbit tidak ditemukan') !!}
                                        @else
                                            {{ $buku->penerbit ? $buku->penerbit->name : 'Penerbit tidak ditemukan' }}
                                        @endif
                                    </span>
                                    @if($buku->penerbit)
                                        <button onclick="openModal('penerbitDetailModal{{ $buku->penerbit->id }}')" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition duration-200" title="Detail Penerbit">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button onclick="openModal('editBukuModal{{ $buku->id }}')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm mr-2 transition duration-200">
                                    Edit
                                </button>
                                <button onclick="openModal('deleteBukuModal{{ $buku->id }}')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition duration-200">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">
                        @if(request('search'))
                            Tidak ada buku yang ditemukan
                        @else
                            Belum ada buku
                        @endif
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if(request('search'))
                            Coba gunakan kata kunci yang berbeda
                        @else
                            Mulai dengan menambahkan buku baru
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Keep all existing modals and scripts -->
<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Close modal when clicking outside
window.onclick = function(event) {
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