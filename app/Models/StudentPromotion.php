<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPromotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_admission_id',
        'student_class_id',
        'student_section_id',
        'promotion_status',
        'created_by_id',
        'updated_by_id',
    ];
}
