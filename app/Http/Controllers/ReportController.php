<?php

namespace App\Http\Controllers;

use App\Exports\Reports\AttendanceReport;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Master\Department;
use App\Models\User;
use App\Models\PayrollManagement\StaffSalaryPattern;
use App\Models\PayrollManagement\SalaryField;
use App\Repositories\ReportRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use App\Models\Leave\StaffLeave;
use App\Models\PayrollManagement\StaffSalary;
use App\Models\PayrollManagement\Payroll;
use DataTables;
use Carbon\Carbon;
use App\Models\Staff\StaffBankLoan;
use App\Models\Staff\StaffInsurance;
use App\Models\PayrollManagement\HoldSalary;
use App\Models\Staff\StaffRetiredResignedDetail;
use App\Models\Staff\StaffSalaryPreEarning;
use App\Models\PayrollManagement\ItStaffStatement;
class ReportController extends Controller
{
    public $repository;

    public function __construct(ReportRepository $repository) {
        $this->repository = $repository;
    }

    function index()
    {
        return view('pages.reports.index');
    }

    public function profileReports(Request $request)
    {

        if ($request->ajax()) {
            $details = User::with(['personal', 'position'])->whereNull('is_super_admin')->orderBy('society_emp_code', 'desc')->where('institute_id',session()->get('staff_institute_id'))->get();
            $params = ['details' => $details];
            return view('pages.reports.profile._table_content', $params);
        }
        return view('pages.reports.profile._index');
    } 
    public function commonExport(Request $request)
    {
    }

    function attendance_collection($request, $date) {
        return User::where('institute_id',session()->get('staff_institute_id'))->with(['AttendancePresent', 'Attendance' => function ($query) use ($date) {
            $query->whereBetween('attendance_date', [$date['start_date'], $date['end_date']]);
        }])
        ->leftJoin('staff_appointment_details', function($join){
            $join->on('staff_appointment_details.staff_id', '=','users.id')
                    ->where('staff_appointment_details.academic_id', academicYearId());
        })
        ->select('users.*','staff_appointment_details.place_of_work_id')
        ->when(!is_null($request->place_of_work),function($q) use ($request){
            $q->where('place_of_work_id', $request->place_of_work);
        });
    }

    function attendance_index(Request $request)
    {
        $month         = $request->month ?? date('m');
        $academic_info = AcademicYear::find( academicYearId());
        if( $academic_info ) {
            $from_year = $academic_info->from_year.'-'.$month.'-01';
            $start_date = date('Y-m-d', strtotime($from_year) );
            $no_of_days = date('t', strtotime($start_date) );
        }
        $place_of_work = $request->place_of_work ?? null;
        $date          = getStartAndEndDateOfMonth($month);
        $month_days    = monthDays($month);

        $perPage = (!empty($request->limit) && $request->limit === 'all') ? 100000000000000000000 : $request->limit;
        $attendance    = $this->attendance_collection($request,$date)->paginate($perPage);
        return view('pages.reports.attendance._index', compact('attendance', 'month_days','month','place_of_work', 'start_date', 'no_of_days'));
    }

    function attendance_export(Request $request) {
        $month       = $request->month ?? date('m');
        $date        = getStartAndEndDateOfMonth($month);
        $month_days  = monthDays($month);
        $attendance  = $this->attendance_collection($request,$date)->get();
        return Excel::download(new AttendanceReport($attendance, $month_days),'attendance.xlsx');
    }

    public function serviceHistoryIndex(Request $request) {
        $employee_id    = $request->employee ?? '';
        $department_id  = $request->department ?? '';
        $history_Data   = $this->repository->getServiceHistory($employee_id, $department_id );
        $history        = current( $history_Data );
        $paginate_link  = end( $history_Data );
        $employees      = User::whereNull('is_super_admin')->where('verification_status', 'approved')->where('institute_id',session()->get('staff_institute_id'))->get();
        $departments    = Department::all();
        $academic_info  = AcademicYear::find(academicYearId());
        $academic_title = 'HISTORY OF SERVICE ( '.$academic_info->from_year.' - '.$academic_info->to_year.' )';
        
        return view('pages.reports.service_history.index', compact('employee_id', 'department_id','history', 'paginate_link', 'employees','departments', 'academic_title' ));
    }

    public function serviceHistoryExport(Request $request) {

        $employee_id = $request->employee ?? '';
        $department_id = $request->department ?? '';

        $history_Data = $this->repository->getServiceHistory($employee_id, $department_id, 'export' );

        $history = current( $history_Data );
        $paginate_link = end( $history_Data );

        $academic_info = AcademicYear::find(academicYearId());
        $academic_title = 'HISTORY OF SERVICE ( '.$academic_info->from_year.' - '.$academic_info->to_year.' )';

        $data = [
            'history' => $history,
            'academic_title' => $academic_title
        ];
        $pdf = PDF::loadView('pages.reports.service_history._pdf_view',$data)->setPaper('a4', 'portrait');
        
        return $pdf->stream('service_history.pdf');

    }
    public function BankLoanReport(Request $request)
    {
        $breadcrums = array(
            'title' => 'Bank Loan Report',
            'breadcrums' => array(
                array(
                    'link' => '', 'title' => 'Bank Loan Report'
                ),
            )
        );
        $academic_info = AcademicYear::find(academicYearId());
        $datatable_search=$request->datatable_search;
        $month = $request->month ?? date('m');
        $year=$academic_info->from_year;
        $dates =  Carbon::now()->month($month)->year($year)->day(1)->format("Y-m-d");
        $from_date = date('Y-m-01', strtotime($dates));
        $data=StaffBankLoan::with('staff','staff.appointment','emione')
         ->when(!empty($datatable_search), function ($query) use ($datatable_search) {
                   
            return $query->where(function ($q) use ($datatable_search) {
                $q->whereHas('staff', function($jq) use($datatable_search){
                $jq->where('name', 'like', "%{$datatable_search}%")
                ->orWhere('institute_emp_code', 'like', "%{$datatable_search}%");
                    });
            });
        })
        ->when(!empty($from_date), function ($query) use ($from_date) {
            return $query->where(function ($q) use ($from_date) {

                $q->whereHas('emione', function($jq) use($from_date){
                $jq->where('emi_month',$from_date);
                    
                    });
            });

        })->get();
        if($request->ajax()){
        $datatables =  Datatables::of($data)
        ->addIndexColumn()
        ->editColumn('name', function ($row) { 
          
                    return $row['staff']->name ?? '';
        })->editColumn('emp_id', function ($row) {
                    return $row['staff']->institute_emp_code ?? '';
        })->editColumn('place', function ($row) {
                    return $row['staff']->appointment->work_place->name ?? '';
        })->editColumn('doj', function ($row) {
                    return $row['staff']->appointment->joining_date ?? '';
        })->editColumn('designation', function ($row) {
                    return $row['staff']->appointment->designation->name?? '';
        })->editColumn('instalment_no', function ($row) {
                    return $row->emione->staff_loan_id?? '';
        })->editColumn('balance_due', function ($row) {
                   return $row->emione->amount?? '';
        });
        return $datatables->make(true);
             }
        return view('pages.reports.bank_loan', compact('breadcrums','month'));
    }
    public function InsuranceReport(Request $request)
    {
        $breadcrums = array(
            'title' => ' Insurance Report',
            'breadcrums' => array(
                array(
                    'link' => '', 'title' => 'Insurance Report'
                ),
            )
        );
        $datatable_search=$request->datatable_search;
        $month = $request->month ?? date('m');
        $data=StaffInsurance::with('staff','staff.appointment')
         ->when(!empty($datatable_search), function ($query) use ($datatable_search) {
                   
            return $query->where(function ($q) use ($datatable_search) {
                $q->whereHas('staff', function($jq) use($datatable_search){
                $jq->where('name', 'like', "%{$datatable_search}%")
                ->orWhere('institute_emp_code', 'like', "%{$datatable_search}%");
                    });
            });
        })->whereMonth('start_date',$month)->orderBy('start_date','desc')->get();
        if($request->ajax()){
        $datatables =  Datatables::of($data)
        ->addIndexColumn()
        ->editColumn('name', function ($row) { 
          
                    return $row['staff']->name ?? '';
        })->editColumn('emp_id', function ($row) {
                    return $row['staff']->institute_emp_code ?? '';
        })->editColumn('place', function ($row) {
                    return $row['staff']->appointment->work_place->name ?? '';
        })->editColumn('doj', function ($row) {
                    return $row['staff']->appointment->joining_date ?? '';
        });
        return $datatables->make(true);
             }
        return view('pages.reports.lic', compact('breadcrums','month'));
    }
    public function SalaryHold(Request $request)
    {
        $breadcrums = array(
            'title' => ' Salary Hold Report',
            'breadcrums' => array(
                array(
                    'link' => '', 'title' => 'Salary Hold Report'
                ),
            )
        );
        $datatable_search=$request->datatable_search;
        $month = $request->month ?? date('m');
        $data=HoldSalary::with('staff','staff.appointment')->where('institute_id',session()->get('staff_institute_id'))
         ->when(!empty($datatable_search), function ($query) use ($datatable_search) {
                   
            return $query->where(function ($q) use ($datatable_search) {
                $q->whereHas('staff', function($jq) use($datatable_search){
                $jq->where('name', 'like', "%{$datatable_search}%")
                ->orWhere('institute_emp_code', 'like', "%{$datatable_search}%");
                    });
            });
        })->where('academic_id',academicYearId())->whereMonth('hold_month',$month)->orderBy('hold_month','desc')->get();
        if($request->ajax()){
        $datatables =  Datatables::of($data)
        ->addIndexColumn()
        ->editColumn('name', function ($row) { 
          
                    return $row['staff']->name ?? '';
        })->editColumn('emp_id', function ($row) {
                    return $row['staff']->institute_emp_code ?? '';
        })->editColumn('place', function ($row) {
                    return $row['staff']->appointment->work_place->name ?? '';
        })->editColumn('doj', function ($row) {
                    return $row['staff']->appointment->joining_date ?? '';
        })->editColumn('designation', function ($row) {
                    return $row['staff']->appointment->designation->name?? '';
        })->editColumn('net_salary', function ($row) {
                    return 0;
        });
        return $datatables->make(true);
             }
        return view('pages.reports.hold_salary', compact('breadcrums','month'));
    }
    public function LOP(Request $request)
    {
        $breadcrums = array(
            'title' => ' LOP Report',
            'breadcrums' => array(
                array(
                    'link' => '', 'title' => 'LOP Report'
                ),
            )
        );
        $datatable_search=$request->datatable_search;
               $academic=AcademicYear::find(academicYearId());
        $month = $request->month ?? date('m');
        $year = $academic->from_year;
        $dates =  Carbon::now()->month($month)->year($year)->day(1)->format("Y-m-d");
        $data=[];
        $from_date = date('Y-m-01', strtotime($dates));
        $to_date = date('Y-m-t', strtotime($dates));
        $payroll = Payroll::where('from_date', $from_date)->where('to_date', $to_date)->where('institute_id',session()->get('staff_institute_id'))->first();
        $payroll_id = $payroll->id ?? '';
        if($payroll){
            $data = StaffSalary::with('staff')->when(!empty($payroll_id), function($query) use($payroll_id){
                $query->where('payroll_id', $payroll_id);
            })->when(!empty($datatable_search), function ($query) use ($datatable_search) {

                return $query->where(function ($q) use ($datatable_search) {
                    $q->whereHas('staff', function($jq) use($datatable_search){
                        $jq->where('name', 'like', "%{$datatable_search}%")
                        ->orWhere('institute_emp_code', 'like', "%{$datatable_search}%");
                    });
                });
            })->orderby('created_at','desc')->get();
        }   
       
        if($request->ajax()){
        $datatables =  Datatables::of($data)
        ->addIndexColumn()
        ->editColumn('name', function ($row) { 
          
                    return $row['staff']->name ?? '';
        })->editColumn('emp_id', function ($row) {
                    return $row['staff']->institute_emp_code ?? '';
        })->editColumn('place', function ($row) {
                    return $row['staff']->appointment->work_place->name ?? '';
        })->editColumn('doj', function ($row) {
                    return $row['staff']->appointment->joining_date ?? '';
        })->editColumn('designation', function ($row) {
                    return $row['staff']->appointment->designation->name?? '';
        })->editColumn('lop', function ($row) {
                    $lop= $row->gross_salary/$row->working_days;
                    return number_format($lop,2);
        });
        return $datatables->make(true);
             }
        return view('pages.reports.lop', compact('breadcrums','month'));
    }
    public function ESI(Request $request)
    {
        $breadcrums = array(
            'title' => 'ESI Report',
            'breadcrums' => array(
                array(
                    'link' => '', 'title' => 'ESI Report'
                ),
            )
        );
        $datatable_search=$request->datatable_search;
               $academic=AcademicYear::find(academicYearId());
        $month = $request->month ?? date('m');
        $year = $academic->from_year;
        $dates =  Carbon::now()->month($month)->year($year)->day(1)->format("Y-m-d");
        $data=[];
        $from_date = date('Y-m-01', strtotime($dates));
        $to_date = date('Y-m-t', strtotime($dates));
        $payroll = Payroll::where('from_date', $from_date)->where('to_date', $to_date)->where('institute_id',session()->get('staff_institute_id'))->first();
        $payroll_id = $payroll->id ?? '';
        if($payroll){
            $data = StaffSalary::with('staff')->when(!empty($payroll_id), function($query) use($payroll_id){
                $query->where('payroll_id', $payroll_id);
            })->when(!empty($datatable_search), function ($query) use ($datatable_search) {

                return $query->where(function ($q) use ($datatable_search) {
                    $q->whereHas('staff', function($jq) use($datatable_search){
                        $jq->where('name', 'like', "%{$datatable_search}%")
                        ->orWhere('institute_emp_code', 'like', "%{$datatable_search}%");
                    });
                });
            })->orderby('created_at','desc')->get();
        }   
        if($request->ajax()){
        $datatables =  Datatables::of($data)
        ->addIndexColumn()
        ->editColumn('esi_no', function ($row) {
                 return $row['staff']->esi->ac_number ?? '-';

        })
        ->editColumn('name', function ($row) { 
          
            return $row['staff']->name ?? '';
        })->editColumn('gross_salary', function ($row) {
        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id,$row->id, '',"Employees' State Insurance",'DEDUCTIONS'));
        })->editColumn('no_of_days', function ($row) {
                    return 31;
        });
        return $datatables->make(true);
             }
        return view('pages.reports.esi', compact('breadcrums','month'));
    }
    public function Resigned(Request $request)
    {
        $breadcrums = array(
            'title' => 'Resigned Report',
            'breadcrums' => array(
                array(
                    'link' => '', 'title' => 'Resigned Report'
                ),
            )
        );
        $datatable_search=$request->datatable_search;
        $month = $request->month ?? date('m');
        $data=StaffRetiredResignedDetail::where('types','resigned')->with('staff')->where('institute_id',session()->get('staff_institute_id'))->when(!empty($datatable_search), function ($query) use ($datatable_search) {
                   
            return $query->where(function ($q) use ($datatable_search) {
                $q->whereHas('staff', function($jq) use($datatable_search){
                $jq->where('name', 'like', "%{$datatable_search}%")
                ->orWhere('institute_emp_code', 'like', "%{$datatable_search}%");
                    });
            });
        })->where('academic_id',academicYearId())->whereMonth('last_working_date',$month)->orderby('last_working_date','desc')->get();
        if($request->ajax()){
        $datatables =  Datatables::of($data)
        ->addIndexColumn()
        ->editColumn('name', function ($row) { 
          
                    return $row['staff']->name ?? '';
        })->editColumn('emp_id', function ($row) {
                    return $row['staff']->institute_emp_code ?? '';
        })->editColumn('place', function ($row) {
                    return $row['staff']->appointment->work_place->name ?? '';
        })->editColumn('doj', function ($row) {
                    return $row['staff']->appointment->joining_date ?? '';
        })->editColumn('designation', function ($row) {
                    return $row['staff']->appointment->designation->name?? '';
        });
        return $datatables->make(true);
             }
        return view('pages.reports.resigned', compact('breadcrums','month'));
    }
    public function Bonus(Request $request)
    {
        $breadcrums = array(
            'title' => 'Bonus Report',
            'breadcrums' => array(
                array(
                    'link' => '', 'title' => 'Bonus Report'
                ),
            )
        );
        $datatable_search=$request->datatable_search;
        $month = $request->month ?? date('m');
        $data=StaffSalaryPreEarning::where('earnings_type','bonus')->with('staff')->when(!empty($datatable_search), function ($query) use ($datatable_search) {
                   
            return $query->where(function ($q) use ($datatable_search) {
                $q->whereHas('staff', function($jq) use($datatable_search){
                $jq->where('name', 'like', "%{$datatable_search}%")
                ->orWhere('institute_emp_code', 'like', "%{$datatable_search}%");
                    });
            });
        })->where('academic_id',academicYearId())->whereMonth('salary_month',$month)->orderby('created_at','desc')->get();
        if($request->ajax()){
        $datatables =  Datatables::of($data)
        ->addIndexColumn()
        ->editColumn('name', function ($row) { 
          
                    return $row['staff']->name ?? '';
        })->editColumn('emp_id', function ($row) {
                    return $row['staff']->institute_emp_code ?? '';
        })->editColumn('place', function ($row) {
                    return $row['staff']->appointment->work_place->name ?? '';
        })->editColumn('doj', function ($row) {
                    return $row['staff']->appointment->joining_date ?? '';
        })->editColumn('designation', function ($row) {
                    return $row['staff']->appointment->designation->name?? '';
        });
        return $datatables->make(true);
             }
        
        return view('pages.reports.bonus', compact('breadcrums','month'));
    }
    public function Arrers(Request $request)
    {
        $breadcrums = array(
            'title' => 'Arrers Report',
            'breadcrums' => array(
                array(
                    'link' => '', 'title' => 'Arrers Report'
                ),
            )
        );
        $datatable_search=$request->datatable_search;
        $month = $request->month ?? date('m');
        $data=StaffSalaryPreEarning::where('earnings_type','arrear')->with('staff')->whereMonth('created_at',$month)->when(!empty($datatable_search), function ($query) use ($datatable_search) {
                   
            return $query->where(function ($q) use ($datatable_search) {
                $q->whereHas('staff', function($jq) use($datatable_search){
                $jq->where('name', 'like', "%{$datatable_search}%")
                ->orWhere('institute_emp_code', 'like', "%{$datatable_search}%");
                    });
            });
        })->orderby('created_at','desc')->get();
        if($request->ajax()){
        $datatables =  Datatables::of($data)
        ->addIndexColumn()
        ->editColumn('name', function ($row) { 
          
                    return $row['staff']->name ?? '';
        })->editColumn('emp_id', function ($row) {
                    return $row['staff']->institute_emp_code ?? '';
        })->editColumn('place', function ($row) {
                    return $row['staff']->appointment->work_place->name ?? '';
        })->editColumn('doj', function ($row) {
                    return $row['staff']->appointment->joining_date ?? '';
        })->editColumn('designation', function ($row) {
                    return $row['staff']->appointment->designation->name?? '';
        });
        return $datatables->make(true);
             }
        return view('pages.reports.arrers', compact('breadcrums','month'));
    }
    public function IncomeTax(Request $request)
    {
        $breadcrums = array(
            'title' => 'IncomeTax Report',
            'breadcrums' => array(
                array(
                    'link' => '', 'title' => 'IncomeTax Report'
                ),
            )
        );
        $datatable_search=$request->datatable_search;
        $month = $request->month ?? date('m');
        $data=ItStaffStatement::with('staff')->when(!empty($datatable_search), function ($query) use ($datatable_search) {
                   
            return $query->where(function ($q) use ($datatable_search) {
                $q->whereHas('staff', function($jq) use($datatable_search){
                $jq->where('name', 'like', "%{$datatable_search}%")
                ->orWhere('institute_emp_code', 'like', "%{$datatable_search}%");
                    });
            });
        })->where('academic_id',academicYearId())->whereMonth('created_at',$month)->orderby('created_at','desc')->get();

        if($request->ajax()){
        $datatables =  Datatables::of($data)
        ->addIndexColumn()
        ->editColumn('name', function ($row) { 
          
                    return $row['staff']->name ?? '';
        })->editColumn('emp_id', function ($row) {
                    return $row['staff']->institute_emp_code ?? '';
        })->editColumn('place', function ($row) {
                    return $row['staff']->appointment->work_place->name ?? '';
        })->editColumn('doj', function ($row) {
                    return $row['staff']->appointment->joining_date ?? '';
        })->editColumn('designation', function ($row) {
                    return $row['staff']->appointment->designation->name?? '';
        });
        return $datatables->make(true);
             }
        return view('pages.reports.incometax', compact('breadcrums','month'));
    }
    public function ProfessionalTax(Request $request){
            $breadcrums = array(
                'title' => 'ProfessionalTax Report',
                'breadcrums' => array(
                    array(
                        'link' => '', 'title' => 'ProfessionalTax Report'
                    ),
                )
            );
        $datatable_search=$request->datatable_search;
        $month = $request->month ?? date('m');
        $academic_info = AcademicYear::find(academicYearId());
       $year=$academic_info->from_year;
        $dates =  Carbon::now()->month($month)->year($year)->day(1)->format("Y-m-d");
        $data=[];
        $from_date = date('Y-m-01', strtotime($dates));
        $to_date = date('Y-m-t', strtotime($dates));
        $payroll = Payroll::where('from_date', $from_date)->where('to_date', $to_date)->where('institute_id',session()->get('staff_institute_id'))->first();
            $payroll_id = $payroll->id ?? '';
            if($payroll){
                $data = StaffSalary::with('staff')->when(!empty($payroll_id), function($query) use($payroll_id){
                                    $query->where('payroll_id', $payroll_id);
                                })->when(!empty($datatable_search), function ($query) use ($datatable_search) {
                       
                return $query->where(function ($q) use ($datatable_search) {
                    $q->whereHas('staff', function($jq) use($datatable_search){
                    $jq->where('name', 'like', "%{$datatable_search}%")
                    ->orWhere('institute_emp_code', 'like', "%{$datatable_search}%");
                        });
                });
            })->orderby('created_at','desc')->get();
            }         
            if($request->ajax()){
            $datatables =  Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($row) { 
              
                        return $row['staff']->name ?? '';
            })->editColumn('emp_id', function ($row) {
                        return $row['staff']->institute_emp_code ?? '';
            })->editColumn('place', function ($row) {
                        return $row['staff']->appointment->work_place->name ?? '';
            })->editColumn('doj', function ($row) {
                        return $row['staff']->appointment->joining_date ?? '';
            })->editColumn('designation', function ($row) {
                        return $row['staff']->appointment->designation->name?? '';
            });
            return $datatables->make(true);
        }
            return view('pages.reports.professionaltax', compact('breadcrums','month'));
        }
    

    public function SalaryAcquitance(Request $request)
    {
        $breadcrums = array(
            'title' => 'SalaryAcquitance Report',
            'breadcrums' => array(
                array(
                    'link' => '', 'title' => 'SalaryAcquitance Report'
                ),
            )
        );
        $datatable_search=$request->datatable_search;
        $earings_field = SalaryField::where('salary_head_id', 1)->where('nature_id', 3)->get();
        $deductions_field = SalaryField::where('salary_head_id', 2)
        ->where(function ($query) {
            $query->where('is_static', 'yes');
            $query->orWhere('nature_id', 3);
        })->get();

        $academic=AcademicYear::find(academicYearId());
        $month = $request->month ?? date('m');
        $year = $academic->from_year;
        $dates =  Carbon::now()->month($month)->year($year)->day(1)->format("Y-m-d");
        $data=[];
        $from_date = date('Y-m-01', strtotime($dates));
        $to_date = date('Y-m-t', strtotime($dates));
        $payroll = Payroll::where('from_date', $from_date)->where('to_date', $to_date)->where('institute_id',session()->get('staff_institute_id'))->first();
        $payroll_id = $payroll->id ?? '';
        if($payroll){
            $data = StaffSalary::with('staff')->when(!empty($payroll_id), function($query) use($payroll_id){
                $query->where('payroll_id', $payroll_id);
            })->when(!empty($datatable_search), function ($query) use ($datatable_search) {

                return $query->where(function ($q) use ($datatable_search) {
                    $q->whereHas('staff', function($jq) use($datatable_search){
                        $jq->where('name', 'like', "%{$datatable_search}%")
                        ->orWhere('institute_emp_code', 'like', "%{$datatable_search}%");
                    });
                });
            })->orderby('created_at','desc')->get();
        }         
        if($request->ajax()){
            $datatables =  Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($row) { 
                $name=$row['staff']->name ?? '';
                $designation=$row['staff']->appointment->designation->name ?? '';
                $join= $name.'/'. $designation;
                return $join;
            })->editColumn('aews', function ($row) {
                return $row['staff']->society_emp_code ?? '';
            })->editColumn('GROSS', function ($row) {
                return amountFormat($row->gross_salary);
            })->editColumn('Deduction in Gross (LOP)', function ($row) {
                return amountFormat($row->total_deductions);
            })->editColumn('Net Gross', function ($row) {
                return 0;
            })->editColumn('NET SALARY', function ($row) {
                return RsFormat($row->net_salary);
            });

            foreach($earings_field as $earing){
                if($earing->name=='Basic Pay'){
                    $datatables->addColumn('BASIC', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $earing->name));
                    });
                }
                if($earing->name=='Dearness Allowance'){
                    $datatables->addColumn('BASIC DA', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $earing->name));
                    });
                }
                if($earing->name=='House Rent Allowance'){
                    $datatables->addColumn('HRA', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $earing->name));
                    });
                }
                if($earing->name=='Traveling Allowance'){
                    $datatables->addColumn('TA', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $earing->name));
                    });
                }
                if($earing->name=='Performance Based Allowance'){
                    $datatables->addColumn('PBA', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $earing->name));
                    });
                }
                if($earing->name=='Performance Based Allowance Dearness Allowance'){
                    $datatables->addColumn('PBA DA', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $earing->name));
                    });
                }
                if($earing->name=='Dedication & Sincerity Allowance'){
                    $datatables->addColumn('DSA', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $earing->name));
                    });
                }
                if($earing->name=='Medical & Nutrition Allowance'){
                    $datatables->addColumn('MNA', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $earing->name));
                    });
                }
                if($earing->name=='ARREAR'){
                    $datatables->addColumn('ARR', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $earing->name));
                    });
                }
                if($earing->name=='Others'){
                    $datatables->addColumn('OTHERS', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $earing->name));
                    });
                }
                if($earing->name=='Bonus'){
                    $datatables->addColumn('Bonus', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $earing->name));
                    });
                }

            }
            foreach($deductions_field as $deduction){
                if($deduction->name=='Employee Provident Fund'){
                    $datatables->addColumn('EPF', function ($row) use($deduction) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS'));
                    });
                }
                if($deduction->name=='Life Insurance Corporation'){
                    $datatables->addColumn('LIC', function ($row) use($deduction) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS'));
                    });
                }
                if($deduction->name=="Employees' State Insurance"){
                    $datatables->addColumn('ESI', function ($row) use($deduction) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS'));
                    });
                }
                if($deduction->name=="Bank Loan"){
                    $datatables->addColumn('BANKLOAN', function ($row) use($deduction) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS'));
                    });
                }
                if($deduction->name=="Professional Tax"){
                    $datatables->addColumn('ProfTax', function ($row) use($deduction) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS'));
                    });
                }
                if($deduction->name=="Income Tax"){
                    $datatables->addColumn('Income Tax', function ($row) use($deduction) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS'));
                    });
                }
                if($deduction->name=="Others"){
                    $datatables->addColumn('OTHERS1', function ($row) use($deduction) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS'));
                    });
                }
                if($deduction->name=="OTHER LOAN"){
                    $datatables->addColumn('LOAN', function ($row) use($deduction) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS'));
                    });
                }
                if($deduction->name=="Contributions"){
                    $datatables->addColumn('DED', function ($row) use($deduction) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS'));
                    });
                }

            }
            return $datatables->make(true);
        }



        return view('pages.reports.salaryacquitance', compact('breadcrums','month','earings_field','deductions_field'));
    }
    public function SalaryAcquitanceRegister(Request $request)
    {
        $breadcrums = array(
            'title' => 'SalaryAcquitance Report',
            'breadcrums' => array(
                array(
                    'link' => '', 'title' => 'SalaryAcquitance Report'
                ),
            )
        );
        $datatable_search=$request->datatable_search;
        $earings_field = SalaryField::where('salary_head_id', 1)->where('nature_id', 3)->get();
        $deductions_field = SalaryField::where('salary_head_id', 2)
        ->where(function ($query) {
            $query->where('is_static', 'yes');
            $query->orWhere('nature_id', 3);
        })->get();

        $academic=AcademicYear::find(academicYearId());
        $month = $request->month ?? date('m');
        $year = $academic->from_year;
        $dates =  Carbon::now()->month($month)->year($year)->day(1)->format("Y-m-d");
        $data=[];
        $from_date = date('Y-m-01', strtotime($dates));
        $to_date = date('Y-m-t', strtotime($dates));
        $payroll = Payroll::where('from_date', $from_date)->where('to_date', $to_date)->where('institute_id',session()->get('staff_institute_id'))->first();
        $payroll_id = $payroll->id ?? '';
        if($payroll){
            $data = StaffSalary::with('staff')->when(!empty($payroll_id), function($query) use($payroll_id){
                $query->where('payroll_id', $payroll_id);
            })->when(!empty($datatable_search), function ($query) use ($datatable_search) {

                return $query->where(function ($q) use ($datatable_search) {
                    $q->whereHas('staff', function($jq) use($datatable_search){
                        $jq->where('name', 'like', "%{$datatable_search}%")
                        ->orWhere('institute_emp_code', 'like', "%{$datatable_search}%");
                    });
                });
            })->orderby('created_at','desc')->get();
        }         
        if($request->ajax()){
            $datatables =  Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($row) { 
                return $row['staff']->name ?? '';
                
            })->editColumn('designation', function ($row) {
                return $row['staff']->appointment->designation->name ?? '';
            })->editColumn('category', function ($row) {
                
                return $row['staff']->appointment->staffCategory->name ?? '';
            })->editColumn('doj', function ($row) {
                    return $row['staff']->appointment->joining_date ?? '';
           })->editColumn('division', function ($row) {
                    return $row['staff']->position->division->name ?? '';
           })->editColumn('aews', function ($row) {
                return $row['staff']->society_emp_code ?? '';
            })->editColumn('Bank', function ($row) {
                return $row['staff']->bank->bankBranch->name ?? '';
            })->editColumn('UAN', function ($row) {
                return $row['staff']->pf->ac_number?? '';
            })->editColumn('UAN Name', function ($row) {
               return $row['staff']->pf->name?? '';
            })->editColumn('ESI No', function ($row) {
                return $row['staff']->esi
->ac_number?? '';
            })->editColumn('ESI Name', function ($row) {
               return $row['staff']->esi
->name?? '';
            })->editColumn('PAN', function ($row) {

                return $row['staff']->pancard
->doc_number?? '';
            })->editColumn('PAN Name', function ($row) {
               return $row['staff']->pancard
->description?? '';
            })->editColumn('Aadhaar Name', function ($row) {
                
                return $row['staff']->aadhaar
->description?? '';
            })->editColumn('Aadhaar No', function ($row) {
               return $row['staff']->aadhaar
->doc_number?? '';
            })->editColumn('Mobile', function ($row) {
               return $row['staff']->personal
->mobile_no1?? '';
            })->editColumn('Email', function ($row) {
               return $row['staff']
->email?? '';
            })->editColumn('ifsc_code', function ($row) {
                return $row['staff']->bank->bankBranch->ifsc_code ?? '';
            })->editColumn('account_number', function ($row) {

                return $row['staff']->bank->account_number ?? '';
            })->editColumn('GROSS', function ($row) {
                return amountFormat($row->gross_salary);
            })->editColumn('Deduction in Gross (LOP)', function ($row) {
                return amountFormat($row->total_deductions);
            })->editColumn('Net Gross', function ($row) {
                return 0;
            })->editColumn('NET SALARY', function ($row) {
                return RsFormat($row->net_salary);
            });

            foreach($earings_field as $earing){
                if($earing->name=='Basic Pay'){
                    $datatables->addColumn('BASIC', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $earing->name));
                    });
                }
                if($earing->name=='Dearness Allowance'){
                    $datatables->addColumn('BASIC DA', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $earing->name));
                    });
                }
                if($earing->name=='House Rent Allowance'){
                    $datatables->addColumn('HRA', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $earing->name));
                    });
                }
                if($earing->name=='Traveling Allowance'){
                    $datatables->addColumn('TA', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $earing->name));
                    });
                }
                if($earing->name=='Performance Based Allowance'){
                    $datatables->addColumn('PBA', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $earing->name));
                    });
                }
                if($earing->name=='Performance Based Allowance Dearness Allowance'){
                    $datatables->addColumn('PBA DA', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $earing->name));
                    });
                }
                if($earing->name=='Dedication & Sincerity Allowance'){
                    $datatables->addColumn('DSA', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $earing->name));
                    });
                }
                if($earing->name=='Medical & Nutrition Allowance'){
                    $datatables->addColumn('MNA', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $earing->name));
                    });
                }
                if($earing->name=='ARREAR'){
                    $datatables->addColumn('ARR', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $earing->name));
                    });
                }
                if($earing->name=='Others'){
                    $datatables->addColumn('OTHERS', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $earing->name));
                    });
                }
                if($earing->name=='Bonus'){
                    $datatables->addColumn('Bonus', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $earing->name));
                    });
                }

            }
            foreach($deductions_field as $deduction){
                if($deduction->name=='Employee Provident Fund'){
                    $datatables->addColumn('EPF', function ($row) use($deduction) 
                    {

                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS'));
                    });
                }
                if($deduction->name=='Life Insurance Corporation'){
                    $datatables->addColumn('LIC', function ($row) use($deduction) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS'));
                    });
                }
                if($deduction->name=="Employees' State Insurance"){
                    $datatables->addColumn('ESI', function ($row) use($deduction) 
                    {

                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS'));
                    });
                }
                if($deduction->name=="Bank Loan"){
                    $datatables->addColumn('BANKLOAN', function ($row) use($deduction) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS'));
                    });
                }
                if($deduction->name=="Professional Tax"){
                    $datatables->addColumn('ProfTax', function ($row) use($deduction) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS'));
                    });
                }
                if($deduction->name=="Income Tax"){
                    $datatables->addColumn('Income Tax', function ($row) use($deduction) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS'));
                    });
                }
                if($deduction->name=="Others"){
                    $datatables->addColumn('OTHERS1', function ($row) use($deduction) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS'));
                    });
                }
                if($deduction->name=="OTHER LOAN"){
                    $datatables->addColumn('LOAN', function ($row) use($deduction) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS'));
                    });
                }
                if($deduction->name=="Contributions"){
                    $datatables->addColumn('DED', function ($row) use($deduction) 
                    {
                        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS'));
                    });
                }

            }
            return $datatables->make(true);
        }



        return view('pages.reports.register', compact('breadcrums','month','earings_field','deductions_field'));
    }
    public function EPF(Request $request)
    {
        $breadcrums = array(
            'title' => 'EPF Report',
            'breadcrums' => array(
                array(
                    'link' => '', 'title' => 'EPF Report'
                ),
            )
        );

        $datatable_search=$request->datatable_search;
               $academic=AcademicYear::find(academicYearId());
        $month = $request->month ?? date('m');
        $year = $academic->from_year;
        $dates =  Carbon::now()->month($month)->year($year)->day(1)->format("Y-m-d");
        $data=[];
        $from_date = date('Y-m-01', strtotime($dates));
        $to_date = date('Y-m-t', strtotime($dates));
        $payroll = Payroll::where('from_date', $from_date)->where('to_date', $to_date)->where('institute_id',session()->get('staff_institute_id'))->first();
        $payroll_id = $payroll->id ?? '';
        if($payroll){
            $data = StaffSalary::with('staff')->when(!empty($payroll_id), function($query) use($payroll_id){
                $query->where('payroll_id', $payroll_id);
            })->when(!empty($datatable_search), function ($query) use ($datatable_search) {

                return $query->where(function ($q) use ($datatable_search) {
                    $q->whereHas('staff', function($jq) use($datatable_search){
                        $jq->where('name', 'like', "%{$datatable_search}%")
                        ->orWhere('institute_emp_code', 'like', "%{$datatable_search}%");
                    });
                });
            })->orderby('created_at','desc')->get();
        }   
        if($request->ajax()){
        $datatables =  Datatables::of($data)
        ->addIndexColumn()
        ->editColumn('pf_no', function ($row) {
                 return $row['staff']->pf->ac_number ?? '-';

        })
        ->editColumn('name', function ($row) { 
          
            return $row['staff']->name ?? '';
        })->editColumn('gross_wages', function ($row) {
        return amountFormat(getStaffSalaryFieldAmount($row['staff']->id,$row->id,'',"Employee Provident Fund",'DEDUCTIONS'));
        });
        return $datatables->make(true);
             }
        return view('pages.reports.epf', compact('breadcrums','month'));
    }
    public function MonthWiseVariation(Request $request)
    {
        $breadcrums = array(
            'title' => 'Month Wise Variation Report',
            'breadcrums' => array(
                array(
                    'link' => '', 'title' => 'Month Wise Variation Report'
                ),
            )
        );
        ini_set('memory_limit', '   10000M');
        $datatable_search=$request->datatable_search;
               $academic=AcademicYear::find(academicYearId());
         $earings_field = SalaryField::where('salary_head_id', 1)->where('nature_id', 3)->get();
        $deductions_field = SalaryField::where('salary_head_id', 2)
        ->where(function ($query) {
            $query->where('is_static', 'yes');
            $query->orWhere('nature_id', 3);
         })->get();
        $month = $request->month ?? date('m');
        $year = $academic->from_year;
        $dates =  Carbon::now()->month($month)->year($year)->day(1)->format("Y-m-d");
        $from_date = date('Y-m-01', strtotime($dates));
            $data = StaffSalaryPattern::with('staff')->where('institute_id',session()->get('staff_institute_id'))->where('payout_month', $from_date
        )->when(!empty($datatable_search), function ($query) use ($datatable_search) {

                return $query->where(function ($q) use ($datatable_search) {
                    $q->whereHas('staff', function($jq) use($datatable_search){
                        $jq->where('name', 'like', "%{$datatable_search}%")
                        ->orWhere('institute_emp_code', 'like', "%{$datatable_search}%");
                    });
                });
        })->orderby('created_at','desc')->get();
        if($request->ajax()){
            $datatables =  Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($row) { 
                return $row['staff']->name ?? '';
                
            })->editColumn('designation', function ($row) {
                return $row['staff']->appointment->designation->name ?? '';
            })->editColumn('Bank', function ($row) {
                return $row['staff']->bank->bankBranch->name ?? '';
            })->editColumn('ESI No', function ($row) {
                return $row['staff']->esi
->ac_number?? '';
            })->editColumn('ESI Name', function ($row) {
               return $row['staff']->esi
->name?? '';
            })->editColumn('ifsc_code', function ($row) {
                return $row['staff']->bank->bankBranch->ifsc_code ?? '';
            })->editColumn('account_number', function ($row) {

                return $row['staff']->bank->account_number ?? '';
            })->editColumn('GROSS', function ($row) {
                return amountFormat($row->gross_salary);
            })->editColumn('Deduction in Gross (LOP)', function ($row) {
                return amountFormat($row->total_deductions);
            })->editColumn('Net Gross', function ($row) {
                return 0;
            })->editColumn('NET SALARY', function ($row) {
                return RsFormat($row->net_salary);
            });

            foreach($earings_field as $earing){
                if($earing->name=='Basic Pay'){
                    $datatables->addColumn('BASIC', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffPatterFieldAmount($row['staff']->id, $row->id, '', $earing->name,'EARNINGS',''));
                    });
                }
                if($earing->name=='Dearness Allowance'){
                    $datatables->addColumn('BASIC DA', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffPatterFieldAmount($row['staff']->id, $row->id, '', $earing->name,'EARNINGS',''));
                    });
                }
                if($earing->name=='House Rent Allowance'){
                    $datatables->addColumn('HRA', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffPatterFieldAmount($row['staff']->id, $row->id, '', $earing->name,'EARNINGS',''));
                    });
                }
                if($earing->name=='Traveling Allowance'){
                    $datatables->addColumn('TA', function ($row) use($earing) 
                    {
                         return amountFormat(getStaffPatterFieldAmount($row['staff']->id, $row->id, '', $earing->name,'EARNINGS',''));
                    });
                }
                if($earing->name=='Performance Based Allowance'){
                    $datatables->addColumn('PBA', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffPatterFieldAmount($row['staff']->id, $row->id, '', $earing->name,'EARNINGS',''));
                    });
                }
                if($earing->name=='Performance Based Allowance Dearness Allowance'){
                    $datatables->addColumn('PBA DA', function ($row) use($earing) 
                    {
                         return amountFormat(getStaffPatterFieldAmount($row['staff']->id, $row->id, '', $earing->name,'EARNINGS',''));
                    });
                }
                if($earing->name=='Dedication & Sincerity Allowance'){
                    $datatables->addColumn('DSA', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffPatterFieldAmount($row['staff']->id, $row->id, '', $earing->name,'EARNINGS',''));
                    });
                }
                if($earing->name=='Medical & Nutrition Allowance'){
                    $datatables->addColumn('MNA', function ($row) use($earing) 
                    {
                         return amountFormat(getStaffPatterFieldAmount($row['staff']->id, $row->id, '', $earing->name,'EARNINGS',''));
                    });
                }
                if($earing->name=='ARREAR'){
                    $datatables->addColumn('ARR', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffPatterFieldAmount($row['staff']->id, $row->id, '', $earing->name,'EARNINGS',''));
                    });
                }
                if($earing->name=='Others'){
                    $datatables->addColumn('OTHERS', function ($row) use($earing) 
                    {
                        return amountFormat(getStaffPatterFieldAmount($row['staff']->id, $row->id, '', $earing->name,'EARNINGS',''));
                    });
                }
                if($earing->name=='Bonus'){
                    $datatables->addColumn('Bonus', function ($row) use($earing) 
                    {
                         return amountFormat(getStaffPatterFieldAmount($row['staff']->id, $row->id, '', $earing->name,'EARNINGS',''));
                    });
                }

            }
            foreach($deductions_field as $deduction){
                if($deduction->name=='Employee Provident Fund'){
                    $datatables->addColumn('EPF', function ($row) use($deduction) 
                    {
                         return amountFormat(getStaffPatterFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS',''));

                       
                    });
                }
                if($deduction->name=='Life Insurance Corporation'){
                    $datatables->addColumn('LIC', function ($row) use($deduction) 
                    {
                        return amountFormat(getStaffPatterFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS',''));
                    });
                }
                if($deduction->name=="Employees' State Insurance"){
                    $datatables->addColumn('ESI', function ($row) use($deduction) 
                    {

                       return amountFormat(getStaffPatterFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS',''));
                    });
                }
                if($deduction->name=="Bank Loan"){
                    $datatables->addColumn('BANKLOAN', function ($row) use($deduction) 
                    {
                       return amountFormat(getStaffPatterFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS',''));
                    });
                }
                if($deduction->name=="Professional Tax"){
                    $datatables->addColumn('ProfTax', function ($row) use($deduction) 
                    {
                        return amountFormat(getStaffPatterFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS',''));
                    });
                }
                if($deduction->name=="Income Tax"){
                    $datatables->addColumn('Income Tax', function ($row) use($deduction) 
                    {
                       return amountFormat(getStaffPatterFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS',''));
                    });
                }
                if($deduction->name=="Others"){
                    $datatables->addColumn('OTHERS1', function ($row) use($deduction) 
                    {
                       return amountFormat(getStaffPatterFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS',''));
                    });
                }
                if($deduction->name=="OTHER LOAN"){
                    $datatables->addColumn('LOAN', function ($row) use($deduction) 
                    {
                        return amountFormat(getStaffPatterFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS',''));
                    });
                }
                if($deduction->name=="Contributions"){
                    $datatables->addColumn('DED', function ($row) use($deduction) 
                    {
                       return amountFormat(getStaffPatterFieldAmount($row['staff']->id, $row->id, '', $deduction->name,'DEDUCTIONS',''));
                    });
                }

            }
            return $datatables->make(true);
        }
        return view('pages.reports.monthwisechanges', compact('breadcrums','month'));
    }
    public function LeaveReport(Request $request){
    $breadcrums = array(
    'title' => 'Leave Report',
    'breadcrums' => array(
    array(
    'link' => '', 'title' => 'Leave Report'
    ),
    )
    );
    $datatable_search=$request->datatable_search;
    $month = $request->month ?? date('m');
    $data=[];
    $institute_id=session()->get('staff_institute_id');
        $data = User::with('leaves')
    ->InstituteBased()->when(!empty($month), function ($query) use ($month) {
        $query->whereHas('leaves', function($q) use($month){
       $q->whereMonth('from_date', $month)->orwhereMonth('to_date', $month);
        $q->where('status','approved');
        });  
        })->when(!empty($datatable_search), function ($query) use ($datatable_search) {

                return $query->where(function ($q) use ($datatable_search) {
                $q->where('name', 'like', "%{$datatable_search}%")
            ->orWhere('institute_emp_code', 'like', "%{$datatable_search}%");
                    
                });
        });
              
    if($request->ajax()){
        $datatables =  Datatables::of($data)
        ->addIndexColumn()
        ->editColumn('name', function ($row) { 

            return $row->name ?? '';
        })->editColumn('emp_id', function ($row) {
            return $row->institute_emp_code ?? '';
        })->editColumn('doj', function ($row) {
            return $row['staff_info']->appointment->joining_date ?? '';
        })->editColumn('designation', function ($row) {
            return $row->appointment->designation->name?? '';
        })->editColumn('cl_eligible', function ($row) {

            return $row->appointment->employment_nature->cl->leave_days??0;
        })->editColumn('cl_availed', function ($row) {
            $cl=0;
                 foreach($row->leaves as $leave){
                     if($leave->leave_category=='Casual Leave'){

                        $cl +=$leave->granted_days;
                     }
                 }
            return $cl ?? 0;
        })->editColumn('cl_balance', function ($row) {
            $cl=0;
                 foreach($row->leaves as $leave){
                     if($leave->leave_category=='Casual Leave'){
                       
                        $cl +=$leave->granted_days;
                     }
                 }
            return ($row->appointment->employment_nature->cl->leave_days-$cl) ?? 0;
        })->editColumn('gl_sanctioned', function ($row) {
                
            return $row->appointment->employment_nature->gl->leave_days??0;
        })->editColumn('gl_availed', function ($row) {
                 $gl=0;
                 foreach($row->leaves as $leave){
                     if($leave->leave_category=='Maternity Leave'){
                       
                        $gl +=$leave->granted_days;
                     }
                 }
            return $gl ??0;
        })->editColumn('el_availed', function ($row) {
                 $el=0;
                 foreach($row->leaves as $leave){
                     if($leave->leave_category=='Earned Leave'){
                       
                        $el +=$leave->granted_days;
                     }
                 }
            return $el ??0;
        })->editColumn('el_balance', function ($row) {
                 $el=0;
                 foreach($row->leaves as $leave){
                     if($leave->leave_category=='Earned Leave'){
                       
                        $el +=$leave->granted_days;
                     }
                 }
                 if(isset($row->appointment->employment_nature->gl->leave_days)){
                     return ($row->appointment->employment_nature->gl->leave_days- $el) ??0;
                 }else{
                    return $el ??0;
                 }
           
        })->editColumn('ml_eligible', function ($row) {
               
                 
            return $row->appointment->employment_nature->ml->leave_days  ?? 0;
        })->editColumn('ml_availed', function ($row) {
                $ml=0;
                 foreach($row->leaves as $leave){
                     if($leave->leave_category=='Maternity Leave'){
                       
                        $ml +=$leave->granted_days;
                     }
                 }
                 
            return $ml  ?? 0;
        })->editColumn('ml_balance', function ($row) {
                $ml=0;
                 foreach($row->leaves as $leave){
                     if($leave->leave_category=='Maternity Leave'){
                       
                        $ml +=$leave->granted_days;
                     }
                 }
                 if(isset($row->appointment->employment_nature->ml->leave_days)){
                     return ($row->appointment->employment_nature->ml->leave_days- $ml) ??0;
                 }else{
                    return $ml ??0;
                 }
           
        })->editColumn('eol_availed', function ($row) {
                $eol=0;
                 foreach($row->leaves as $leave){
                     if($leave->leave_category=='Extra Ordinary Leave'){
                       
                        $eol +=$leave->granted_days;
                     }
                 }
                 
            return $eol ??0;
        })->editColumn('eol_amount', function ($row) {  
            return '';
        })->editColumn('eol_reason', function ($row) {   
             return '';
        });
        return $datatables->make(true);
    }
    return view('pages.reports.leave_report', compact('breadcrums','month'));
}
    public function BankDisbursement(Request $request){
        $breadcrums = array(
        'title' => 'Bank Disbursement Report',
        'breadcrums' => array(
        array(
        'link' => '', 'title' => 'Bank Disbursement Report'
        ),
        )
        );
        $datatable_search=$request->datatable_search;
        $month = $request->month ?? date('m');
        $academic_info = AcademicYear::find(academicYearId());
        $year=$academic_info->from_year;
        $dates =  Carbon::now()->month($month)->year($year)->day(1)->format("Y-m-d");
        $data=[];
        $from_date = date('Y-m-01', strtotime($dates));
        $to_date = date('Y-m-t', strtotime($dates));
        $payroll = Payroll::where('from_date', $from_date)->where('to_date', $to_date)->where('institute_id',session()->get('staff_institute_id'))->first();
        $payroll_id = $payroll->id ?? '';
        if($payroll_id){
            $data = StaffSalary::with('staff')->when(!empty($payroll_id), function($query) use($payroll_id){
                $query->where('payroll_id', $payroll_id);
            })->when(!empty($datatable_search), function ($query) use ($datatable_search) {

                return $query->where(function ($q) use ($datatable_search) {
                    $q->whereHas('staff', function($jq) use($datatable_search){
                        $jq->where('name', 'like', "%{$datatable_search}%")
                        ->orWhere('institute_emp_code', 'like', "%{$datatable_search}%");
                    });
                });
            })->orderby('created_at','desc')->get();
           

        }         
        if($request->ajax()){
            $datatables =  Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('staff_name', function ($row) { 

                return $row['staff']->name ?? '';
            })->editColumn('bank_code', function ($row) { 
          if(isset($row['staff']->bank->bankDetails)){
            if($row['staff']->bank->bankDetails->name=="AXIS BANK"){
                           return 'I';
                       }else{
                            return 'N';
                       }
                   }
                 return '';
            })->editColumn('account_number', function ($row) { 
          return $row['staff']->bank->account_number ?? '';
            })->editColumn('ifsc_code', function ($row) { 
                 return $row['staff']->bank->bankBranch->ifsc_code ?? '';
            })->editColumn('remarks', function ($row) { 

                 return '';
            })->editColumn('bulk_upload', function ($row) { 

                 return '10';
            })->editColumn('school_account_number', function ($row) { 

                 return '';
            });
            return $datatables->make(true);
        }
        return view('pages.reports.bankdisbursement', compact('breadcrums','month'));
    }


}

