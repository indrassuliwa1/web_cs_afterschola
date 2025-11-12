<x-mail::message>
# Pendaftaran Anda Berhasil!

Halo, **{{ $pendaftaran->nama_wali }}**!

Terima kasih telah mendaftar di AfterSchola. Kami telah menerima data pendaftaran Anda.

Berikut adalah ringkasan pendaftaran Anda:
* **Kelas yang Diambil:** {{ $pendaftaran->nama_kelas }}
* **Jumlah Peserta:** {{ $pendaftaran->jumlah_peserta }}
* **Total Harga:** Rp {{ number_format($pendaftaran->harga, 0, ',', '.') }}
* **Jumlah Bayar (DP):** Rp {{ number_format($pendaftaran->jumlah_bayar, 0, ',', '.') }}

Langkah selanjutnya adalah melakukan pembayaran dan mengunggah bukti transfer.

Silakan klik tombol di bawah ini untuk melanjutkan ke halaman konfirmasi pembayaran.

<x-mail::button :url="route('register.step2.show')">
Lanjut ke Pembayaran
</x-mail::button>

Terima kasih,<br>
Tim afterSchola
</x-mail::message>
