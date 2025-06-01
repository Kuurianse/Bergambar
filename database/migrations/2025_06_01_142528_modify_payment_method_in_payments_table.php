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
            // Change enum to string, ensuring to handle existing data if necessary (though for enum to string, it's usually fine)
            // Making it a string of length 50.
            $table->string('payment_method', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Revert back to enum. This requires knowing the original enum values.
            // This is a best-effort rollback. Data changed to non-enum values will cause issues.
            $originalEnumValues = ['paypal', 'credit_card', 'bank_transfer'];
            $table->enum('payment_method', $originalEnumValues)->change();
        });
    }
};
