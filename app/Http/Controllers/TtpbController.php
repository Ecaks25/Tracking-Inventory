<?php

namespace App\Http\Controllers;

use App\Models\Ttpb;
use App\Models\Bpg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class TtpbController extends Controller
{
    /**
     * Show the form for editing the specified TTPB record.
     *
     * @param Ttpb $ttpb
     * @return \Illuminate\View\View
     */
    public function edit(Ttpb $ttpb)
    {
        $lotNumbers = Bpg::pluck('lot_number')->merge(Ttpb::pluck('lot_number'))->unique();
        return view('ttpb.edit', [
            'record' => $ttpb,
            'lotNumbers' => $lotNumbers
        ]);
    }

    /**
     * Update the specified TTPB record.
     *
     * @param \Illuminate\Http\Request $request
     * @param Ttpb $ttpb
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Ttpb $ttpb)
    {
        // Normalize input values
        $request->merge([
            'qty_awal' => $this->normalizeNumber($request->input('qty_awal')),
            'qty_aktual' => $this->normalizeNumber($request->input('qty_aktual')),
            'qty_loss' => $this->normalizeNumber($request->input('qty_loss')),
        ]);

        // Validate the input
        $validated = $request->validate([
            'tanggal' => 'nullable|date',
            'no_ttpb' => 'nullable|string',
            'lot_number' => ['nullable', 'string', Rule::unique('ttpbs', 'lot_number')->ignore($ttpb->id)],
            'nama_barang' => 'nullable|string',
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

        // Determine the role from validated data
        $role = $validated['dari'];

        // Update the TTPB record
        $ttpb->update($validated);

        return redirect()->route("{$role}.ttpb");
    }

    /**
     * Remove the specified TTPB record from storage.
     *
     * @param Ttpb $ttpb
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Ttpb $ttpb)
    {
        $role = $ttpb->dari;
        $ttpb->delete();

        return redirect()->route("{$role}.ttpb");
    }

    /**
     * Store a newly created TTPB record(s).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Handle the case where a single record is submitted
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

        // Normalize item values
        $items = collect($request->input('items', []))->map(function ($item) {
            $item['qty_awal'] = $this->normalizeNumber($item['qty_awal'] ?? null);
            $item['qty_aktual'] = $this->normalizeNumber($item['qty_aktual'] ?? null);
            $item['qty_loss'] = $this->normalizeNumber($item['qty_loss'] ?? null);
            return $item;
        })->toArray();

        // Merge normalized items into the request
        $request->merge(['items' => $items]);

        // Validate the input
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.tanggal' => 'nullable|date',
            'items.*.no_ttpb' => 'nullable|string',
            'items.*.lot_number' => 'nullable|string|unique:ttpbs,lot_number',
            'items.*.nama_barang' => 'nullable|string',
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
        $role = $validated['items'][0]['dari'];

        try {
            DB::beginTransaction();

            // Process each item and store it
            foreach ($validated['items'] as $item) {
                if ($item['dari'] !== $role) {
                    throw ValidationException::withMessages([
                        'items.*.dari' => 'Semua baris harus memiliki asal yang sama',
                    ]);
                }

                // Check if the quantity is sufficient
                $saldo = $this->calculateSaldo($item['lot_number'] ?? '', $item['dari'] ?? '');
                if ($item['qty_awal'] > $saldo) {
                    throw ValidationException::withMessages([
                        'qty_awal' => 'QTY tidak mencukupi',
                    ]);
                }

                // Create the TTPB record
                $record = Ttpb::create($item);

                // Store role-specific records
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

        // Merge newly created IDs with existing preview IDs in session
        $existingIds = session()->get("ttpb_preview_ids_{$role}", []);
        $allIds = collect($existingIds)->merge($createdIds)->unique()->values()->all();
        session()->put("ttpb_preview_ids_{$role}", $allIds);

        return redirect()->route("{$role}.ttpb.preview");
    }

    /**
     * Store role-specific records in the database.
     *
     * @param array $item
     * @return void
     */
    /**
     * Persist copies of a TTPB record into role-scoped tables.
     *
     * The main `ttpbs` table already stores the canonical record. To make
     * data immediately available on each role's own TTPB and stock pages we
     * duplicate the entry into `<role>_ttpbs` tables for both the source
     * ("dari") and destination ("ke") roles when those tables exist.
     */
    private function storeRoleSpecificRecords(array $item): void
    {
        foreach (['dari', 'ke'] as $key) {
            if (!empty($item[$key])) {
                $this->insertIntoRoleTable($item[$key] . '_ttpbs', $item);
            }
        }
    }

    /**
     * Insert the item into a role-specific table if the table exists.
     */
    private function insertIntoRoleTable(string $table, array $item): void
    {
        // Skip when the expected table is missing – this allows the code to
        // run even if a particular role does not maintain its own TTPB table.
        if (!\Illuminate\Support\Facades\Schema::hasTable($table)) {
            return;
        }

        // List of columns shared by all role tables
        $columns = [
            'tanggal', 'no_ttpb', 'lot_number', 'nama_barang',
            'qty_awal', 'qty_aktual', 'qty_loss', 'persen_loss',
            'coly', 'spec', 'keterangan', 'dari', 'ke',
        ];

        // Gather available data
        $data = collect($item)->only($columns)->toArray();

        // Optional columns for certain roles
        if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'kadar_air')) {
            $data['kadar_air'] = $item['kadar_air'] ?? null;
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'deviasi')) {
            $data['deviasi'] = $item['deviasi'] ?? null;
        }

        // Manually set timestamps since we're bypassing Eloquent
        $data['created_at'] = now();
        $data['updated_at'] = now();

        DB::table($table)->insert($data);
    }

    /**
     * Calculate the available saldo (balance) for a specific lot.
     *
     * @param string $lot
     * @param string $role
     * @return float
     */
    private function calculateSaldo(string $lot, string $role): float
    {
        if ($role === 'gudang') {
            // Use the actual quantity recorded in BPG entries so saldo reflects
            // real stock instead of the planned quantity.
            $incoming = Bpg::where('lot_number', $lot)->sum('qty_aktual')
                + Ttpb::where('ke', 'gudang')->where('lot_number', $lot)->sum('qty_aktual');
            $outgoing = Ttpb::where('dari', 'gudang')->where('lot_number', $lot)->sum('qty_awal');
            return $incoming - $outgoing;
        }

        $incoming = Ttpb::where('ke', $role)->where('lot_number', $lot)->sum('qty_aktual');
        $outgoing = Ttpb::where('dari', $role)->where('lot_number', $lot)->sum('qty_awal');

        return $incoming - $outgoing;
    }

    /**
     * Normalize a numeric value, handling different formats.
     *
     * @param mixed $value
     * @return float|null
     */
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
