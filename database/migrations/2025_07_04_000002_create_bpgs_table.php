<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bpgs', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('no_bpg');
            $table->string('lot_number');
            $table->string('supplier');
            $table->string('nama_barang');
            $table->double('qty');
            $table->string('coly')->nullable();
            $table->string('diterima');
            $table->string('ttpb');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bpgs');
    }
};
