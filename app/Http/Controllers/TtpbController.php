<?php

namespace App\Http\Controllers;

use App\Models\Ttpb;
use App\Models\Bpg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TtpbController extends Controller
{
    public function edit(Ttpb $ttpb)
    {
        return view('ttpb.edit', ['record' => $ttpb]);
    }

    public function update(Request $request, Ttpb $ttpb)
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
        $items = collect($request->input('items', []))->map(function ($item) {
            $item['qty_awal'] = $this->normalizeNumber($item['qty_awal'] ?? null);
            $item['qty_aktual'] = $this->normalizeNumber($item['qty_aktual'] ?? null);
            $item['qty_loss'] = $this->normalizeNumber($item['qty_loss'] ?? null);
            return $item;
        })->toArray();
        $request->merge(['items' => $items]);

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.tanggal' => 'required|date',
            'items.*.no_ttpb' => 'required|string',
            'items.*.lot_number' => 'required|string',
            'items.*.nama_barang' => 'required|string',
            'items.*.qty_awal' => 'required|numeric',
            'items.*.qty_aktual' => 'required|numeric',
            'items.*.qty_loss' => 'nullable|numeric',
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
        try {
            DB::beginTransaction();
            foreach ($validated['items'] as $item) {
                $saldo = $this->calculateSaldo($item['lot_number'], $item['dari']);

                if ($item['qty_awal'] > $saldo) {
                    throw ValidationException::withMessages([
                        'qty_awal' => 'QTY tidak mencukupi',
                    ]);
                }

                $record = Ttpb::create($item);
                $createdIds[] = $record->id;
                $role = $item['dari'];
            }
            DB::commit();
        } catch (ValidationException $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors($e->errors());
        }

        session(["ttpb_preview_ids_{$role}" => $createdIds]);

        return redirect()->route("{$role}.ttpb.preview");
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
            return $value;
        }
        return str_replace(',', '.', str_replace('.', '', $value));
    }
}
