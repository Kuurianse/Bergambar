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
        Schema::table('payments', function (Blueprint $table) {
            // Change precision for the amount column
            // From decimal(8, 2) to decimal(12, 2) to allow larger values
            $table->decimal('amount', 12, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Revert back to original precision decimal(8, 2)
            // Warning: This could cause data loss if values larger than 999999.99 exist.
            $table->decimal('amount', 8, 2)->change();
        });
    }
};
