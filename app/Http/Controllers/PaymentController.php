<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    private function konfigurasiMidtrans(): void
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 404);

        if ($order->status_pesanan !== Order::MENUNGGU) {
            return redirect()->route('pesanan.show', $order);
        }

        $order->load('items.product', 'payment', 'user');
        $simulasi = config('midtrans.simulasi');
        $snapToken = null;

        if (! $simulasi) {
            if (! $order->payment->snap_token) {
                $this->konfigurasiMidtrans();

                $itemDetails = $order->items->map(fn ($i) => [
                    'id' => (string) $i->product_id,
                    'price' => (int) $i->harga_satuan,
                    'quantity' => (int) $i->qty,
                    'name' => mb_substr($i->product->nama_produk, 0, 50),
                ])->all();
                $itemDetails[] = ['id' => 'ONGKIR', 'price' => (int) $order->ongkir, 'quantity' => 1, 'name' => 'Ongkos kirim'];

                $params = [
                    'transaction_details' => ['order_id' => $order->kode_order, 'gross_amount' => (int) $order->total_bayar],
                    'item_details' => $itemDetails,
                    'customer_details' => [
                        'first_name' => $order->user->nama,
                        'email' => $order->user->email,
                        'phone' => $order->user->no_hp,
                    ],
                ];

                try {
                    $token = Snap::getSnapToken($params);
                } catch (\Throwable $e) {
                    report($e);

                    return redirect()->route('pesanan.show', $order)
                        ->with('galat', 'Gagal menghubungi Midtrans: '.$e->getMessage());
                }

                $order->payment->update(['snap_token' => $token]);
            }

            $snapToken = $order->payment->snap_token;
        }

        return view('payment.show', compact('order', 'simulasi', 'snapToken'));
    }

    /** Pembayaran simulasi untuk demo tanpa kunci Midtrans. */
    public function simulasi(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 404);
        abort_unless(config('midtrans.simulasi'), 403, 'Mode simulasi tidak aktif.');

        if ($order->status_pesanan === Order::MENUNGGU) {
            $order->tandaiDibayar('simulasi', 'SIM-'.$order->kode_order);
        }

        return redirect()->route('pesanan.show', $order)->with('sukses', 'Pembayaran simulasi berhasil.');
    }

    /** Webhook dari Midtrans (POST /midtrans/notifikasi). */
    public function notifikasi(Request $request)
    {
        $data = $request->all();
        $serverKey = (string) config('midtrans.server_key');

        $seharusnya = hash('sha512', ($data['order_id'] ?? '').($data['status_code'] ?? '').($data['gross_amount'] ?? '').$serverKey);

        if ($serverKey === '' || ! hash_equals($seharusnya, (string) ($data['signature_key'] ?? ''))) {
            return response()->json(['pesan' => 'Signature tidak valid'], 403);
        }

        $order = Order::where('kode_order', $data['order_id'] ?? '')->first();
        if (! $order) {
            return response()->json(['pesan' => 'Order tidak ditemukan'], 404);
        }

        $status = $data['transaction_status'] ?? '';
        $tipe = $data['payment_type'] ?? null;
        $trx = $data['transaction_id'] ?? null;
        $fraud = $data['fraud_status'] ?? null;

        if ($status === 'settlement' || ($status === 'capture' && $fraud !== 'challenge')) {
            $order->tandaiDibayar($tipe, $trx);
        } elseif ($status === 'pending') {
            $order->payment()->update(['metode_bayar' => $tipe, 'transaction_id' => $trx]);
        } elseif (in_array($status, ['deny', 'cancel', 'expire', 'failure'], true)) {
            $order->batalkan();
        }

        return response()->json(['pesan' => 'ok']);
    }
}
