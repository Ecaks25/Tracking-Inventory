<?php

use App\Models\User;
use App\Models\Bpg;
use App\Models\Ttpb;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);


test('monitoring stock page is displayed for admin per role', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $this->actingAs($user);

    $this->get('/gudang/monitoring')->assertOk();

});

test('monitoring page shows data for selected lot', function () {
    $user = User::factory()->create(['role' => 'gudang']);
    $this->actingAs($user);

    \App\Models\Bpg::factory()->create(['lot_number' => 'LOT-1', 'nama_barang' => 'Barang', 'qty' => 5]);

    $this->get('/gudang/monitoring?lot=LOT-1')
        ->assertOk()
        ->assertSee('LOT-1');
});

test('gudang monitoring shows sequential entries with running saldo', function () {
    $user = User::factory()->create(['role' => 'gudang']);
    $this->actingAs($user);

    Bpg::factory()->create([
        'lot_number' => 'LOT-2',
        'nama_barang' => 'Barang',
        'supplier' => 'Supp',
        'qty' => 10,
    ]);

    Ttpb::factory()->create([
        'lot_number' => 'LOT-2',
        'nama_barang' => 'Barang',
        'qty_awal' => 5,
        'qty_aktual' => 5,
        'dari' => 'mixing',
        'ke' => 'gudang',
    ]);

    Ttpb::factory()->create([
        'lot_number' => 'LOT-2',
        'nama_barang' => 'Barang',
        'qty_awal' => 3,
        'qty_aktual' => 3,
        'dari' => 'gudang',
        'ke' => 'mixing',
    ]);

    $this->get('/gudang/monitoring?lot=LOT-2')
        ->assertOk()
        ->assertViewHas('records', function ($records) {
            return $records->count() === 3
                && $records[0]['qty_in_bpg'] == 10
                && $records[0]['saldo'] == 10
                && $records[1]['qty_in_ttpb'] == 5
                && $records[1]['saldo'] == 15
                && $records[2]['qty_out_ttpb'] == 3
                && $records[2]['saldo'] == 12;
        });
});
