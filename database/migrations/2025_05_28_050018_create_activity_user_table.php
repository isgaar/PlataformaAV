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
        Schema::create('activity_user', function (Blueprint $table) {
            $table->id();

            $table->foreignId('activity_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->boolean('done')->default(false);
            $table->string('session')->nullable(); // ← hacer nullable es útil si no siempre se define

            $table->timestamps();

            // Evitar duplicados exactos de asignación por usuario, actividad y sesión
            $table->unique(['user_id', 'activity_id', 'session']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_user');
    }
};
