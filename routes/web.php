<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\TtpbController;
use App\Http\Controllers\BpgController;
use App\Models\Bpg;
use Carbon\Carbon;

Route::get('/', function () {
  return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
  ->middleware(['auth', 'verified'])
  ->name('dashboard');

Route::middleware(['auth'])->group(function () {
  Route::redirect('settings', 'settings/profile');

  Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
  Volt::route('settings/password', 'settings.password')->name('settings.password');

  Route::resource('bpg', BpgController::class)->only(['edit', 'update', 'destroy']);
  Route::resource('ttpb', TtpbController::class)->only(['edit', 'update', 'destroy']);

  $roles = ['gudang', 'pencucian', 'pengeringan', 'blower', 'mixing', 'grinding', 'packaging', 'finish_good'];

  foreach ($roles as $role) {
    Route::get("{$role}/stock", function () use ($role) {
      if ($role === 'gudang') {
        $bpgQuery = App\Models\Bpg::query();
        $ttpbQuery = App\Models\Ttpb::where('ke', 'gudang');

        if ($month = request('month')) {
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

        if ($lot = request('lot')) {
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

      $query = App\Models\Ttpb::where('ke', $role);

      if ($month = request('month')) {
        try {
          $date = Carbon::parse($month . '-01');
          $query->whereYear('tanggal', $date->year)
            ->whereMonth('tanggal', $date->month);
        } catch (\Exception $e) {
          // Ignore invalid month
        }
      }

      if ($lot = request('lot')) {
        $query->where('lot_number', 'like', "%$lot%");
      }

      $records = $query->orderBy('lot_number')
        ->orderBy('tanggal')
        ->get();

      return view("{$role}.stock", ['role' => $role, 'records' => $records]);
    })->name("{$role}.stock");
    Route::get("{$role}/stock/create", function () use ($role) {
      $lotNumbers = Bpg::pluck('lot_number');
      return view("{$role}.stock-create", ['lotNumbers' => $lotNumbers]);
    })->name("{$role}.stock.create");
    if ($role === 'gudang') {
      Route::post("{$role}/stock", [BpgController::class, 'store'])->name("{$role}.stock.store");
    }

    Route::get("{$role}/ttpb", function () use ($role) {
      $query = App\Models\Ttpb::where('dari', $role);

      if ($month = request('month')) {
        try {
          $date = Carbon::parse($month . '-01');
          $query->whereYear('tanggal', $date->year)
            ->whereMonth('tanggal', $date->month);
        } catch (\Exception $e) {
          // Ignore invalid month
        }
      }

      if ($lot = request('lot')) {
        $query->where('lot_number', 'like', "%$lot%");
      }

      $records = $query->orderBy('lot_number')
        ->orderBy('tanggal')
        ->get();

      return view('ttpb.index', ['role' => $role, 'records' => $records]);
    })->name("{$role}.ttpb");

    Route::get("{$role}/ttpb/create", function () use ($role, $roles) {
      $stocks = $role === 'gudang'
        ? App\Models\Bpg::all()
        : App\Models\Ttpb::where('ke', $role)->get();
      return view('ttpb.create', ['role' => $role, 'roles' => $roles, 'stocks' => $stocks]);
    })->name("{$role}.ttpb.create");

    Route::post("{$role}/ttpb", [TtpbController::class, 'store'])->name("{$role}.ttpb.store");

    Route::get("{$role}/ttpb/preview", function () use ($role) {
      $ids = session()->get("ttpb_preview_ids_{$role}", []);
      $records = App\Models\Ttpb::where('dari', $role)
        ->whereIn('id', $ids)
        ->get();
      return view('ttpb.preview', ['role' => $role, 'records' => $records]);
    })->name("{$role}.ttpb.preview");

    Route::get("{$role}/monitoring", function () use ($role) {
      $selectedLot = request('lot');

      if ($role === 'gudang') {
        $lots = App\Models\Bpg::pluck('lot_number');
        $records = collect();

        if ($selectedLot) {
          $bpgs = App\Models\Bpg::where('lot_number', $selectedLot)
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();

          $ttpbs = App\Models\Ttpb::where('lot_number', $selectedLot)
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

      $lots = App\Models\Ttpb::where('ke', $role)->pluck('lot_number');
      $records = collect();

      if ($selectedLot) {
        $ttpbs = App\Models\Ttpb::where('lot_number', $selectedLot)
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
    })->name("{$role}.monitoring");
  }

  Route::view('mixing/barang-jadi', 'mixing.barang-jadi', ['role' => 'mixing'])
    ->name('mixing.barang_jadi');
  Route::view('grinding/barang-jadi', 'grinding.barang-jadi', ['role' => 'grinding'])
    ->name('grinding.barang_jadi');

  Route::get('monitoring/stock', function () use ($roles) {
    $data = [];
    foreach ($roles as $role) {
      if ($role === 'gudang') {
        $incoming = App\Models\Bpg::sum('qty') +
          App\Models\Ttpb::where('ke', 'gudang')->sum('qty_aktual');
        $outgoing = App\Models\Ttpb::where('dari', 'gudang')->sum('qty_awal');
        $data[$role] = $incoming - $outgoing;
      } else {
        $incoming = App\Models\Ttpb::where('ke', $role)->sum('qty_aktual');
        $outgoing = App\Models\Ttpb::where('dari', $role)->sum('qty_awal');
        $data[$role] = $incoming - $outgoing;
      }
    }

    return view('monitoring-stock', ['data' => $data]);
  })->name('monitoring.stock');



  // API endpoint to fetch BPG data for Gudang based on lot number
  Route::get('gudang/api/bpg/{lotNumber}', function (string $lotNumber) {
    return App\Models\Bpg::where('lot_number', $lotNumber)
      ->first(['nama_barang', 'qty']);
  })->name('gudang.bpg.show');

  // API endpoint to fetch TTPB data based on lot number per role
  Route::get('{role}/api/ttpb/{lotNumber}', function (string $role, string $lotNumber) use ($roles) {
    abort_unless(in_array($role, $roles), 404);

    return App\Models\Ttpb::where('ke', $role)
      ->where('lot_number', $lotNumber)
      ->first(['nama_barang', 'qty_aktual']);
  })->whereIn('role', $roles)->name('role.ttpb.show');
});

require __DIR__ . '/auth.php';
