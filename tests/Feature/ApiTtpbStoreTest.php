<?php

use App\Models\Bpg;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('api store ttpb saves records into role specific tables', function () {
    Bpg::factory()->create([
        'lot_number' => 'LOT-API',
        'qty' => 20,
        'nama_barang' => 'Barang',
        'supplier' => 'Supp',
    ]);

    $payload = [
        'tanggal' => '2024-01-01',
        'no_ttpb' => 'TTPB-123',
        'lot_number' => 'LOT-API',
        'nama_barang' => 'Barang',
        'qty_awal' => 10,
        'qty_aktual' => 9,
        'qty_loss' => 1,
        'persen_loss' => 10,
        'coly' => 'A',
        'spec' => 'Spec',
        'keterangan' => 'Test',
        'dari' => 'gudang',
        'ke' => 'pencucian',
    ];

    $this->postJson('/api/ttpb', $payload)->assertCreated();

    $this->assertDatabaseHas('ttpbs', ['no_ttpb' => 'TTPB-123']);
    $this->assertDatabaseHas('gudang_ttpbs', ['no_ttpb' => 'TTPB-123']);
    $this->assertDatabaseHas('pencucian_ttpbs', ['no_ttpb' => 'TTPB-123']);
});
