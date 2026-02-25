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
      Schema::create('guests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('event_id') 
          ->constrained('events') 
          ->onDelete('cascade');  
    $table->integer('guest_no');
    $table->string('name', 255);
    $table->string('phone', 50)->nullable();
    $table->string('address', 255)->nullable();
    $table->text('note')->nullable();
    $table->string('status')->default('pending');
    $table->timestamps(); 
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
