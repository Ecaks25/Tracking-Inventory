<?php

namespace App\Http\Controllers;

use App\Models\Bpg;
use App\Models\Ttpb;

class MonitoringController extends Controller
{
    public function stock()
    {
        $roles = config('roles');
        $data = [];

        foreach ($roles as $role) {
            if ($role === 'gudang') {
                $incoming = Bpg::sum('qty') +
                    Ttpb::where('ke', 'gudang')->sum('qty_aktual');
                $outgoing = Ttpb::where('dari', 'gudang')->sum('qty_awal');
                $data[$role] = $incoming - $outgoing;
            } else {
                $incoming = Ttpb::where('ke', $role)->sum('qty_aktual');
                $outgoing = Ttpb::where('dari', $role)->sum('qty_awal');
                $data[$role] = $incoming - $outgoing;
            }
        }

        return view('monitoring-stock', ['data' => $data]);
    }
}

