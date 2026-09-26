<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class QrScanController extends Controller
{
    public function __invoke(string $token)
    {
        $record=DB::table('qr_tokens')->join('qr_stations','qr_stations.id','=','qr_tokens.station_id')->where('token_hash',hash('sha256',$token))->whereNull('used_at')->where('expires_at','>',now())->where('qr_stations.status','active')->select('qr_tokens.*','qr_stations.label','qr_stations.audience')->first();
        abort_unless($record,410,'QR sudah kedaluwarsa. Silakan pindai QR terbaru pada layar stasiun.');
        abort_unless($record->audience===request()->user()->role,403,'QR ini tidak sesuai dengan jenis akun Anda.');
        return view('stations.scan',compact('record','token'));
    }
}
