@extends('layouts.dashboard')

@section('title', 'Detail Pesan Kontak')
@section('page', 'Pesan Masuk')

@section('content')
    <div class="container mx-auto p-4 md:p-8 min-h-screen bg-gray-50">
        <div class="max-w-5xl mx-auto">

            <h1 class="text-3xl font-extrabold text-gray-800 mb-6 flex items-center border-b pb-3">
                <i class="fas fa-envelope-open-text text-blue-600 mr-2"></i> Detail Pesan dari {{ $pesan->contactName }}
            </h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

                {{-- KOLOM SENTIMEN ANALYSIS (ML) --}}
                <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-lg border-t-4 border-purple-500 h-fit">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 flex items-center">
                        <i class="fas fa-robot mr-2 text-purple-600"></i> Analisis Sentimen (ML)
                    </h3>

                    @if (isset($sentimentResult['error']))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md">
                            <p class="font-bold">Error ML Service!</p>
                            <p class="text-sm">{{ $sentimentResult['message'] }}</p>
                        </div>
                    @else
                        @php
                            $sentiment = strtolower($sentimentResult['predicted_sentiment'] ?? 'neutral');
                            $color =
                                [
                                    'positive' => 'bg-green-100 text-green-800 border-green-500',
                                    'negative' => 'bg-red-100 text-red-800 border-red-500',
                                    'neutral' => 'bg-gray-100 text-gray-800 border-gray-500',
                                ][$sentiment] ?? 'bg-gray-100 text-gray-800 border-gray-500';
                        @endphp
                        <div class="border rounded-lg p-4 {{ $color }}">
                            <p class="text-sm text-gray-600 mb-2">Prediksi Model:</p>
                            <span class="text-2xl font-extrabold capitalize block">
                                {{ ucfirst($sentiment) }}
                            </span>
                            <p class="mt-3 text-xs text-gray-700">Ini menunjukkan {{ $pesan->contactName }} memiliki respons
                                **{{ ucfirst($sentiment) }}** terhadap layanan/produk.</p>
                        </div>
                    @endif
                </div>

                {{-- KOLOM DETAIL PESAN --}}
                <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-lg border-t-4 border-blue-500">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 flex items-center">
                        <i class="fas fa-user-tag mr-2 text-blue-600"></i> Info Pengirim
                    </h3>

                    <div class="space-y-3 text-gray-700 text-sm mb-6">
                        <p><strong>Nama:</strong> {{ $pesan->contactName }}</p>
                        <p><strong>Email:</strong> <a href="mailto:{{ $pesan->contactEmail }}"
                                class="text-blue-500 hover:underline">{{ $pesan->contactEmail }}</a></p>
                        <p><strong>Subjek:</strong> {{ $pesan->contactSubject }}</p>
                        <p><strong>Dikirim pada:</strong>
                            {{ \Carbon\Carbon::parse($pesan->created_at)->translatedFormat('d F Y, H:i') }} WIB</p>
                    </div>

                    <h3 class="text-xl font-semibold mb-3 text-gray-800 flex items-center pt-4 border-t">
                        <i class="fas fa-comment-dots mr-2 text-blue-600"></i> Isi Pesan
                    </h3>
                    <div class="p-4 bg-gray-50 border rounded-lg whitespace-pre-wrap text-gray-800">
                        {{ $pesan->contactComment }}
                    </div>

                    <div class="flex justify-end mt-6">
                        <form action="{{ route('admin.pesan.destroy', $pesan->id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2 rounded-lg transition duration-150 shadow-md">
                                <i class="fas fa-trash-alt mr-2"></i> Hapus Pesan
                            </button>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
