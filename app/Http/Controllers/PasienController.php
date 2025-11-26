<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasienController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('q');

        $pasiens = Pasien::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_pasien', 'like', "%{$search}%")
                      ->orWhere('no_rekam_medis', 'like', "%{$search}%");
                });
            })
            ->orderBy('nama_pasien')
            ->paginate(15)
            ->withQueryString();

        return view('pasien.index', compact('pasiens', 'search'));
    }
}
