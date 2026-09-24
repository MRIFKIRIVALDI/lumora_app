<?php

namespace App\Http\Controllers;

use App\Events\QrTokenRotated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QrStationController extends Controller
{
    public function store(Request $request)
    {
        $data=$request->validate(['label'=>'required|string|max:80']);
        $id=DB::table('qr_stations')->insertGetId(['label'=>$data['label'],'opened_by'=>$request->user()->id,'opened_at'=>now(),'status'=>'active','created_at'=>now(),'updated_at'=>now()]);
        return redirect()->route('stations.show',$id)->with('status','Stasiun QR berhasil dibuka.');
    }
    public function show(int $station)
    {
        $station=DB::table('qr_stations')->where('id',$station)->first(); abort_unless($station,404);
        return view('stations.show',compact('station'));
    }
    public function token(int $station)
    {
        $stationRow=DB::table('qr_stations')->where('id',$station)->where('status','active')->first(); abort_unless($stationRow,410,'Stasiun sudah ditutup.');
        $plain=Str::random(48); $expires=now()->addSeconds(8);
        DB::table('qr_tokens')->where('station_id',$station)->where('expires_at','<',now()->subMinute())->delete();
        DB::table('qr_tokens')->insert(['station_id'=>$station,'token_hash'=>hash('sha256',$plain),'expires_at'=>$expires,'created_at'=>now(),'updated_at'=>now()]);
        $url=route('attendance.scan',$plain);
        broadcast(new QrTokenRotated($station,$url,$expires->toIso8601String()));
        return response()->json(['scan_url'=>$url,'expires_at'=>$expires->toIso8601String()])->header('Cache-Control','no-store');
    }
    public function close(int $station)
    {
        DB::table('qr_stations')->where('id',$station)->update(['status'=>'inactive','closed_at'=>now(),'updated_at'=>now()]);
        return redirect()->route('attendance')->with('status','Stasiun QR berhasil ditutup.');
    }
}
