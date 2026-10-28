<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'is_production' => (bool) env('MIDTRANS_IS_PRODUCTION', false),
    // Jika true, halaman bayar menampilkan tombol simulasi (tanpa Midtrans).
    // Default: aktif otomatis kalau MIDTRANS_SERVER_KEY belum diisi.
    'simulasi' => env('MIDTRANS_SIMULASI') !== null
        ? filter_var(env('MIDTRANS_SIMULASI'), FILTER_VALIDATE_BOOLEAN)
        : empty(env('MIDTRANS_SERVER_KEY')),
];
