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
        Schema::table('life_values_results', function (Blueprint $table) {
            $table->boolean('is_retake')->default(false);
            $table->unsignedBigInteger('previous_result_id')->nullable();
            $table->boolean('admin_reopened')->default(false);

            $table->foreign('previous_result_id')->references('id')->on('life_values_results')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('life_values_results', function (Blueprint $table) {
            $table->dropForeign(['previous_result_id']);
            $table->dropColumn(['is_retake', 'previous_result_id', 'admin_reopened']);
        });
    }
};
