<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bpgs', function (Blueprint $table) {
            $table->unique('lot_number');
        });

        Schema::table('ttpbs', function (Blueprint $table) {
            $table->unique('lot_number');
        });
    }

    public function down(): void
    {
        Schema::table('bpgs', function (Blueprint $table) {
            $table->dropUnique('bpgs_lot_number_unique');
        });

        Schema::table('ttpbs', function (Blueprint $table) {
            $table->dropUnique('ttpbs_lot_number_unique');
        });
    }
};
