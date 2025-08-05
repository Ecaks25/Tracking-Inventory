<?php

namespace App\Http\Controllers;

use App\Models\Ttpb;
use App\Models\Bpg;
use Illuminate\Http\Request;

class TtpbController extends Controller
{
    public function edit(Ttpb $ttpb)
    {
        return view('ttpb.edit', ['record' => $ttpb]);
    }

    public function update(Request $request, Ttpb $ttpb)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'no_ttpb' => 'required|string',
            'lot_number' => 'required|string',
            'nama_barang' => 'required|string',
            'qty_awal' => 'required|integer',
            'qty_aktual' => 'required|integer',
            'qty_loss' => 'nullable|integer',
            'persen_loss' => 'nullable|numeric',
            'kadar_air' => 'nullable|numeric|prohibited_unless:dari,pencucian',
            'deviasi' => 'nullable|numeric|prohibited_unless:dari,pencucian',
            'coly' => 'nullable|string',
            'spec' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'ke' => 'required|string',
            'dari' => 'required|string',
        ]);

        $ttpb->update($validated);

        return redirect()->route("{$validated['dari']}.ttpb");
    }

    public function destroy(Ttpb $ttpb)
    {
        $role = $ttpb->dari;
        $ttpb->delete();

        return redirect()->route("{$role}.ttpb");
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.tanggal' => 'required|date',
            'items.*.no_ttpb' => 'required|string',
            'items.*.lot_number' => 'required|string',
            'items.*.nama_barang' => 'required|string',
            'items.*.qty_awal' => 'required|integer',
            'items.*.qty_aktual' => 'required|integer',
            'items.*.qty_loss' => 'nullable|integer',
            'items.*.persen_loss' => 'nullable|numeric',
            'items.*.kadar_air' => 'nullable|numeric|prohibited_unless:items.*.dari,pencucian',
            'items.*.deviasi' => 'nullable|numeric|prohibited_unless:items.*.dari,pencucian',
            'items.*.coly' => 'nullable|string',
            'items.*.spec' => 'nullable|string',
            'items.*.keterangan' => 'nullable|string',
            'items.*.ke' => 'required|string',
            'items.*.dari' => 'required|string',
        ]);

        $createdIds = [];

        foreach ($validated['items'] as $item) {
            $saldo = $this->calculateSaldo($item['lot_number'], $item['dari']);

            if ($item['qty_awal'] > $saldo) {
                return back()
                    ->withInput()
                    ->withErrors(['qty_awal' => 'QTY tidak mencukupi']);
            }

            $record = Ttpb::create($item);
            $createdIds[] = $record->id;
            $role = $item['dari'];
        }

        session(['ttpb_preview_ids' => $createdIds]);

        return redirect()->route("{$role}.ttpb.preview");
    }

    private function calculateSaldo(string $lot, string $role): int
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
}
