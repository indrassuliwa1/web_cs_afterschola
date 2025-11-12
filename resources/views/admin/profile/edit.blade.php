@extends('layouts.dashboard')

@section('title', 'Pengaturan Profil')
@section('page', 'Pengaturan Profil Admin')

@section('content')
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-2xl border border-gray-100">
        <h1 class="text-3xl font-extrabold text-gray-800 mb-6 border-b pb-2">
            <i class="fas fa-user-cog mr-2 text-blue-600"></i> Pengaturan Profil
        </h1>

        {{-- Notifikasi Sukses --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline"> {{ session('success') }}</span>
            </div>
        @endif

        {{-- Notifikasi Error (dari Controller) --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Perhatian!</strong>
                <span class="block sm:inline"> Silakan perbaiki kesalahan berikut:</span>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Update Profile --}}
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- BAGIAN FOTO PROFIL --}}
            <h3 class="text-xl font-bold text-gray-700 border-b pb-2">Foto Profil</h3>
            <div class="flex items-center space-x-6">
                <div class="flex-shrink-0">
                    @php
                        $profilePicPath = $user->profile_picture
                            ? asset('uploads/profile_pictures/' . $user->profile_picture)
                            : asset('images/default_profile.png');
                    @endphp
                    <img id="current-photo" class="h-20 w-20 object-cover rounded-full border-2 border-blue-500 shadow-md"
                        src="{{ $profilePicPath }}" alt="Foto Profil">
                </div>
                <div>
                    <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-1">Ganti Foto Baru (Max
                        2MB)</label>
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
            </div>

            {{-- BAGIAN INFORMASI UMUM --}}
            <h3 class="text-xl font-bold text-gray-700 border-b pb-2 pt-4">Informasi Akun</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span
                            class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            {{-- BAGIAN PERUBAHAN PASSWORD --}}
            <h3 class="text-xl font-bold text-gray-700 border-b pb-2 pt-4">Ganti Password (Opsional)</h3>
            <p class="text-sm text-gray-500 italic">Isi kolom di bawah hanya jika Anda ingin mengganti password Anda.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat
                        Ini</label>
                    <input type="password" name="current_password" id="current_password"
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
                    @error('current_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <input type="password" name="password" id="password"
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi
                        Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end pt-6 border-t mt-6">
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
