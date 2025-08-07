<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ttpb;
use App\Models\Bpg;
use Illuminate\Http\Request;

class TtpbController extends Controller
{
    public function index()
    {
        return Ttpb::all();
    }

    public function show(Ttpb $ttpb)
    {
        return $ttpb;
    }

    public function store(Request $request)
    {
        $request->merge([
            'qty_awal' => $this->normalizeNumber($request->input('qty_awal')),
            'qty_aktual' => $this->normalizeNumber($request->input('qty_aktual')),
            'qty_loss' => $this->normalizeNumber($request->input('qty_loss')),
        ]);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'no_ttpb' => 'required|string',
            'lot_number' => 'required|string',
            'nama_barang' => 'required|string',
            'qty_awal' => 'required|numeric',
            'qty_aktual' => 'required|numeric',
            'qty_loss' => 'nullable|numeric',
            'persen_loss' => 'nullable|numeric',
            'kadar_air' => 'nullable|numeric|prohibited_unless:dari,pencucian',
            'deviasi' => 'nullable|numeric|prohibited_unless:dari,pencucian',
            'coly' => 'nullable|string',
            'spec' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'ke' => 'required|string',
            'dari' => 'required|string',
        ]);

        $saldo = $this->calculateSaldo($validated['lot_number'], $validated['dari']);
        if ($validated['qty_awal'] > $saldo) {
            return response()->json(['message' => 'QTY tidak mencukupi'], 422);
        }

        $ttpb = Ttpb::create($validated);

        return response()->json($ttpb, 201);
    }

    public function destroy(Ttpb $ttpb)
    {
        $ttpb->delete();

        return response()->noContent();
    }

    private function calculateSaldo(string $lot, string $role): float
    {
        if ($role === 'gudang') {
            $incoming = Bpg::where('lot_number', $lot)->sum('qty')
                + Ttpb::where('ke', 'gudang')->where('lot_number', $lot)->sum('qty_aktual');
            $outgoing = Ttpb::where('dari', 'gudang')->where('lot_number', $lot)->sum('qty_awal');

            return $incoming - $outgoing;
        }

        $incoming = Ttpb::where('ke', $role)->where('lot_number', $lot)->sum('qty_aktual');
        $outgoing = Ttpb::where('dari', $role)->where('lot_number', $lot)->sum('qty_awal');

        return $incoming - $outgoing;
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
