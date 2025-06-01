<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commission;

class CardController extends Controller
{
    public function welcome()
    {
        // Ambil semua commission beserta user yang membuatnya
        $commissions = Commission::with('user')->get();
        
        // Kirim data commission ke view
        return view('welcome', compact('commissions'));
    }

}