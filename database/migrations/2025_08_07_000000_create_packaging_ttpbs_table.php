<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packaging_ttpbs', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('no_ttpb');
            $table->string('lot_number');
            $table->string('nama_barang');
            $table->double('qty_awal');
            $table->double('qty_aktual');
            $table->double('qty_loss')->nullable();
            $table->decimal('persen_loss', 5, 2)->nullable();
            $table->string('coly')->nullable();
            $table->string('spec')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('dari');
            $table->string('ke');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packaging_ttpbs');
    }
};
