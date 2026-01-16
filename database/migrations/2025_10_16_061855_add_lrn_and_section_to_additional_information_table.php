<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('additional_information', function (Blueprint $table) {
            // removed lrn because it already exists
            if (!Schema::hasColumn('additional_information', 'section')) {
                $table->string('section')->after('curriculum');
            }
        });
    }

    public function down(): void
    {
        Schema::table('additional_information', function (Blueprint $table) {
            if (Schema::hasColumn('additional_information', 'section')) {
                $table->dropColumn('section');
            }
        });
    }
};
