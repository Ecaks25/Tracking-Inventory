<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('duplicate lot number in bpg is rejected', function () {
    $user = User::factory()->create(['role' => 'gudang']);
    $this->actingAs($user);

    $payload = [
        'tanggal' => '2024-01-01',
        'no_bpg' => 'BPG-001',
        'lot_number' => 'LOT-1',
        'supplier' => 'Supp',
        'nomor_mobil' => 'AB-123',
        'nama_barang' => 'Barang',
        'qty' => 5,
        'qty_aktual' => 5,
        'qty_loss' => 0,
        'diterima' => 'A',
        'ttpb' => 'TTPB-1',
    ];

    $this->post('/gudang/stock', $payload)->assertRedirect('/gudang/stock');

    $this->post('/gudang/stock', $payload)
        ->assertSessionHasErrors(['lot_number']);

    $this->assertDatabaseCount('bpgs', 1);
});
