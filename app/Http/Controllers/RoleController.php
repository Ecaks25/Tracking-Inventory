<?php

namespace App\Http\Controllers;

use App\Models\Bpg;
use App\Models\Ttpb;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected array $roles;

    public function __construct()
    {
        $this->roles = config('roles');
    }

    public function stock(Request $request, string $role)
    {
        if ($role === 'gudang') {
            $bpgQuery = Bpg::query();
            $ttpbQuery = Ttpb::where('ke', 'gudang');

            if ($month = $request->input('month')) {
                try {
                    $date = Carbon::parse($month . '-01');
                    $bpgQuery->whereYear('tanggal', $date->year)
                        ->whereMonth('tanggal', $date->month);
                    $ttpbQuery->whereYear('tanggal', $date->year)
                        ->whereMonth('tanggal', $date->month);
                } catch (\Exception $e) {
                    // Ignore invalid month
                }
            }

            if ($lot = $request->input('lot')) {
                $bpgQuery->where('lot_number', 'like', "%$lot%");
                $ttpbQuery->where('lot_number', 'like', "%$lot%");
            }

            $bpgRecords = $bpgQuery->orderBy('lot_number')
                ->orderBy('tanggal')
                ->get();

            $ttpbRecords = $ttpbQuery->orderBy('lot_number')
                ->orderBy('tanggal')
                ->get()
                ->map(function ($item) {
                    $item->no_bpg = $item->no_ttpb;
                    $item->supplier = '-';
                    $item->nomor_mobil = '-';
                    $item->qty = $item->qty_awal;
                    $item->diterima = '-';
                    $item->ttpb = $item->no_ttpb;
                    return $item;
                });

            $records = $bpgRecords->concat($ttpbRecords)
                ->sortBy('lot_number')
                ->sortBy('tanggal')
                ->values();

            return view("{$role}.stock", ['role' => $role, 'records' => $records]);
        }

        $query = Ttpb::where('ke', $role);

        if ($month = $request->input('month')) {
            try {
                $date = Carbon::parse($month . '-01');
                $query->whereYear('tanggal', $date->year)
                    ->whereMonth('tanggal', $date->month);
            } catch (\Exception $e) {
                // Ignore invalid month
            }
        }

        if ($lot = $request->input('lot')) {
            $query->where('lot_number', 'like', "%$lot%");
        }

        $records = $query->orderBy('lot_number')
            ->orderBy('tanggal')
            ->get();

        return view("{$role}.stock", ['role' => $role, 'records' => $records]);
    }

    public function stockCreate(string $role)
    {
        $lotNumbers = Bpg::pluck('lot_number');
        return view("{$role}.stock-create", ['lotNumbers' => $lotNumbers]);
    }

    public function ttpb(Request $request, string $role)
    {
        $query = Ttpb::where('dari', $role);

        if ($month = $request->input('month')) {
            try {
                $date = Carbon::parse($month . '-01');
                $query->whereYear('tanggal', $date->year)
                    ->whereMonth('tanggal', $date->month);
            } catch (\Exception $e) {
                // Ignore invalid month
            }
        }

        if ($lot = $request->input('lot')) {
            $query->where('lot_number', 'like', "%$lot%");
        }

        $records = $query->orderBy('lot_number')
            ->orderBy('tanggal')
            ->get();

        return view('ttpb.index', ['role' => $role, 'records' => $records]);
    }

    public function ttpbCreate(string $role)
    {
        $roles = $this->roles;
        $stocks = $role === 'gudang'
            ? Bpg::all()
            : Ttpb::where('ke', $role)->get();
        return view('ttpb.create', ['role' => $role, 'roles' => $roles, 'stocks' => $stocks]);
    }

    public function ttpbPreview(string $role)
    {
        $ids = session()->get("ttpb_preview_ids_{$role}", []);
        $records = Ttpb::where('dari', $role)
            ->whereIn('id', $ids)
            ->get();
        return view('ttpb.preview', ['role' => $role, 'records' => $records]);
    }

    public function monitoring(Request $request, string $role)
    {
        $selectedLot = $request->input('lot');

        if ($role === 'gudang') {
            $lots = Bpg::pluck('lot_number');
            $records = collect();

            if ($selectedLot) {
                $bpgs = Bpg::where('lot_number', $selectedLot)
                    ->orderBy('tanggal')
                    ->orderBy('id')
                    ->get();

                $ttpbs = Ttpb::where('lot_number', $selectedLot)
                    ->where(function ($q) {
                        $q->where('ke', 'gudang')->orWhere('dari', 'gudang');
                    })
                    ->orderBy('tanggal')
                    ->orderBy('id')
                    ->get();

                $entries = collect();

                foreach ($bpgs as $bpg) {
                    $entries->push([
                        'tanggal' => $bpg->tanggal,
                        'lot_number' => $bpg->lot_number,
                        'supplier' => $bpg->supplier,
                        'nama_barang' => $bpg->nama_barang,
                        'qty_in_bpg' => $bpg->qty,
                        'qty_in_ttpb' => 0,
                        'qty_out_ttpb' => 0,
                        'sort_key' => $bpg->tanggal . '-' . $bpg->id,
                    ]);
                }

                foreach ($ttpbs as $ttpb) {
                    $entries->push([
                        'tanggal' => $ttpb->tanggal,
                        'lot_number' => $ttpb->lot_number,
                        'supplier' => '-',
                        'nama_barang' => $ttpb->nama_barang,
                        'qty_in_bpg' => 0,
                        'qty_in_ttpb' => $ttpb->ke === 'gudang' ? $ttpb->qty_aktual : 0,
                        'qty_out_ttpb' => $ttpb->dari === 'gudang' ? $ttpb->qty_awal : 0,
                        'sort_key' => $ttpb->tanggal . '-' . $ttpb->id,
                    ]);
                }

                $saldo = 0;
                $records = $entries->sortBy('sort_key')->values()->map(function ($row) use (&$saldo) {
                    $saldo += $row['qty_in_bpg'] + $row['qty_in_ttpb'] - $row['qty_out_ttpb'];
                    $row['saldo'] = $saldo;
                    return $row;
                });
            }

            return view('gudang.monitoring', [
                'records' => $records,
                'lots' => $lots,
                'selectedLot' => $selectedLot,
            ]);
        }

        $lots = Ttpb::where('ke', $role)->pluck('lot_number');
        $records = collect();

        if ($selectedLot) {
            $ttpbs = Ttpb::where('lot_number', $selectedLot)
                ->where(function ($q) use ($role) {
                    $q->where('ke', $role)->orWhere('dari', $role);
                })
                ->orderBy('tanggal')
                ->orderBy('id')
                ->get();

            $saldo = 0;
            $records = $ttpbs->map(function ($item) use ($role, &$saldo) {
                $row = [
                    'lot_number' => $item->lot_number,
                    'supplier' => '-',
                    'nama_barang' => $item->nama_barang,
                    'tanggal' => $item->tanggal,
                    'qty_in_ttpb' => $item->ke === $role ? $item->qty_aktual : 0,
                    'qty_out_ttpb' => $item->dari === $role ? $item->qty_awal : 0,
                ];
                $saldo += $row['qty_in_ttpb'] - $row['qty_out_ttpb'];
                $row['saldo'] = $saldo;
                return $row;
            });
        }

        return view("{$role}.monitoring", [
            'role' => $role,
            'records' => $records,
            'lots' => $lots,
            'selectedLot' => $selectedLot,
        ]);
    }

    public function gudangBpg(string $lotNumber)
    {
        return Bpg::where('lot_number', $lotNumber)
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->first(['nama_barang', 'qty', 'qty_aktual', 'coly']);
    }

    public function roleTtpbShow(string $role, string $lotNumber)
    {
        abort_unless(in_array($role, $this->roles), 404);

        return Ttpb::where('ke', $role)
            ->where('lot_number', $lotNumber)
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->first(['nama_barang', 'qty_aktual', 'qty_loss', 'persen_loss', 'coly', 'spec', 'keterangan']);
    }

    public function roleTtpbLast(string $role)
    {
        abort_unless(in_array($role, $this->roles), 404);

        return Ttpb::where('dari', $role)
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->first();
    }
}

