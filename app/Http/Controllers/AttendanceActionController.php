<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceActionController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'action' => ['required', 'in:check_in,check_out'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'qr_token' => ['nullable', 'string', 'size:48'],
        ]);

        $user = $request->user();
        $result = DB::transaction(function () use ($user, $data) {
            $qrToken = null;
            if (!empty($data['qr_token'])) {
                $qrToken=DB::table('qr_tokens')->where('token_hash',hash('sha256',$data['qr_token']))->whereNull('used_at')->where('expires_at','>',now())->lockForUpdate()->first();
                if (!$qrToken) return ['error'=>'Token QR tidak valid atau sudah kedaluwarsa. Pindai QR terbaru.'];
            }
            $attendance = DB::table('attendances')->where('student_id', $user->id)->whereDate('date', today())->lockForUpdate()->first();
            $now = now()->format('H:i:s');

            if ($data['action'] === 'check_in') {
                if ($attendance?->check_in) return ['error' => 'Presensi masuk hari ini sudah tercatat.'];
                $status = now()->format('H:i') > '07:00' ? 'terlambat' : 'hadir';
                DB::table('attendances')->insert([
                    'student_id'=>$user->id, 'date'=>today(), 'check_in'=>$now, 'check_out'=>null,
                    'status'=>$status, 'latitude'=>$data['latitude'] ?? null, 'longitude'=>$data['longitude'] ?? null,
                    'station_id'=>$qrToken?->station_id ?? DB::table('qr_stations')->where('status','active')->value('id'), 'qr_token_id'=>$qrToken?->id, 'created_at'=>now(), 'updated_at'=>now(),
                ]);
                if($qrToken) DB::table('qr_tokens')->where('id',$qrToken->id)->update(['used_at'=>now(),'updated_at'=>now()]);
                return ['success' => 'Presensi masuk berhasil dicatat pukul '.now()->format('H:i').' WIB.'];
            }

            if (! $attendance?->check_in) return ['error' => 'Silakan lakukan presensi masuk terlebih dahulu.'];
            if ($attendance->check_out) return ['error' => 'Presensi pulang hari ini sudah tercatat.'];
            DB::table('attendances')->where('id',$attendance->id)->update(['check_out'=>$now,'latitude'=>$data['latitude'] ?? $attendance->latitude,'longitude'=>$data['longitude'] ?? $attendance->longitude,'updated_at'=>now()]);
            return ['success' => 'Presensi pulang berhasil dicatat pukul '.now()->format('H:i').' WIB.'];
        });

        if (isset($result['error'])) return back()->with('error', $result['error']);
        return back()->with('status', $result['success']);
    }
}
