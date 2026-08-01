<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\Desa;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Acara::with('desa');
        $desaQuery = Desa::query();

        if ($user && $user->isBendahara() && $user->desa_id) {
            $query->where('desa_id', $user->desa_id);
            $desaQuery->where('id', $user->desa_id);
        }

        $acaras = $query->orderBy('tanggal_mulai')->get();
        $desas = $desaQuery->get();

        return view('jadwal.index', compact('acaras', 'desas'));
    }
}
