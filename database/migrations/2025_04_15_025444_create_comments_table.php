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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thread_id')->constrained()->onDelete('cascade');
            $table->foreignId('temporal_user_id')
                  ->constrained()              // Assumes temporal_users table is called 'temporal_users'
                  ->onDelete('cascade');        // If a temporal user is deleted, delete their comments
    
            $table->text('body');
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
