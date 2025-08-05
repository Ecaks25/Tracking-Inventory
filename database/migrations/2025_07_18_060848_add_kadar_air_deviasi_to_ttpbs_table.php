<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ttpbs', function (Blueprint $table) {
            $table->decimal('kadar_air', 5, 2)->nullable()->after('persen_loss');
            $table->decimal('deviasi', 5, 2)->nullable()->after('kadar_air');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ttpbs', function (Blueprint $table) {
            $table->dropColumn(['kadar_air', 'deviasi']);
        });
    }
};
