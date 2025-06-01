<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->string('portfolio_link')->nullable()->after('user_id');
            $table->boolean('is_verified')->default(false)->after('portfolio_link');
            $table->decimal('rating', 3, 2)->nullable()->after('is_verified'); // Allows for ratings like X.XX
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('artists', function (Blueprint $table) {
            $table->dropColumn(['portfolio_link', 'is_verified', 'rating']);
        });
    }
};
