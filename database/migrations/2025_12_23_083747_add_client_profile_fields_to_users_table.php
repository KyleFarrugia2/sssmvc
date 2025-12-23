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
            $table->decimal('weight_kg', 5, 2)->nullable()->after('password');
            $table->decimal('height_cm', 5, 2)->nullable()->after('weight_kg');
            $table->unsignedTinyInteger('age')->nullable()->after('height_cm');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('age');
            $table->enum('activity_level', ['sedentary', 'light', 'moderate', 'active', 'very_active'])->default('moderate')->after('gender');
            $table->enum('weight_goal', ['lose', 'maintain', 'gain'])->default('maintain')->after('activity_level');
            $table->decimal('target_weight_kg', 5, 2)->nullable()->after('weight_goal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'weight_kg',
                'height_cm',
                'age',
                'gender',
                'activity_level',
                'weight_goal',
                'target_weight_kg',
            ]);
        });
    }
};
