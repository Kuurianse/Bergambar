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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('artist_id');
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->enum('service_type', ['illustration', 'music', 'rigging', 'other']);
            $table->boolean('availability_status')->default(true);
            $table->timestamps();
        
            $table->foreign('artist_id')
                  ->references('id')
                  ->on('artists')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
};
