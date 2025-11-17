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
        Schema::table('additional_information', function (Blueprint $table) {
            $table->string('guardian_relationship')->nullable()->after('guardian_fb');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('additional_information', function (Blueprint $table) {
            $table->dropColumn('guardian_relationship');
        });
    }
};
