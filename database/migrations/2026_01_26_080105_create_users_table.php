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
        Schema::create('userss', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("email")->unique(); // Changed to string and unique
            $table->string("password");
            $table->string("number")->nullable();
            $table->string("numero")->nullable();
            $table->text("pictures")->nullable();
            $table->boolean("verified")->default(false);
            $table->string("verification_token", 64)->nullable(); // Added length for token
            $table->timestamp('email_verified_at')->nullable(); // Added this column
            $table->timestamps();
            
            // Add index for verification token for better performance
            $table->index('verification_token');
            $table->index('email_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('userss');
    }
};