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
        Schema::table('commissions', function (Blueprint $table) {
            // Change enum to string, length 50, with a default.
            // Consider existing data: if current statuses are 'pending', 'accepted', 'completed', they will fit.
            $table->string('status', 50)->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commissions', function (Blueprint $table) {
            // Revert back to original enum.
            // Data changed to values not in this enum list will cause issues on rollback or further operations.
            $originalEnumValues = ['pending', 'accepted', 'completed'];
            $table->enum('status', $originalEnumValues)->default('pending')->change();
        });
    }
};
