<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('store ttpb redirects to ttpb index', function () {
    $user = User::factory()->create(['role' => 'gudang']);
    $this->actingAs($user);

    \App\Models\Bpg::factory()->create([
        'lot_number' => 'LOT-1',
        'qty' => 20,
        'nama_barang' => 'Barang',
        'supplier' => 'Supp',
    ]);

    $data = [
        'items' => [[
            'tanggal' => '2024-01-01',
            'no_ttpb' => 'TTPB-001',
            'lot_number' => 'LOT-1',
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
        ]],
    ];

    $response = $this->post('/gudang/ttpb', $data);

    $response->assertRedirect('/gudang/ttpb/preview');
    $this->assertDatabaseHas('ttpbs', ['no_ttpb' => 'TTPB-001']);
});

test('store ttpb saves all rows including added ones', function () {
    $user = User::factory()->create(['role' => 'gudang']);
    $this->actingAs($user);

    \App\Models\Bpg::factory()->create([
        'lot_number' => 'LOT-A',
        'qty' => 20,
        'nama_barang' => 'Barang A',
        'supplier' => 'Supp',
    ]);

    \App\Models\Bpg::factory()->create([
        'lot_number' => 'LOT-B',
        'qty' => 20,
        'nama_barang' => 'Barang B',
        'supplier' => 'Supp',
    ]);

    $payload = [
        'items' => [
            [
                'tanggal' => '2024-01-01',
                'no_ttpb' => 'TTPB-009',
                'lot_number' => 'LOT-A',
                'nama_barang' => 'Barang A',
                'qty_awal' => 10,
                'qty_aktual' => 9,
                'qty_loss' => 1,
                'persen_loss' => 10,
                'coly' => 'A',
                'spec' => 'Spec A',
                'keterangan' => 'Test A',
                'dari' => 'gudang',
                'ke' => 'pencucian',
            ],
            [
                'tanggal' => '2024-01-01',
                'no_ttpb' => 'TTPB-009',
                'lot_number' => 'LOT-B',
                'nama_barang' => 'Barang B',
                'qty_awal' => 5,
                'qty_aktual' => 5,
                'qty_loss' => 0,
                'persen_loss' => 0,
                'coly' => 'B',
                'spec' => 'Spec B',
                'keterangan' => 'Test B',
                'dari' => 'gudang',
                'ke' => 'pencucian',
            ],
        ],
    ];

    $response = $this->post('/gudang/ttpb', $payload);
    $response->assertRedirect('/gudang/ttpb/preview');

    $this->assertDatabaseCount('ttpbs', 2);
    $this->assertDatabaseHas('ttpbs', [
        'lot_number' => 'LOT-A',
        'qty_awal' => 10,
        'qty_aktual' => 9,
    ]);
    $this->assertDatabaseHas('ttpbs', [
        'lot_number' => 'LOT-B',
        'qty_awal' => 5,
        'qty_aktual' => 5,
    ]);
});

test('new record is created when the same lot number is sent again', function () {
    $user = User::factory()->create(['role' => 'gudang']);
    $this->actingAs($user);

    \App\Models\Bpg::factory()->create([
        'lot_number' => 'LOT-1',
        'qty' => 20,
        'nama_barang' => 'Barang',
        'supplier' => 'Supp',
    ]);

    $initial = [
        'tanggal' => '2024-01-01',
        'no_ttpb' => 'TTPB-001',
        'lot_number' => 'LOT-1',
        'nama_barang' => 'Barang',
        'qty_awal' => 10,
        'qty_aktual' => 9,
        'qty_loss' => 1,
        'persen_loss' => 10,
        'dari' => 'gudang',
        'ke' => 'pencucian',
    ];

    \App\Models\Ttpb::create($initial);

    $additional = $initial;
    $additional['no_ttpb'] = 'TTPB-001';
    $additional['qty_awal'] = 5;
    $additional['qty_aktual'] = 5;
    $additional['qty_loss'] = 0;
    $additional['persen_loss'] = 0;

    $this->post('/gudang/ttpb', ['items' => [$additional]])->assertRedirect('/gudang/ttpb/preview');

    $this->assertDatabaseCount('ttpbs', 2);
    $this->assertDatabaseHas('ttpbs', [
        'lot_number' => 'LOT-1',
        'ke' => 'pencucian',
        'qty_awal' => 10,
        'qty_aktual' => 9,
    ]);
    $this->assertDatabaseHas('ttpbs', [
        'lot_number' => 'LOT-1',
        'ke' => 'pencucian',
        'qty_awal' => 5,
        'qty_aktual' => 5,
    ]);
});

test('qty awal cannot exceed latest saldo', function () {
    $user = User::factory()->create(['role' => 'gudang']);
    $this->actingAs($user);

    \App\Models\Bpg::factory()->create([
        'lot_number' => 'LOT-S',
        'nama_barang' => 'Barang',
        'supplier' => 'Supp',
        'qty' => 5,
    ]);

    $payload = [
        'items' => [[
            'tanggal' => '2024-01-01',
            'no_ttpb' => 'TTPB-002',
            'lot_number' => 'LOT-S',
            'nama_barang' => 'Barang',
            'qty_awal' => 10,
            'qty_aktual' => 9,
            'qty_loss' => 1,
            'persen_loss' => 10,
            'dari' => 'gudang',
            'ke' => 'pencucian',
        ]],
    ];

    $this->from('/gudang/ttpb/create')
        ->post('/gudang/ttpb', $payload)
        ->assertSessionHasErrors('qty_awal');

    $this->assertDatabaseCount('ttpbs', 0);
});

test('non pencucian role cannot submit moisture or deviation', function () {
    $user = User::factory()->create(['role' => 'gudang']);
    $this->actingAs($user);

    \App\Models\Bpg::factory()->create([
        'lot_number' => 'LOT-X',
        'qty' => 10,
        'nama_barang' => 'Barang',
        'supplier' => 'Supp',
    ]);

    $payload = [
        'items' => [[
            'tanggal' => '2024-01-01',
            'no_ttpb' => 'TTPB-003',
            'lot_number' => 'LOT-X',
            'nama_barang' => 'Barang',
            'qty_awal' => 5,
            'qty_aktual' => 5,
            'qty_loss' => 0,
            'persen_loss' => 0,
            'kadar_air' => 1,
            'deviasi' => 1,
            'dari' => 'gudang',
            'ke' => 'pencucian',
        ]],
    ];

    $this->from('/gudang/ttpb/create')
        ->post('/gudang/ttpb', $payload)
        ->assertSessionHasErrors(['items.0.kadar_air', 'items.0.deviasi']);
});
