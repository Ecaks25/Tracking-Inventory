<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ttpb;
use App\Models\Bpg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $saldo = $this->calculateSaldo($validated['lot_number'] ?? '', $validated['dari'] ?? '');
        if (($validated['qty_awal'] ?? 0) > $saldo) {
            return response()->json(['message' => 'QTY tidak mencukupi'], 422);
        }

        $ttpb = Ttpb::create($validated);

        $this->storeRoleSpecificRecords($validated);

        return response()->json($ttpb, 201);
    }

    public function destroy(Ttpb $ttpb)
    {
        $ttpb->delete();

        return response()->noContent();
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
