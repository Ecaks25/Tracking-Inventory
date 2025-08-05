<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Bpg;

class BpgFactory extends Factory
{
    protected $model = Bpg::class;

    public function definition(): array
    {
        return [
            'tanggal' => now()->toDateString(),
            'no_bpg' => $this->faker->unique()->numerify('BPG-###'),
            'lot_number' => $this->faker->unique()->bothify('LOT-###'),
            'supplier' => $this->faker->company,
            'nama_barang' => $this->faker->word,
            'qty' => $this->faker->numberBetween(1, 100),
            'coly' => $this->faker->word,
            'diterima' => $this->faker->name,
            'ttpb' => $this->faker->numerify('TTPB-###'),
        ];
    }
}
