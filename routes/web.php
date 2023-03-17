<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('layouts.template');
// });

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/open/modal', [App\Http\Controllers\CommonController::class, 'openAddModal'])->name('modal.open');

Route::post('/save/institution', [App\Http\Controllers\Master\InstitutionController::class, 'save'])->name('save.institute');
Route::post('/get/institution/staff/code', [App\Http\Controllers\Master\InstitutionController::class, 'getInstituteStaffCode'])->name('institute.staff.code');

Route::post('/save/division', [App\Http\Controllers\Master\DivisionController::class, 'save'])->name('save.division');
Route::post('/save/class', [App\Http\Controllers\Master\ClassesController::class, 'save'])->name('save.class');
Route::post('/save/language', [App\Http\Controllers\Master\LanguageController::class, 'save'])->name('save.language');
Route::post('/save/place', [App\Http\Controllers\Master\PlaceController::class, 'save'])->name('save.place');
Route::post('/save/nationality', [App\Http\Controllers\Master\NationalityController::class, 'save'])->name('save.nationality');
Route::post('/save/religion', [App\Http\Controllers\Master\ReligionController::class, 'save'])->name('save.religion');
Route::post('/save/caste', [App\Http\Controllers\Master\CasteController::class, 'save'])->name('save.caste');
Route::post('/save/community', [App\Http\Controllers\Master\CommunityController::class, 'save'])->name('save.community');
Route::post('/save/bank', [App\Http\Controllers\Master\BankController::class, 'save'])->name('save.bank');
Route::post('/save/branch', [App\Http\Controllers\Master\BankBranchController::class, 'save'])->name('save.branch');
Route::post('/get/branch', [App\Http\Controllers\Master\BankBranchController::class, 'getBankBranches'])->name('branch.all');

Route::get('staff/register/{id?}', [App\Http\Controllers\StaffController::class, 'register'])->name('staff.register');
Route::post('staff/add/personal', [App\Http\Controllers\StaffController::class, 'insertPersonalData'])->name('staff.save.personal');
Route::post('staff/add/kyc', [App\Http\Controllers\StaffController::class, 'insertKycData'])->name('staff.save.kyc');
Route::post('staff/add/position', [App\Http\Controllers\StaffController::class, 'insertEmployeePosition'])->name('staff.save.employee_position');
Route::post('staff/add/education', [App\Http\Controllers\StaffController::class, 'insertEducationDetails'])->name('staff.save.education_details');
Route::post('staff/get/draftData', [App\Http\Controllers\StaffController::class, 'getStaffDraftData'])->name('staff.get.draft.data');


Route::post('/save/designation', [App\Http\Controllers\Master\DesignationController::class, 'save'])->name('save.designation');
Route::post('/save/department', [App\Http\Controllers\Master\DepartmentController::class, 'save'])->name('save.department');
Route::post('/save/subject', [App\Http\Controllers\Master\SubjectController::class, 'save'])->name('save.subject');
Route::post('/save/scheme', [App\Http\Controllers\Master\AttendanceSchemeController::class, 'save'])->name('save.scheme');
Route::post('/save/duty/class', [App\Http\Controllers\Master\DutyClassController::class, 'save'])->name('save.duty.class');
Route::post('/save/duty/type', [App\Http\Controllers\Master\DutyTypeController::class, 'save'])->name('save.duty.type');
Route::post('/save/other/school', [App\Http\Controllers\Master\OtherSchoolController::class, 'save'])->name('save.other.school');

Route::post('/save/invigilation', [App\Http\Controllers\Staff\InvigilationDutyController::class, 'save'])->name('save.invigilation');
Route::post('/form/invigilation/content', [App\Http\Controllers\Staff\InvigilationDutyController::class, 'formContent'])->name('form.invigilation.content');
Route::post('/invigilation/list', [App\Http\Controllers\Staff\InvigilationDutyController::class, 'getStaffInvigilationList'])->name('staff.invigilation.list');
Route::post('/invigilation/delete', [App\Http\Controllers\Staff\InvigilationDutyController::class, 'deleteStaffInvigilation'])->name('staff.invigilation.delete');

Route::post('/save/training/topic', [App\Http\Controllers\Master\TrainingTopicController::class, 'save'])->name('save.training.topic');
Route::post('/save/staff/training', [App\Http\Controllers\Staff\StaffTrainingController::class, 'save'])->name('save.staff.training');
Route::post('/form/training/content', [App\Http\Controllers\Staff\StaffTrainingController::class, 'formContent'])->name('form.training.content');
Route::post('/training/list', [App\Http\Controllers\Staff\StaffTrainingController::class, 'getStaffTrainingList'])->name('staff.training.list');
Route::post('/training/delete', [App\Http\Controllers\Staff\StaffTrainingController::class, 'deleteStaffTraining'])->name('staff.training.delete');

Route::post('/save/board', [App\Http\Controllers\Master\BoardController::class, 'save'])->name('save.board');
Route::post('/save/type', [App\Http\Controllers\Master\ProfessionTypeController::class, 'save'])->name('save.type');

Route::post('/save/staff/education', [App\Http\Controllers\Staff\StaffEducationController::class, 'save'])->name('save.staff.course');
