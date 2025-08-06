<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Bpg;

class BpgFactory extends Factory
{
    protected $model = Bpg::class;

    public function definition(): array
    {
        $qty = $this->faker->randomFloat(1, 1, 100);
        $qtyAktual = $this->faker->randomFloat(1, 0, $qty);

        return [
            'tanggal' => now()->toDateString(),
            'no_bpg' => $this->faker->unique()->numerify('BPG-###'),
            'lot_number' => $this->faker->unique()->bothify('LOT-###'),
            'supplier' => $this->faker->company,
            'nama_barang' => $this->faker->word,
            'qty' => $qty,
            'qty_aktual' => $qtyAktual,
            'qty_loss' => $qty - $qtyAktual,
            'coly' => $this->faker->word,
            'diterima' => $this->faker->name,
            'ttpb' => $this->faker->numerify('TTPB-###'),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Bpg $bpg) {
            if ($bpg->qty_aktual === null) {
                $bpg->qty_aktual = $bpg->qty;
            }
            $bpg->qty_loss = $bpg->qty - $bpg->qty_aktual;
        });
    }
}
