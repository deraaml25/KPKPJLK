<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\Pilkades;
use App\Models\PilkadesSuara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PilkadesController extends Controller
{
    public function index()
    {
        $desaId = Auth::user()->desa_id;
        $pilkadesObj = Pilkades::with('suaras')
            ->where('desa_id', $desaId)
            ->latest()
            ->first(); // Get the active Pilkades tracking

        return view('desa.pilkades.index', compact('pilkadesObj'));
    }

    public function show(Pilkades $pilkades)
    {
        if ($pilkades->desa_id !== Auth::user()->desa_id) {
            abort(403);
        }
        $pilkades->load('suaras');
        return view('desa.pilkades.show', compact('pilkades'));
    }

    public function storeSuara(Request $request, Pilkades $pilkades)
    {
        if ($pilkades->desa_id !== Auth::user()->desa_id) {
            abort(403);
        }

        $request->validate([
            'tps_name' => 'required',
            'suara_calon_1' => 'required|integer|min:0',
            'suara_calon_2' => 'required|integer|min:0',
            'suara_calon_3' => 'required|integer|min:0',
        ]);

        PilkadesSuara::create([
            'pilkades_id' => $pilkades->id,
            'tps_name' => $request->tps_name,
            'suara_calon_1' => $request->suara_calon_1,
            'suara_calon_2' => $request->suara_calon_2,
            'suara_calon_3' => $request->suara_calon_3,
        ]);

        return redirect()->route('desa.pilkades.index')->with('success', 'Data perolehan suara TPS berhasil dimasukkan secara real-time.');
    }
}
