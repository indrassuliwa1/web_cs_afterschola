@extends('layouts.dashboard')

@section('title', 'Manajemen Kelas')
@section('page', 'Manajemen Kelas')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded-xl shadow-2xl border border-gray-100">
    
    <h1 class="text-3xl font-extrabold text-gray-800 mb-6 flex items-center">
        <i class="fas fa-chalkboard text-blue-600 mr-3"></i> Manajemen Data Kelas
    </h1>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 shadow-md" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Header & Tombol Tambah --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-700">Daftar Kelas Tersedia</h2>
        <a href="{{ route('admin.kelas.create') }}" 
           class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded flex items-center space-x-1">
            <i class="fas fa-plus mr-1"></i> Tambah Kelas
        </a>
    </div>

    {{-- Tabel Data Kelas --}}
    @if ($kelasList->isEmpty())
        <div class="bg-gray-50 p-10 text-center rounded-xl border border-dashed border-gray-300">
            <p class="text-gray-500 italic text-lg">Belum ada data kelas yang ditemukan.</p>
        </div>
    @else
        <div class="overflow-x-auto border rounded-xl shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Foto</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Kelas</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Harga (Rp)</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kontrak Aktif</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach ($kelasList as $kelas)
                        <tr class="group hover:bg-blue-50/50 transition duration-150">
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $loop->iteration + ($kelasList->currentPage() - 1) * $kelasList->perPage() }}
                            </td>

                            {{-- Kolom Foto --}}
                            <td class="px-4 py-2">
                                @if ($kelas->foto_kelas)
                                    <img src="{{ asset('uploads/kelas/' . $kelas->foto_kelas) }}" alt="Foto Kelas" class="w-12 h-12 object-cover rounded-md border">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded-md flex items-center justify-center text-xs text-gray-500">N/A</div>
                                @endif
                            </td>
                            
                            <td class="px-4 py-4 text-sm font-medium text-gray-900 group-hover:text-blue-700">
                                {{ $kelas->nama_kelas }}
                            </td>
                            
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700 font-semibold">
                                Rp {{ number_format($kelas->harga, 0, ',', '.') }}
                            </td>
                            
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                                <span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-xs font-medium">
                                    {{ $kelas->kontrak_count }} Kontrak
                                </span>
                            </td>
                            
                            {{-- Aksi --}}
                            <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-3">
                                    
                                    {{-- Tombol Detail --}}
                                    <a href="{{ route('admin.kelas.show', $kelas->id) }}" title="Lihat Detail" class="text-indigo-600 hover:text-indigo-800 transform hover:scale-110 transition duration-150">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.kelas.edit', $kelas->id) }}" title="Edit" class="text-blue-600 hover:text-blue-800 transform hover:scale-110 transition duration-150">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('admin.kelas.destroy', $kelas->id) }}" method="POST" onsubmit="return confirm('Menghapus kelas ini akan mempengaruhi {{ $kelas->kontrak_count }} kontrak. Yakin ingin menghapus?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus" class="text-red-600 hover:text-red-800 transform hover:scale-110 transition duration-150">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $kelasList->links() }}
        </div>
    @endif
</div>
@endsection
