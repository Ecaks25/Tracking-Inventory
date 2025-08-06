<?php

namespace App\Http\Controllers;

use App\Models\Bpg;
use Illuminate\Http\Request;

class BpgController extends Controller
{
    public function edit(Bpg $bpg)
    {
        $lotNumbers = Bpg::pluck('lot_number');
        return view('bpg.edit', ['record' => $bpg, 'lotNumbers' => $lotNumbers]);
    }

    public function update(Request $request, Bpg $bpg)
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

        $bpg->update($validated);

        return redirect()->route('gudang.stock');
    }

    public function destroy(Bpg $bpg)
    {
        $bpg->delete();

        return redirect()->route('gudang.stock');
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

        Bpg::create($validated);

        return redirect()->route('gudang.stock');
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
