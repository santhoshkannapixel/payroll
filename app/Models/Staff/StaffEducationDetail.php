<?php

namespace App\Models\Staff;

use App\Models\Master\Board;
use App\Models\Master\ProfessionType;
use App\Models\Master\Subject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffEducationDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_id',
        'staff_id',
        'course_started_year',	
        'course_completed_year',	
        'board_id',	
        'main_subject_id',	
        'ancillary_subject_id',	
        'certificate_no',	
        'submitted_date',	
        'education_type',	
        'doc_file',	
        'multi_file',	
        'status'
    ];

    public function boards()
    {
        return $this->hasOne(Board::class, 'id', 'board_id');
    }

    public function mainSubject()
    {
        return $this->hasOne(Subject::class, 'id', 'main_subject_id');
    }

    public function axSubject()
    {
        return $this->hasOne(Subject::class, 'id', 'ancillary_subject_id');
    }

    public function eduType()
    {
        return $this->hasOne(ProfessionType::class, 'id', 'education_type');
    }
}
