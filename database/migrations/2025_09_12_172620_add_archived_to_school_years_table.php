<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('school_years', function (Blueprint $table) {
            $table->boolean('archived')->default(0); // 0 = active, 1 = archived
        });
    }
    
    public function down()
    {
        Schema::table('school_years', function (Blueprint $table) {
            $table->dropColumn('archived');
        });
    }
    
};
