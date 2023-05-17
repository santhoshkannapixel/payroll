<?php

namespace App\Models\Staff;

use App\Models\AttendanceManagement\LeaveMapping;
use App\Models\Master\AppointmentOrderModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Master\NatureOfEmployment;
use App\Models\Master\PlaceOfWork;
use App\Models\Master\StaffCategory;
use App\Models\Master\TeachingType;

class StaffAppointmentDetail extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'academic_id',	
        'staff_id',	
        'category_id',	
        'nature_of_employment_id',	
        'teaching_type_id',	
        'place_of_work_id',	
        'joining_date',	
        'salary_scale',	
        'from_appointment',	
        'to_appointment',	
        'appointment_order_model_id',	
        'has_probation',
        'probation_period',
        'appointment_doc',
        'appointment_order_no',
        'status'
    ];
    public function employment_nature()
    {
        return $this->hasOne(NatureOfEmployment::class, 'id','nature_of_employment_id');
    }

    public function work_place()
    {
        return $this->hasOne(PlaceOfWork::class, 'id', 'place_of_work_id');
    }

    public function staffCategory()
    {
        return $this->hasOne(StaffCategory::class, 'id', 'category_id');
    }

    public function teachingType()
    {
        return $this->hasOne(TeachingType::class, 'id', 'teaching_type_id');
    }

    public function appointmentOrderModel()
    {
        return $this->hasOne(AppointmentOrderModel::class, 'id', 'appointment_order_model_id');
    }

    public function leaveAllocated()
    {
        return $this->hasMany(LeaveMapping::class, 'nature_of_employment_id', 'nature_of_employment_id');
    }

    public function leaveAllocatedYear()
    {
        return $this->hasMany(LeaveMapping::class, 'nature_of_employment_id', 'nature_of_employment_id')
                ->groupBy('nature_of_employment_id', 'academic_id')
                ->selectRaw('sum(CAST(leave_mappings.leave_days as int)) as total_leave, nature_of_employment_id, academic_id');
    }
}
