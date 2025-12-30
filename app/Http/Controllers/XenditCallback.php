<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penggajian;
use Illuminate\Support\Facades\Log;

class XenditCallback extends Controller
{
    // public function handle(Request $request)
    // {
    //     // 1. Verifikasi Token Callback (Agar aman dari hacker)
    //     // Cek header 'x-callback-token' sesuai settingan di Dashboard Xendit kamu
    //     $callbackToken = $request->header('x-callback-token');
    //     if ($callbackToken !== env('XENDIT_CALLBACK_TOKEN')) {
    //         return response()->json(['message' => 'Unauthorized'], 401);
    //     }

    //     // 2. Ambil Data
    //     $data = $request->all();
    //     $external_id = $data['external_id'];
    //     $status = $data['status']; // 'COMPLETED' atau 'FAILED'

    //     // 3. Update Database
    //     $gaji = Penggajian::where('xendit_external_id', $external_id)->first();

    //     if ($gaji) {
    //         if ($status == 'COMPLETED') {
    //             $gaji->update([
    //                 'status_transfer' => 'COMPLETED',
    //                 'status_pembayaran' => 'Lunas'
    //             ]);
    //         } elseif ($status == 'FAILED') {
    //             $gaji->update([
    //                 'status_transfer' => 'FAILED',
    //                 'failure_code' => $data['failure_code'] ?? 'UNKNOWN'
    //             ]);
    //         }
    //     }

    //     return response()->json(['message' => 'Success'], 200);
    // }

    // public function handle(Request $request)
    // {
    //     // 1. Cek Token (Wajib)
    //     if ($request->header('x-callback-token') !== env('XENDIT_CALLBACK_TOKEN')) {
    //         return response()->json(['message' => 'Unauthorized'], 401);
    //     }

    //     $data = $request->all();
    //     Log::info('Xendit Payload Masuk:', $data); // Log biar tau isinya apa

    //     // 2. DETEKSI JENIS CALLBACK
    //     // Pakai tanda tanya '??' agar tidak error jika key tidak ada
    //     $external_id = $data['external_id'] ?? null; // Ada di Callback Satuan
    //     $batch_id    = $data['id'] ?? null;          // Ada di Callback Batch
    //     $status      = $data['status'] ?? null;

    //     // --- SKENARIO 1: CALLBACK SATUAN (Per Tutor) ---
    //     if ($external_id) {
    //         $gaji = Penggajian::where('xendit_external_id', $external_id)->first();

    //         if ($gaji) {
    //             if ($status == 'COMPLETED') {
    //                 $gaji->update([
    //                     'status_transfer' => 'COMPLETED',
    //                     'status_pembayaran' => 'LUNAS',
    //                     'tgl_dibayar' => now(), // <--- INI ADALAH BARIS YANG KUTAMBAHKAN
    //                 ]);
    //             } elseif ($status == 'FAILED') {
    //                 $gaji->update([
    //                     'status_transfer' => 'FAILED',
    //                     'failure_code' => $data['failure_code'] ?? 'UNKNOWN'
    //                 ]);
    //             }
    //         }
    //         return response()->json(['message' => 'Transaction Callback processed'], 200);
    //     }

    //     // --- SKENARIO 2: CALLBACK BATCH (Per Kelompok) ---
    //     // Jika external_id tidak ada, tapi ada batch ID, berarti ini laporan Batch
    //     elseif ($batch_id && isset($data['reference'])) { // Cek 'reference' untuk memastikan ini Batch

    //         // Cari semua gaji yang punya batch_id ini
    //         // (Pastikan kamu sudah menyimpan batch_id saat create batch sebelumnya)
    //         $listGaji = Penggajian::where('batch_id', $batch_id)->get();

    //         if ($status == 'COMPLETED' || $status == 'APPROVED') {
    //             // Jika Batch sukses, kita anggap semua di dalamnya sukses (Cara Cepat)
    //             // TAPI: Lebih aman menunggu callback satuan sebenarnya. 
    //             // Untuk skripsi, boleh update semua jadi Lunas biar cepat.
    //             foreach ($listGaji as $gaji) {
    //                 $gaji->update([
    //                     'status_transfer' => 'COMPLETED',
    //                     'status_pembayaran' => 'LUNAS',
    //                     'tgl_dibayar' => now(),
    //                 ]);
    //             }
    //             Log::info("Batch $batch_id selesai. Mengupdate " . $listGaji->count() . " data.");
    //         }

    //         return response()->json(['message' => 'Batch Callback processed'], 200);
    //     }

    //     // --- SKENARIO 3: DATA TIDAK DIKENALI ---
    //     return response()->json(['message' => 'Ignored: No valid ID found'], 200);
    // }


    public function handlePayout(Request $request)
    {
        // 1. Ambil semua data & Log untuk debugging
        $payload = $request->all();
        Log::info("Webhook Xendit Masuk:", $payload);

        // 2. Logika Pengambilan ID & Status (Support V3 & Legacy)
        $externalId = null;
        $status = null;
        $failureCode = null;

        // Cek apakah struktur V3 (ada key 'data')
        if (isset($payload['data']) && is_array($payload['data'])) {
            $externalId = $payload['data']['reference_id'] ?? null;
            $status     = $payload['data']['status'] ?? null;
            $failureCode = $payload['data']['failure_code'] ?? null;
        }
        // Jika tidak, asumsikan struktur Flat (Legacy)
        else {
            $externalId = $payload['reference_id'] ?? $payload['external_id'] ?? null;
            $status     = $payload['status'] ?? null;
            $failureCode = $payload['failure_code'] ?? null;
        }

        // 3. Validasi ID
        if (!$externalId) {
            return response()->json(['message' => 'No ID detected in payload'], 400);
        }

        // 4. Update Database
        // Cari transaksi berdasarkan xendit_external_id
        $gaji = Penggajian::where('xendit_external_id', $externalId)->first();

        if ($gaji) {
            if ($status == 'SUCCEEDED') {
                $gaji->update(['status_pembayaran' => 'LUNAS']);
                Log::info("Gaji ID $externalId BERHASIL");
            } elseif ($status == 'FAILED') {
                $gaji->update([
                    'status_pembayaran' => 'Gagal',
                    'keterangan_error' => $failureCode ?? 'Unknown Error'
                ]);
                Log::info("Gaji ID $externalId GAGAL");
            }
            return response()->json(['message' => 'Update Success']);
        }

        // 5. Handling Khusus Test Dashboard
        // Saat Anda klik "Test" di dashboard, Xendit mengirim ID palsu (misal: "demo_reference_id")
        // ID palsu ini pasti tidak ada di database Anda.
        Log::warning("Data gaji tidak ditemukan untuk ID: $externalId");

        return response()->json(['message' => 'Data not found, but webhook received OK']);
    }
}
