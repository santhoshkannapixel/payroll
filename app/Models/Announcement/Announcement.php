<?php

namespace App\Models\Announcement;

use App\Models\Scopes\AcademicScope;
use App\Models\Scopes\InstitutionScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_id',
        'institute_id',
        'announcement_type',
        'from_date',
        'to_date',
        'message',
        'announcement_created_id',
        'sort_order',
        'status'
    ];

    public function scopeAcademic($query)
    {
        if( session()->get('academic_id') && !empty( session()->get('academic_id') ) ){

            return $query->where('announcements.academic_id', session()->get('academic_id'));
        }
    }

    public function scopeInstituteBased($query)
    {
        if( session()->get('staff_institute_id') && !empty( session()->get('staff_institute_id') ) ){

            return $query->where('announcements.institute_id', session()->get('staff_institute_id'));
        }
    }

}
