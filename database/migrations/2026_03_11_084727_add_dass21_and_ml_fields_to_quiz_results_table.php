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
        Schema::table('quiz_results', function (Blueprint $table) {
            $table->integer('dass21_stress')->nullable()->after('completed_at');
            $table->integer('dass21_anxiety')->nullable()->after('dass21_stress');
            $table->integer('dass21_depression')->nullable()->after('dass21_anxiety');
            $table->json('ml_prediction')->nullable()->after('dass21_depression');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_results', function (Blueprint $table) {
            $table->dropColumn(['dass21_stress', 'dass21_anxiety', 'dass21_depression', 'ml_prediction']);
        });
    }
};
