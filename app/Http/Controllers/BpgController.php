<?php

namespace App\Http\Controllers;

use App\Models\Bpg;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            'tanggal' => 'nullable|date',
            'no_bpg' => 'nullable|string',
            'lot_number' => ['nullable', 'string', Rule::unique('bpgs', 'lot_number')->ignore($bpg->id)],
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
            'tanggal' => 'nullable|date',
            'no_bpg' => 'nullable|string',
            'lot_number' => 'nullable|string|unique:bpgs,lot_number',
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

        Bpg::create($validated);

        return redirect()->route('gudang.stock');
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
