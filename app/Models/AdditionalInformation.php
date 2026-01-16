<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalInformation extends Model
{
    use HasFactory;

    protected $table = 'additional_information';

    protected $fillable = [
        'learner_id',
        'school_year_id',
        'lrn',
        'sex',
        'grade',
        'curriculum',
        'section',
        'living_mode',
        'address',
        'contact_number',
        'birthday',
        'age',
        'religion',
        'religion_denomination',
        'nationality',
        'fb_messenger',
        'current_date',
        'disability', // ✅ ADD THIS LINE
        'profile_picture',

        'mother_name',
        'mother_age',
        'mother_occupation',
        'mother_place_work',
        'mother_contact',
        'mother_fb',

        'father_name',
        'father_age',
        'father_occupation',
        'father_place_work',
        'father_contact',
        'father_fb',

        'guardian_name',
        'guardian_age',
        'guardian_occupation',
        'guardian_place_work',
        'guardian_contact',
        'guardian_fb',
        'guardian_relationship',

        'student_agreement_1',
        'student_agreement_2',
        'parent_agreement_1',
        'parent_agreement_2',
    ];

    protected $casts = [
        'living_mode' => 'array', // This will automatically handle JSON encoding/decoding
        'birthday' => 'date',
        'current_date' => 'date',
        'student_agreement_1' => 'boolean',
        'student_agreement_2' => 'boolean',
        'parent_agreement_1' => 'boolean',
        'parent_agreement_2' => 'boolean',
    ];

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    public function learner()
    {
        return $this->belongsTo(User::class, 'learner_id');
    }
}