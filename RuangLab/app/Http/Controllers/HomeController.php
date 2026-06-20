<?php

namespace App\Http\Controllers;

use App\Models\MstLaboratorium;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $totalLab = MstLaboratorium::where('status', true)->count();
        $labUnggulan = MstLaboratorium::where('status', true)
            ->withCount('detailReservasi')
            ->latest()
            ->take(3)
            ->get();

        return view('home', compact('totalLab', 'labUnggulan'));
    }
}
