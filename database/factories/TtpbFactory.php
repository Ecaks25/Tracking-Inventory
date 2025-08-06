<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ttpb;

class TtpbFactory extends Factory
{
    protected $model = Ttpb::class;

    public function definition(): array
    {
        return [
            'tanggal' => now()->toDateString(),
            'no_ttpb' => $this->faker->unique()->numerify('TTPB-###'),
            'lot_number' => $this->faker->bothify('LOT-###'),
            'nama_barang' => $this->faker->word,
            'qty_awal' => $this->faker->randomFloat(1, 1, 100),
            'qty_aktual' => $this->faker->randomFloat(1, 1, 100),
            'qty_loss' => $this->faker->randomFloat(1, 0, 10),
            'persen_loss' => $this->faker->randomFloat(2, 0, 100),
            'coly' => $this->faker->word,
            'spec' => $this->faker->word,
            'keterangan' => $this->faker->sentence,
            'dari' => 'gudang',
            'ke' => 'pencucian',
        ];
    }
}
