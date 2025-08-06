<?php

namespace App\Http\Controllers;

use App\Models\Bpg;
use Illuminate\Http\Request;

class BpgController extends Controller
{
    public function edit(Bpg $bpg)
    {
        return view('bpg.edit', ['record' => $bpg]);
    }

    public function update(Request $request, Bpg $bpg)
    {
        $request->merge([
            'qty' => $this->normalizeNumber($request->input('qty')),
        ]);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'no_bpg' => 'required|string',
            'lot_number' => 'required|string',
            'supplier' => 'required|string',
            'nama_barang' => 'required|string',
            'qty' => 'required|numeric',
            'coly' => 'nullable|string',
            'diterima' => 'required|string',
            'ttpb' => 'required|string',
        ]);

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
        ]);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'no_bpg' => 'required|string',
            'lot_number' => 'required|string',
            'supplier' => 'required|string',
            'nama_barang' => 'required|string',
            'qty' => 'required|numeric',
            'coly' => 'nullable|string',
            'diterima' => 'required|string',
            'ttpb' => 'required|string',
        ]);

        Bpg::create($validated);

        return redirect()->route('gudang.stock');
    }

    private function normalizeNumber($value)
    {
        if ($value === null) {
            return $value;
        }
        return str_replace(',', '.', str_replace('.', '', $value));
    }
}
