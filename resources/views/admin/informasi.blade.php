@extends('layouts.dashboard')

@section('title', 'Manajemen Informasi')
@section('page', 'Manajemen Informasi')

@section('content')
<div class="max-w-6xl mx-auto animate-fade-in">
    
    {{-- KARTU UTAMA --}}
    <div class="bg-white p-8 md:p-10 rounded-2xl shadow-3xl border-t-4 border-indigo-600">

        {{-- HEADER --}}
        <h1 class="text-4xl font-extrabold text-gray-900 mb-2 flex items-center">
            <i class="fas fa-layer-group text-indigo-600 mr-3"></i> Manajemen Informasi
        </h1>
        <p class="text-gray-500 mb-6 border-b pb-4">Kelola semua data Berita, Trainer, dan Prestasi di sini.</p>
        
        {{-- NOTIFIKASI SUKSES (Lebih mencolok) --}}
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-lg shadow-md mb-6 transition duration-300" role="alert">
                <div class="flex items-center">
                    <div class="py-1"><i class="fas fa-check-circle text-green-500 mr-3 text-lg"></i></div>
                    <div>
                        <p class="font-bold">Sukses!</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif
        
        {{-- NAVIGASI TAB DINAMIS --}}
        @php
            $tabs = [
                'general' => ['name' => 'Berita General', 'icon' => 'fa-newspaper', 'color' => 'indigo'],
                'trainer' => ['name' => 'Informasi Trainer', 'icon' => 'fa-users', 'color' => 'teal'],
                'prestasi' => ['name' => 'Informasi Prestasi', 'icon' => 'fa-trophy', 'color' => 'yellow'],
            ];
            $activeCategory = $activeCategory ?? 'general'; 
        @endphp

        <div class="flex flex-wrap gap-4 mb-6">
            @foreach ($tabs as $key => $tab)
                @php
                    $isActive = $activeCategory == $key;
                    $bgColor = $isActive ? 'bg-' . $tab['color'] . '-600' : 'bg-white hover:bg-gray-50';
                    $textColor = $isActive ? 'text-white' : 'text-gray-700';
                    $borderColor = $isActive ? 'border-' . $tab['color'] . '-600' : 'border-gray-200';
                    $totalCount = ${'total' . ucfirst($key)} ?? 0;
                @endphp
                
                {{-- LINK ASLI ANDA --}}
                <a href="{{ route('admin.informasi', ['kategori' => $key]) }}" 
                   class="flex items-center px-6 py-2.5 text-sm font-semibold rounded-xl border-2 transition duration-300 transform hover:scale-[1.02] shadow-sm 
                          {{ $bgColor }} {{ $textColor }} {{ $borderColor }} 
                          {{ $isActive ? 'shadow-lg ' . 'shadow-' . $tab['color'] . '-200' : '' }}">
                    <i class="fas {{ $tab['icon'] }} mr-2"></i> 
                    {{ $tab['name'] }} <span class="ml-2 font-bold opacity-80">({{ $totalCount }})</span>
                </a>
            @endforeach
        </div>

        {{-- KONTEN UTAMA TAB --}}
        <div class="space-y-6">
            <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas {{ $tabs[$activeCategory]['icon'] }} text-{{ $tabs[$activeCategory]['color'] }}-600 mr-2"></i>
                    Tabel Data: {{ $tabs[$activeCategory]['name'] }}
                </h2>
                
                {{-- Tombol Tambah --}}
                @php
                    // Logika penentuan route CREATE ASLI ANDA
                    $createRoute = '';
                    if ($activeCategory == 'trainer') {
                        $createRoute = route('admin.trainer.create');
                    } elseif ($activeCategory == 'prestasi') {
                        $createRoute = route('admin.prestasi.create'); 
                    } else {
                        $createRoute = route('admin.informasi.create', ['kategori' => $activeCategory]);
                    }
                @endphp
                
                {{-- LINK ASLI ANDA --}}
                <a href="{{ $createRoute }}" 
                   class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded flex items-center space-x-1">
                    <i class="fas fa-plus mr-2"></i> Tambah Baru
                </a>
            </div>

            {{-- Tabel Data --}}
            @if ($dataInformasi->isEmpty())
                <div class="bg-gray-50 p-10 text-center rounded-xl border-2 border-dashed border-gray-300 shadow-inner">
                    <i class="fas fa-box-open text-gray-400 text-4xl mb-3"></i>
                    <p class="text-gray-600 font-semibold text-lg">Belum ada data {{ $tabs[$activeCategory]['name'] }} yang ditemukan.</p>
                </div>
            @else
                <div class="overflow-x-auto shadow-lg rounded-xl border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-indigo-50/70 border-b-2 border-indigo-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Judul / Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">
                                    @if ($activeCategory == 'trainer') Spesialisasi @else Penulis @endif
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Tgl. Terbit/Dicapai</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-indigo-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($dataInformasi as $info)
                                <tr class="hover:bg-blue-50/50 transition duration-150">
                                    {{-- Jarak Dikurangi dari py-4 menjadi py-2 --}}
                                    <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-600">{{ $loop->iteration + ($dataInformasi->currentPage() - 1) * $dataInformasi->perPage() }}</td>
                                    
                                    {{-- Judul / Nama --}}
                                    <td class="px-6 py-2 text-sm font-semibold text-gray-900">
                                        {{ $activeCategory == 'trainer' ? $info->nama : $info->judul }}
                                        @if ($info->gambar)
                                            <i class="fas fa-image text-gray-400 ml-2" title="Memiliki Gambar"></i>
                                        @endif
                                    </td>
                                    
                                    {{-- Penulis / Spesialisasi --}}
                                    <td class="px-6 py-2 text-sm text-gray-700">
                                        @if ($activeCategory == 'trainer')
                                            <span class="inline-block bg-teal-100 text-teal-800 px-3 py-1 text-xs rounded-full font-medium">{{ $info->spesialisasi }}</span>
                                        @else
                                            {{ $info->author->name ?? 'N/A' }}
                                        @endif
                                    </td>
                                    
                                    {{-- Tanggal --}}
                                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            $dateField = $activeCategory == 'prestasi' ? $info->tanggal_dicapai : ($info->tanggal ?? $info->created_at);
                                        @endphp
                                        <i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($dateField)->format('d M Y') }}
                                    </td>
                                    
                                    {{-- Aksi (Style Tetap) --}}
                                    <td class="px-6 py-2 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex justify-center space-x-2">
                                            @php
                                                $baseRoute = $activeCategory == 'trainer' ? 'admin.trainer' : ($activeCategory == 'prestasi' ? 'admin.prestasi' : 'admin.informasi');
                                                $idParam = $info->id;
                                            @endphp
                                            
                                            {{-- Tombol Lihat (Detail) - Biru Solid --}}
                                            <a href="{{ route($baseRoute . '.show', $idParam) }}" title="Lihat Detail" 
                                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition duration-150 transform hover:scale-110">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            {{-- Tombol Edit - Kuning Keemasan Solid --}}
                                            <a href="{{ route($baseRoute . '.edit', [$idParam, 'kategori' => $activeCategory]) }}" title="Edit" 
                                               class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-sm transition duration-150 transform hover:scale-110">
                                                <i class="fas fa-pen-to-square"></i>
                                            </a>
                                            
                                            {{-- Tombol Hapus - Merah Solid --}}
                                            <form action="{{ route($baseRoute . '.destroy', $idParam) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus {{ $info->judul ?? $info->nama }}?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus" 
                                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition duration-150 transform hover:scale-110">
                                                    <i class="fas fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination --}}
                <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200 flex justify-center">
                    {{ $dataInformasi->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection