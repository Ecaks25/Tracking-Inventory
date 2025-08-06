<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bpg extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'no_bpg',
        'lot_number',
        'supplier',
        'nama_barang',
        'qty',
        'coly',
        'diterima',
        'ttpb',
    ];

    protected $casts = [
        'qty' => 'float',
    ];
}
