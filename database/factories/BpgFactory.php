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

        return [
            'tanggal' => now()->toDateString(),
            'no_bpg' => $this->faker->unique()->numerify('BPG-###'),
            'lot_number' => $this->faker->unique()->bothify('LOT-###'),
            'supplier' => $this->faker->company,
            'nomor_mobil' => strtoupper($this->faker->bothify('?? #### ??')),
            'nama_barang' => $this->faker->word,
            // Default to no losses; `qty_aktual` will be set in configure()
            // if not explicitly provided when the factory is used.
            'qty' => $qty,
            'qty_aktual' => null,
            'qty_loss' => 0,
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
