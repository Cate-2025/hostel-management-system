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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('roll_number')->unique();
            $table->string('enrollment_number')->unique();
            $table->string('program');
            $table->integer('semester');
            $table->string('contact_number');
            $table->string('parent_contact')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('roll_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
