@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Penerbit</h2>
            <div class="flex space-x-4">
                <!-- Search Form -->
                <form method="GET" action="{{ route('penerbit.index') }}" class="flex">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari penerbit..." 
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
                        <a href="{{ route('penerbit.index') }}" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                            Reset
                        </a>
                    @endif
                </form>
                
                <button onclick="openModal('createPenerbitModal')" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    Tambah Penerbit
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
                        Menampilkan {{ $penerbits->count() }} hasil pencarian untuk "<strong>{{ request('search') }}</strong>"
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
            
            @if($penerbits->count() > 0)
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Alamat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Nomor Telepon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Bergabung</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($penerbits as $penerbit)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if(request('search'))
                                    {!! str_ireplace(request('search'), '<mark class="bg-yellow-200">' . request('search') . '</mark>', $penerbit->name) !!}
                                @else
                                    {{ $penerbit->name }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if(request('search'))
                                    {!! str_ireplace(request('search'), '<mark class="bg-yellow-200">' . request('search') . '</mark>', $penerbit->alamat) !!}
                                @else
                                    {{ $penerbit->alamat }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if(request('search'))
                                    {!! str_ireplace(request('search'), '<mark class="bg-yellow-200">' . request('search') . '</mark>', $penerbit->nomor_telepon) !!}
                                @else
                                    {{ $penerbit->nomor_telepon }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $penerbit->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button onclick="openModal('editPenerbitModal{{ $penerbit->id }}')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm mr-2 transition duration-200">
                                    Edit
                                </button>
                                <button onclick="openModal('deletePenerbitModal{{ $penerbit->id }}')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition duration-200">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">
                        @if(request('search'))
                            Tidak ada penerbit yang ditemukan
                        @else
                            Belum ada penerbit
                        @endif
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if(request('search'))
                            Coba gunakan kata kunci yang berbeda
                        @else
                            Mulai dengan menambahkan penerbit baru
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