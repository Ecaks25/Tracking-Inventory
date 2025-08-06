<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bpgs', function (Blueprint $table) {
            $table->double('qty_aktual')->nullable()->after('qty');
            $table->double('qty_loss')->nullable()->after('qty_aktual');
        });
    }

    public function down(): void
    {
        Schema::table('bpgs', function (Blueprint $table) {
            $table->dropColumn(['qty_aktual', 'qty_loss']);
        });
    }
};
