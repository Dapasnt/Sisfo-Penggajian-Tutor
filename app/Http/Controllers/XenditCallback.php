<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penggajian;
use Illuminate\Support\Facades\Log;

class XenditCallback extends Controller
{
    // public function handle(Request $request)
    // {
    //     Log::info('Xendit Disbursement Callback Received:', $request->all());

    //     $data = $request->all();

    //     if (isset($data['external_id'])) {
    //         $penggajian = Penggajian::where('external_id', $data['external_id'])->first();

    //         if ($penggajian) {
    //             $penggajian->status_pembayaran = $data['status'];
    //             $penggajian->save();

    //             Log::info("Penggajian ID {$penggajian->id} updated to status {$data['status']}");
    //         } else {
    //             Log::warning("Penggajian with external_id {$data['external_id']} not found.");
    //         }
    //     } else {
    //         Log::warning('External ID not found in Xendit callback data.');
    //     }

    //     return response()->json(['message' => 'Callback processed'], 200);
    // }

    public function handle(Request $request)
    {
        // 1. Verifikasi Token Callback (Agar aman dari hacker)
        // Cek header 'x-callback-token' sesuai settingan di Dashboard Xendit kamu
        $callbackToken = $request->header('x-callback-token');
        if ($callbackToken !== env('XENDIT_CALLBACK_TOKEN')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // 2. Ambil Data
        $data = $request->all();
        $external_id = $data['external_id'];
        $status = $data['status']; // 'COMPLETED' atau 'FAILED'

        // 3. Update Database
        $gaji = Penggajian::where('xendit_external_id', $external_id)->first();

        if ($gaji) {
            if ($status == 'COMPLETED') {
                $gaji->update([
                    'status_transfer' => 'COMPLETED',
                    'status_pembayaran' => 'Lunas'
                ]);
            } elseif ($status == 'FAILED') {
                $gaji->update([
                    'status_transfer' => 'FAILED',
                    'failure_code' => $data['failure_code'] ?? 'UNKNOWN'
                ]);
            }
        }

        return response()->json(['message' => 'Success'], 200);
    }
}