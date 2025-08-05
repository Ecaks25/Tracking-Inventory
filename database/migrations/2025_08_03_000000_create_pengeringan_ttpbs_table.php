<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeringan_ttpbs', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('no_ttpb');
            $table->string('lot_number');
            $table->string('nama_barang');
            $table->integer('qty_awal');
            $table->integer('qty_aktual');
            $table->integer('qty_loss')->nullable();
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
        Schema::dropIfExists('pengeringan_ttpbs');
    }
};
