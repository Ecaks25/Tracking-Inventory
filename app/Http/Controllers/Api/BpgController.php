<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bpg;
use Illuminate\Http\Request;

class BpgController extends Controller
{
    public function index()
    {
        return Bpg::all();
    }

    public function show(Bpg $bpg)
    {
        return $bpg;
    }

    public function store(Request $request)
    {
        $request->merge([
            'qty' => $this->normalizeNumber($request->input('qty')),
            'qty_aktual' => $this->normalizeNumber($request->input('qty_aktual')),
            'qty_loss' => $this->normalizeNumber($request->input('qty_loss')),
        ]);

        $validated = $request->validate([
            'tanggal' => 'nullable|date',
            'no_bpg' => 'nullable|string',
            'lot_number' => 'nullable|string',
            'supplier' => 'nullable|string',
            'nomor_mobil' => 'nullable|string',
            'nama_barang' => 'nullable|string',
            'qty' => 'nullable|numeric',
            'qty_aktual' => 'nullable|numeric',
            'qty_loss' => 'nullable|numeric',
            'coly' => 'nullable|string',
            'diterima' => 'nullable|string',
            'ttpb' => 'nullable|string',
        ]);

        $validated['qty_loss'] = ($validated['qty'] ?? 0) - ($validated['qty_aktual'] ?? 0);

        $bpg = Bpg::create($validated);

        return response()->json($bpg, 201);
    }

    public function destroy(Bpg $bpg)
    {
        $bpg->delete();

        return response()->noContent();
    }

    private function normalizeNumber($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        $value = trim($value);

        if (strpos($value, ',') !== false && strpos($value, '.') !== false) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        } else {
            $value = str_replace(',', '.', $value);
        }

        return (float) $value;
    }
}
