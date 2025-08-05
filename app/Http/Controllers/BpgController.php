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
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'no_bpg' => 'required|string',
            'lot_number' => 'required|string',
            'supplier' => 'required|string',
            'nama_barang' => 'required|string',
            'qty' => 'required|integer',
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
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'no_bpg' => 'required|string',
            'lot_number' => 'required|string',
            'supplier' => 'required|string',
            'nama_barang' => 'required|string',
            'qty' => 'required|integer',
            'coly' => 'nullable|string',
            'diterima' => 'required|string',
            'ttpb' => 'required|string',
        ]);

        Bpg::create($validated);

        return redirect()->route('gudang.stock');
    }
}
