<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ttpb extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'no_ttpb',
        'lot_number',
        'nama_barang',
        'qty_awal',
        'qty_aktual',
        'qty_loss',
        'persen_loss',
        'kadar_air',
        'deviasi',
        'coly',
        'spec',
        'keterangan',
        'dari',
        'ke',
    ];
}
