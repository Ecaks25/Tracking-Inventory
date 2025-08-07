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
            'tanggal' => 'required|date',
            'no_bpg' => 'required|string',
            'lot_number' => 'required|string',
            'supplier' => 'required|string',
            'nomor_mobil' => 'required|string',
            'nama_barang' => 'required|string',
            'qty' => 'required|numeric',
            'qty_aktual' => 'required|numeric',
            'qty_loss' => 'nullable|numeric',
            'coly' => 'nullable|string',
            'diterima' => 'required|string',
            'ttpb' => 'required|string',
        ]);

        $validated['qty_loss'] = $validated['qty'] - $validated['qty_aktual'];

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
        if ($value === null) {
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
