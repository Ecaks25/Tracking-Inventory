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
        $lotNumbers = Bpg::pluck('lot_number')->merge(Ttpb::pluck('lot_number'))->unique();
        return view('ttpb.edit', ['record' => $ttpb, 'lotNumbers' => $lotNumbers]);
    }

    public function update(Request $request, Ttpb $ttpb)
    {
        $request->merge([
            'qty_awal' => $this->normalizeNumber($request->input('qty_awal')),
            'qty_aktual' => $this->normalizeNumber($request->input('qty_aktual')),
            'qty_loss' => $this->normalizeNumber($request->input('qty_loss')),
        ]);

        $validated = $request->validate([
            'tanggal' => 'nullable|date',
            'no_ttpb' => 'nullable|string',
            'lot_number' => 'nullable|string',
            'nama_barang' => 'nullable|string',
            'qty_awal' => 'nullable|numeric',
            'qty_aktual' => 'nullable|numeric',
            'qty_loss' => 'nullable|numeric',
            'persen_loss' => 'nullable|numeric',
            'kadar_air' => 'nullable|numeric|prohibited_unless:dari,pencucian',
            'deviasi' => 'nullable|numeric|prohibited_unless:dari,pencucian',
            'coly' => 'nullable|string',
            'spec' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'ke' => 'nullable|string',
            'dari' => 'nullable|string',
        ]);

        $role = $validated['dari'] ?? 'gudang';
        $ttpb->update($validated);

        return redirect()->route("{$role}.ttpb");
    }

    public function destroy(Ttpb $ttpb)
    {
        $role = $ttpb->dari;
        $ttpb->delete();

        return redirect()->route("{$role}.ttpb");
    }
    public function store(Request $request)
    {
        // Support forms that submit a single record without wrapping fields in an
        // `items` array. When the request doesn't contain an `items` key we
        // assume all top level fields represent a single entry and wrap them
        // accordingly so the rest of the method can handle them uniformly.
        if (!$request->has('items')) {
            $single = $request->only([
                'tanggal',
                'no_ttpb',
                'lot_number',
                'nama_barang',
                'qty_awal',
                'qty_aktual',
                'qty_loss',
                'persen_loss',
                'kadar_air',
                'deviasi',
                'coly',
                'spec',
                'keterangan',
                'ke',
                'dari',
            ]);
            $request->merge(['items' => [$single]]);
        }

        $items = collect($request->input('items', []))->map(function ($item) {
            $item['qty_awal'] = $this->normalizeNumber($item['qty_awal'] ?? null);
            $item['qty_aktual'] = $this->normalizeNumber($item['qty_aktual'] ?? null);
            $item['qty_loss'] = $this->normalizeNumber($item['qty_loss'] ?? null);
            return $item;
        })->toArray();
        $request->merge(['items' => $items]);

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.tanggal' => 'nullable|date',
            'items.*.no_ttpb' => 'nullable|string',
            'items.*.lot_number' => 'nullable|string',
            'items.*.nama_barang' => 'nullable|string',
            'items.*.qty_awal' => 'nullable|numeric',
            'items.*.qty_aktual' => 'nullable|numeric',
            'items.*.qty_loss' => 'nullable|numeric',
            'items.*.persen_loss' => 'nullable|numeric',
            'items.*.kadar_air' => 'nullable|numeric|prohibited_unless:items.*.dari,pencucian',
            'items.*.deviasi' => 'nullable|numeric|prohibited_unless:items.*.dari,pencucian',
            'items.*.coly' => 'nullable|string',
            'items.*.spec' => 'nullable|string',
            'items.*.keterangan' => 'nullable|string',
            'items.*.ke' => 'nullable|string',
            'items.*.dari' => 'nullable|string',
        ]);

        $createdIds = [];
        $role = $validated['items'][0]['dari'] ?? 'gudang';

        try {
            DB::beginTransaction();
            foreach ($validated['items'] as $item) {
                if (($item['dari'] ?? $role) !== $role) {
                    throw ValidationException::withMessages([
                        'items.*.dari' => 'Semua baris harus memiliki asal yang sama',
                    ]);
                }

                $saldo = $this->calculateSaldo($item['lot_number'] ?? '', $item['dari'] ?? '');

                if (($item['qty_awal'] ?? 0) > $saldo) {
                    throw ValidationException::withMessages([
                        'qty_awal' => 'QTY tidak mencukupi',
                    ]);
                }

                $record = Ttpb::create($item);
                $this->storeRoleSpecificRecords($item);
                $createdIds[] = $record->id;
            }
            DB::commit();
        } catch (ValidationException $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors($e->errors());
        }

        $existingIds = session()->get("ttpb_preview_ids_{$role}", []);
        $allIds = collect($existingIds)->merge($createdIds)->unique()->values()->all();
        session()->put("ttpb_preview_ids_{$role}", $allIds);

        return redirect()->route("{$role}.ttpb.preview");
    }

    private function storeRoleSpecificRecords(array $item): void
    {
        if (isset($item['dari'])) {
            $this->insertIntoRoleTable($item['dari'] . '_ttpbs', $item);
        }
        if (isset($item['ke'])) {
            $this->insertIntoRoleTable($item['ke'] . '_ttpbs', $item);
        }
    }

    private function insertIntoRoleTable(string $table, array $item): void
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable($table)) {
            return;
        }

        $columns = [
            'tanggal',
            'no_ttpb',
            'lot_number',
            'nama_barang',
            'qty_awal',
            'qty_aktual',
            'qty_loss',
            'persen_loss',
            'coly',
            'spec',
            'keterangan',
            'dari',
            'ke',
        ];

        $data = collect($item)->only($columns)->toArray();

        if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'kadar_air')) {
            $data['kadar_air'] = $item['kadar_air'] ?? null;
        }

        if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'deviasi')) {
            $data['deviasi'] = $item['deviasi'] ?? null;
        }

        $data['created_at'] = now();
        $data['updated_at'] = now();

        DB::table($table)->insert($data);
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
