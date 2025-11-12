<?php

// app/Providers/ViewServiceProvider.php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\PesanKontak; // PASTIKAN SUDAH DIIMPORT

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // View Composer ini akan berjalan SETIAP KALI file 'layouts.dashboard' dimuat.
        View::composer('layouts.dashboard', function ($view) {
            $unreadCount = 0;
            $latestMessages = collect([]);
            
            // Hanya jalankan jika user sudah login (Auth::check())
            // dan user adalah 'admin' (karena supervisor tidak perlu notif kontak)
            if (auth()->check() && auth()->user()->role === 'admin') {
                $unreadCount = PesanKontak::where('is_read', false)->count();
                $latestMessages = PesanKontak::where('is_read', false)
                                            ->latest()
                                            ->take(5) // Ambil 5 pesan terbaru
                                            ->get();
            }
            
            $view->with('unreadCount', $unreadCount)
                 ->with('latestMessages', $latestMessages);
        });
    }
}