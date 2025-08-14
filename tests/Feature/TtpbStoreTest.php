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

test('store ttpb requires origin and destination roles', function () {
    $user = User::factory()->create(['role' => 'gudang']);
    $this->actingAs($user);

    \App\Models\Bpg::factory()->create([
        'lot_number' => 'LOT-X',
        'qty' => 10,
        'nama_barang' => 'Barang',
        'supplier' => 'Supp',
    ]);

    $payload = [
        'tanggal' => '2024-01-01',
        'no_ttpb' => 'TTPB-404',
        'lot_number' => 'LOT-X',
        'nama_barang' => 'Barang',
        'qty_awal' => 5,
        'qty_aktual' => 5,
        'qty_loss' => 0,
        'persen_loss' => 0,
        // intentionally omit dari and ke
    ];

    $this->post('/gudang/ttpb', $payload)
        ->assertSessionHasErrors(['items.0.dari', 'items.0.ke']);

    $this->assertDatabaseCount('ttpbs', 0);
});

test('store ttpb accepts qty values with comma', function () {
    $user = User::factory()->create(['role' => 'gudang']);
    $this->actingAs($user);

    \App\Models\Bpg::factory()->create([
        'lot_number' => 'LOT-C',
        'qty' => 5000,
        'nama_barang' => 'Barang',
        'supplier' => 'Supp',
    ]);

    $data = [
        'items' => [[
            'tanggal' => '2024-01-01',
            'no_ttpb' => 'TTPB-010',
            'lot_number' => 'LOT-C',
            'nama_barang' => 'Barang',
            'qty_awal' => '4.000,9',
            'qty_aktual' => '3.500,5',
            'qty_loss' => '500,4',
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
    $this->assertDatabaseHas('ttpbs', [
        'no_ttpb' => 'TTPB-010',
        'qty_awal' => 4000.9,
        'qty_aktual' => 3500.5,
    ]);
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

test('store ttpb handles single item input without array', function () {
    $user = User::factory()->create(['role' => 'gudang']);
    $this->actingAs($user);

    \App\Models\Bpg::factory()->create([
        'lot_number' => 'LOT-S',
        'qty' => 30,
        'nama_barang' => 'Barang',
        'supplier' => 'Supp',
    ]);

    $payload = [
        'tanggal' => '2024-02-01',
        'no_ttpb' => 'TTPB-020',
        'lot_number' => 'LOT-S',
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

    $this->post('/gudang/ttpb', $payload)->assertRedirect('/gudang/ttpb/preview');

    $this->assertDatabaseHas('ttpbs', [
        'no_ttpb' => 'TTPB-020',
        'lot_number' => 'LOT-S',
        'qty_awal' => 10,
        'qty_aktual' => 9,
    ]);
});

test('duplicate lot number is rejected', function () {
    $user = User::factory()->create(['role' => 'gudang']);
    $this->actingAs($user);

    \App\Models\Bpg::factory()->create([
        'lot_number' => 'LOT-1',
        'qty' => 20,
        'nama_barang' => 'Barang',
        'supplier' => 'Supp',
    ]);

    $payload = [
        'items' => [[
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
        ]],
    ];

    $this->post('/gudang/ttpb', $payload)->assertRedirect('/gudang/ttpb/preview');

    $this->post('/gudang/ttpb', $payload)
        ->assertSessionHasErrors(['items.0.lot_number']);

    $this->assertDatabaseCount('ttpbs', 1);
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

test('saldo calculation uses actual qty from bpg', function () {
    $user = User::factory()->create(['role' => 'gudang']);
    $this->actingAs($user);

    // BPG reports a planned qty higher than the actual received qty
    \App\Models\Bpg::factory()->create([
        'lot_number' => 'LOT-ACT',
        'nama_barang' => 'Barang',
        'supplier' => 'Supp',
        'qty' => 10,
        'qty_aktual' => 8,
    ]);

    $payload = [
        'items' => [[
            'tanggal' => '2024-01-01',
            'no_ttpb' => 'TTPB-ACT',
            'lot_number' => 'LOT-ACT',
            'nama_barang' => 'Barang',
            'qty_awal' => 9, // exceeds actual saldo of 8
            'qty_aktual' => 8,
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

test('no records are saved when any added row exceeds saldo', function () {
    $user = User::factory()->create(['role' => 'gudang']);
    $this->actingAs($user);

    \App\Models\Bpg::factory()->create([
        'lot_number' => 'LOT-Z',
        'qty' => 10,
        'nama_barang' => 'Barang',
        'supplier' => 'Supp',
    ]);

    $payload = [
        'items' => [
            [
                'tanggal' => '2024-01-01',
                'no_ttpb' => 'TTPB-010',
                'lot_number' => 'LOT-Z',
                'nama_barang' => 'Barang',
                'qty_awal' => 6,
                'qty_aktual' => 6,
                'qty_loss' => 0,
                'persen_loss' => 0,
                'dari' => 'gudang',
                'ke' => 'pencucian',
            ],
            [
                'tanggal' => '2024-01-01',
                'no_ttpb' => 'TTPB-010',
                'lot_number' => 'LOT-Z',
                'nama_barang' => 'Barang',
                'qty_awal' => 6,
                'qty_aktual' => 6,
                'qty_loss' => 0,
                'persen_loss' => 0,
                'dari' => 'gudang',
                'ke' => 'pencucian',
            ],
        ],
    ];

    $this->from('/gudang/ttpb/create')
        ->post('/gudang/ttpb', $payload)
        ->assertSessionHasErrors('qty_awal');

    $this->assertDatabaseCount('ttpbs', 0);
});

test('stored ttpb appears in preview and receiving stock', function () {
    $gudangUser = User::factory()->create(['role' => 'gudang']);
    $pencucianUser = User::factory()->create(['role' => 'pencucian']);
    $this->actingAs($gudangUser);

    \App\Models\Bpg::factory()->create([
        'lot_number' => 'LOT-P',
        'qty' => 20,
        'nama_barang' => 'Barang',
        'supplier' => 'Supp',
    ]);

    $payload = [
        'tanggal' => '2024-01-01',
        'no_ttpb' => 'TTPB-050',
        'lot_number' => 'LOT-P',
        'nama_barang' => 'Barang',
        'qty_awal' => 5,
        'qty_aktual' => 5,
        'qty_loss' => 0,
        'persen_loss' => 0,
        'dari' => 'gudang',
        'ke' => 'pencucian',
    ];

    $this->post('/gudang/ttpb', $payload)->assertRedirect('/gudang/ttpb/preview');

    $this->actingAs($pencucianUser);
    $this->getJson('/pencucian/api/ttpb/LOT-P')
        ->assertOk()
        ->assertJson([
            'nama_barang' => 'Barang',
            'qty_aktual' => 5,
        ]);
});


test('store ttpb saves records into role specific tables', function () {
    $gudangUser = User::factory()->create(['role' => 'gudang']);
    $this->actingAs($gudangUser);

    \App\Models\Bpg::factory()->create([
        'lot_number' => 'LOT-R',
        'qty' => 10,
        'nama_barang' => 'Barang',
        'supplier' => 'Supp',
    ]);

    $payload = [
        'tanggal' => '2024-01-01',
        'no_ttpb' => 'TTPB-777',
        'lot_number' => 'LOT-R',
        'nama_barang' => 'Barang',
        'qty_awal' => 5,
        'qty_aktual' => 5,
        'qty_loss' => 0,
        'persen_loss' => 0,
        'dari' => 'gudang',
        'ke' => 'pencucian',
    ];

    $this->post('/gudang/ttpb', $payload)->assertRedirect('/gudang/ttpb/preview');

    $this->assertDatabaseHas('gudang_ttpbs', [
        'no_ttpb' => 'TTPB-777',
        'dari' => 'gudang',
        'ke' => 'pencucian',
    ]);

    $this->assertDatabaseHas('pencucian_ttpbs', [
        'no_ttpb' => 'TTPB-777',
        'dari' => 'gudang',
        'ke' => 'pencucian',
    ]);
});

test('api returns last ttpb entry for role', function () {
    $gudangUser = User::factory()->create(['role' => 'gudang']);
    $this->actingAs($gudangUser);

    \App\Models\Bpg::factory()->create([
        'lot_number' => 'LOT-L',
        'qty' => 10,
        'nama_barang' => 'Barang',
        'supplier' => 'Supp',
    ]);

    $payload1 = [
        'tanggal' => '2024-01-01',
        'no_ttpb' => 'TTPB-901',
        'lot_number' => 'LOT-L',
        'nama_barang' => 'Barang',
        'qty_awal' => 3,
        'qty_aktual' => 3,
        'qty_loss' => 0,
        'persen_loss' => 0,
        'dari' => 'gudang',
        'ke' => 'pencucian',
    ];

    $payload2 = $payload1;
    $payload2['no_ttpb'] = 'TTPB-902';
    $payload2['qty_awal'] = 4;
    $payload2['qty_aktual'] = 4;
    $payload2['lot_number'] = 'LOT-M';

    $this->post('/gudang/ttpb', $payload1)->assertRedirect('/gudang/ttpb/preview');
    \App\Models\Ttpb::create($payload2);

    $this->getJson('/gudang/api/ttpb-last')
        ->assertOk()
        ->assertJson(['no_ttpb' => 'TTPB-902']);
});
