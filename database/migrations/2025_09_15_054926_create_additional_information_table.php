<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('additional_information', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('school_year_id');
            $table->unsignedBigInteger('learner_id');
            $table->string('lrn');
            $table->date('current_date');
            $table->string('sex');
            $table->string('grade');
            $table->string('curriculum');
            $table->string('disability')->nullable();
            $table->json('living_mode'); // json column
            $table->string('address');
            $table->string('contact_number');
            $table->date('birthday');
            $table->integer('age');
            $table->string('religion');
            $table->string('nationality');
            $table->string('fb_messenger') ->nullable();

            $table->string('father_name')->nullable();
            $table->integer('father_age')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_place_work')->nullable();
            $table->string('father_contact')->nullable();
            $table->string('father_fb') ->nullable();

            $table->string('mother_name')->nullable();
            $table->integer('mother_age')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_place_work')->nullable();
            $table->string('mother_contact')->nullable()    ;
            $table->string('mother_fb') ->nullable();

            $table->string('guardian_name')->nullable();
            $table->integer('guardian_age')->nullable();
            $table->string('guardian_occupation')->nullable();
            $table->string('guardian_place_work')->nullable();
            $table->string('guardian_contact')->nullable();
            $table->string('guardian_fb') ->nullable();

            $table->boolean('student_agreement_1');
            $table->boolean('student_agreement_2');
            $table->boolean('parent_agreement_1');
            $table->boolean('parent_agreement_2');

            $table->timestamps();

            $table->foreign('school_year_id')->references('id')->on('school_years')->onDelete('cascade');
            $table->foreign('learner_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('additional_information');
    }
};
