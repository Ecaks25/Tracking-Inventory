<?php

use App\Models\User;
use App\Models\Bpg;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('stock page can be filtered by month', function () {
    $user = User::factory()->create(['role' => 'gudang']);
    $this->actingAs($user);

    Bpg::factory()->create(['tanggal' => '2024-01-05', 'lot_number' => 'LOT-001']);
    Bpg::factory()->create(['tanggal' => '2024-02-10', 'lot_number' => 'LOT-002']);

    $this->get('/gudang/stock?month=2024-01')
        ->assertOk()
        ->assertSee('LOT-001')
        ->assertDontSee('LOT-002');
});

test('stock page can be filtered by lot number', function () {
    $user = User::factory()->create(['role' => 'gudang']);
    $this->actingAs($user);

    Bpg::factory()->create(['lot_number' => 'LOT-AAA']);
    Bpg::factory()->create(['lot_number' => 'LOT-BBB']);

    $this->get('/gudang/stock?lot=AAA')
        ->assertOk()
        ->assertSee('LOT-AAA')
        ->assertDontSee('LOT-BBB');
});
