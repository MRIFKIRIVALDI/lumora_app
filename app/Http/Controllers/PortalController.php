<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PortalController extends Controller
{
    public function explore(Request $request)
    {
        $news = DB::table('announcements')
            ->where(fn ($query) => $query->whereNull('target_role')->orWhere('target_role', $request->user()->role))
            ->latest('published_at')
            ->get();

        return view('portal.explore', compact('news'));
    }

    public function chat()
    {
        return view('portal.chat');
    }

    public function account()
    {
        return view('portal.account');
    }
}
