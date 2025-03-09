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
        Schema::table('users', function (Blueprint $table) {
            // Agregar el campo role si no existe
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'teacher', 'student'])->default('student')->after('password');
            }

            // Agregar claves foráneas a otras tablas (escuela, grado, grupo, turno)
            if (!Schema::hasColumn('users', 'school_id')) {
                $table->foreignId('school_id')->nullable()->constrained('schools')->onDelete('set null')->after('role');
            }

            if (!Schema::hasColumn('users', 'grade_id')) {
                $table->foreignId('grade_id')->nullable()->constrained('grades')->onDelete('set null')->after('school_id');
            }

            if (!Schema::hasColumn('users', 'group_id')) {
                $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('set null')->after('grade_id');
            }

            if (!Schema::hasColumn('users', 'turno_id')) {
                $table->foreignId('turno_id')->nullable()->constrained('turnos')->onDelete('set null')->after('group_id');
            }

            // Agregar campos de apellido si no existen
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable()->after('name');
            }

            if (!Schema::hasColumn('users', 'second_last_name')) {
                $table->string('second_last_name')->nullable()->after('last_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminar columnas si existen
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }

            if (Schema::hasColumn('users', 'school_id')) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            }

            if (Schema::hasColumn('users', 'grade_id')) {
                $table->dropForeign(['grade_id']);
                $table->dropColumn('grade_id');
            }

            if (Schema::hasColumn('users', 'group_id')) {
                $table->dropForeign(['group_id']);
                $table->dropColumn('group_id');
            }

            if (Schema::hasColumn('users', 'turno_id')) {
                $table->dropForeign(['turno_id']);
                $table->dropColumn('turno_id');
            }

            if (Schema::hasColumn('users', 'last_name')) {
                $table->dropColumn('last_name');
            }

            if (Schema::hasColumn('users', 'second_last_name')) {
                $table->dropColumn('second_last_name');
            }
        });
    }
};
