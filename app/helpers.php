<?php

if (! function_exists('rupiah')) {
    function rupiah($angka): string
    {
        return 'Rp '.number_format((float) $angka, 0, ',', '.');
    }
}

if (! function_exists('kelas_status')) {
    function kelas_status(string $status): string
    {
        return match ($status) {
            'Menunggu Pembayaran', 'Menunggu' => 'b-kuning',
            'Dibayar', 'Berhasil', 'Selesai' => 'b-hijau',
            'Diproses' => 'b-oranye',
            'Dikirim' => 'b-biru',
            'Dibatalkan', 'Gagal' => 'b-merah',
            default => 'b-abu',
        };
    }
}
