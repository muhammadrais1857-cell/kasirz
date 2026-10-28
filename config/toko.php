<?php

return [
    'nama' => env('TOKO_NAMA', 'TokoKita'),
    // Ongkos kirim dibuat flat agar sederhana (batasan masalah di laporan).
    'ongkir' => (int) env('TOKO_ONGKIR', 15000),
    'stok_menipis' => 5,
];
