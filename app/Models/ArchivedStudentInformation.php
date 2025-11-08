<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivedStudentInformation extends Model
{
    use HasFactory;

    protected $table = 'archived_student_information';

    protected $fillable = [
        'school_year_id',
        'learner_id',
        'lrn',
        'sex',
        'grade',
        'curriculum',
        'section',
        'disability',
        'living_mode',
        'address',
        'contact_number',
        'birthday',
        'age',
        'religion',
        'nationality',
        'fb_messenger',
        'father_name',
        'father_age',
        'father_occupation',
        'father_place_work',
        'father_contact',
        'father_fb',
        'mother_name',
        'mother_age',
        'mother_occupation',
        'mother_place_work',
        'mother_contact',
        'mother_fb',
        'guardian_name',
        'guardian_age',
        'guardian_occupation',
        'guardian_place_work',
        'guardian_contact',
        'guardian_fb',
        'student_agreement_1',
        'student_agreement_2',
        'parent_agreement_1',
        'parent_agreement_2',

    ];

    protected $casts = [
        'living_mode' => 'array',
    ];

    // 🔗 Relationships
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'learner_id');
    }
}
