<!--begin::Navbar-->
@extends('layouts.template')
@section('content')
    <!--begin::Card-->
    <div class="card">
        <div class="card-body py-4">
            <form action="{{route('permission.save')}}" method="post" class="was-validated" id="dynamic_form">
                @csrf
                <input type="hidden" name="id" value="{{ $info->id ?? '' }}">
                <input type="hidden" name="form_type" id="form_type" value="{{ $from ?? '' }}">
                <div class="row">
                    <div class="col-sm-4  ">
                        <div class="fv-row form-group mb-10 ">
                            <label class="form-label required" for="">
                             Select Role
                            </label>
                            <div > 
                                <select name="role_id"  required  class="form-control" onchange="return permission_table_show();" id="role_id">
                                    <option value="">--Select Role--</option>
                                    @foreach ($role as $key=>$val)
                                    <option value="{{ $val->id }}" @if(isset($info->staff_id) && $info->staff_id == $val->id) selected @endif>{{ $val['name']  }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Must Select Role</div>
                        </div>
                    </div>
                    </div>               
          
            <!--begin::Table-->
            <div id="kt_table_users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="display:none;">
               
                <div class="row">
                      
        <div class="container">
            <!-- Account Menu Checkbox Start -->
            <div class="col-12 pb-3">
                <h4> <strong>Account Menu</strong>
                 <input type="checkbox"  name="account_select_all" id="account_select_all" onclick="account_select_all_func();"></h4>
               </div>
               <div class="row justify-content-start">                     
                <div class="row mb-6">
                    <div class="col-1">
                        <b>Sl.No</b>
                    </div>
                    <div class="col-1"></div>
                    <div class="col-5">
                        <strong>Menu</strong>
                    </div>
                    <div class="col-1">
                        <strong>Add</strong>
                    </div>
                    <div class="col-1">
                        <strong> Edit</strong>
                    </div>
                    <div class="col-1">
                        <strong>View</strong>
                    </div>
                    <div class="col-1">
                        <strong>Delete</strong>
                    </div>
                    <div class="col-1">
                        <strong>Export</strong>
                    </div> 
                </div>
                @php
                    $acc_i=1;
                    $account_row=0;
                @endphp
                @foreach($account as $key => $account_menu)               
                <div class="row mb-6"> 
                    <div class="col-1">
                        {{ $acc_i}}
                    </div>
                    <div class="col-1">
                        <input type="checkbox" class="account_checkox" name="account_select_all_row_wise_{{$acc_i}}" id="account_select_all_row_wise_{{$acc_i}}" onclick="account_row({{$acc_i}});">
                    </div>
                    <div class="col-5">                  
                    <span class="pl-3"> {{$account_menu}}</span>
                    <input type="hidden" name="account_menu_name[]" id="account_menu_name" value="{{$key}}">                   
                    </div>
                    <div class="col-1">
                        <input type="checkbox" class="account_checkox" name="account_add[]" value="{{$key}}_account_add" id="account_add_{{$acc_i}}">
                    </div>
                    <div class="col-1">
                        <input type="checkbox" class="account_checkox" name="account_edit[]"  value="{{$key}}_account_edit" id="account_edit_{{$acc_i}}" >
                    </div>
                    <div class="col-1">
                        <input type="checkbox" class="account_checkox" name="account_view[]"  value="{{$key}}_account_view" id="account_view_{{$acc_i}}">
                    </div>
                    <div class="col-1">
                        <input type="checkbox" class="account_checkox" name="account_delete[]" value="{{$key}}_account_delete" id="account_delete_{{$acc_i}}">
                    </div>
                    <div class="col-1">
                        <input type="checkbox" class="account_checkox" name="account_export[]"  value="{{$key}}_account_export" id="account_export_{{$acc_i}}">
                    </div>    
                </div>
            @php
            $acc_i++;
            $account_row++;
            @endphp           
            @endforeach
            <input type="hidden" name="account_row_count" id="account_row_count" value="{{$account_row}}">
                    </div> 
    <br>
       <!-- Account Menu Checkbox End -->

    <!-- Authentication Menu Checkbox Start -->
    <div class="col-12 pb-3">
    <h4> <strong>Authentication Menu</strong>
     <input type="checkbox"  name="auth_select_all" id="auth_select_all" onclick="auth_select_all_func();"></h4>
   </div>
   <div class="row justify-content-start">                     
    <div class="row mb-6">
        <div class="col-1">
            <b>Sl.No</b>
        </div>
        <div class="col-1"></div>
        <div class="col-5">
            <strong>Menu</strong>
        </div>
        <div class="col-1">
            <strong>Add</strong>
        </div>
        <div class="col-1">
            <strong> Edit</strong>
        </div>
        <div class="col-1">
            <strong>View</strong>
        </div>
        <div class="col-1">
            <strong>Delete</strong>
        </div>
        <div class="col-1">
            <strong>Export</strong>
        </div> 
    </div>
    @php
        $auth_i=1;
        $auth_row=0;
    @endphp
    @foreach($authentication as $key => $authentication_menu)               
    <div class="row mb-6"> 
        <div class="col-1">
            {{ $auth_i}}
        </div>
        <div class="col-1">
            <input type="checkbox" class="auth_checkox" name="auth_select_all_row_wise_{{$auth_i}}" id="auth_select_all_row_wise_{{$auth_i}}" onclick="auth_row({{$auth_i}});">
        </div>
        <div class="col-5">                  
        <span class="pl-3"> {{$authentication_menu}}</span>
        <input type="hidden" name="auth_menu_name[]" id="auth_menu_name" value="{{$key}}">                   
        </div>
        <div class="col-1">
            <input type="checkbox" class="auth_checkox" name="auth_add[]"  value="0" id="auth_add_{{$auth_i}}">
        </div>
        <div class="col-1">
            <input type="checkbox" class="auth_checkox" name="auth_edit[]"  value="0" id="auth_edit_{{$auth_i}}" >
        </div>
        <div class="col-1">
            <input type="checkbox" class="auth_checkox" name="auth_view[]"  value="0" id="auth_view_{{$auth_i}}">
        </div>
        <div class="col-1">
            <input type="checkbox" class="auth_checkox" name="auth_delete[]"  value="0" id="auth_delete_{{$auth_i}}">
        </div>
        <div class="col-1">
            <input type="checkbox" class="auth_checkox" name="auth_export[]"  value="0" id="auth_export_{{$auth_i}}">
        </div>    
    </div>
@php
$auth_i++;
$auth_row++;
@endphp           
@endforeach
<input type="hidden" name="auth_row_count" id="auth_row_count" value="{{$auth_row}}">
        </div> 
    <br>
     <!-- Authentication Menu Checkbox End -->
 
     <!-- Staff Management Menu Checkbox Start -->

    <div class="col-12 pb-3">
        <h4> <strong>Staff Management</strong>
         <input type="checkbox"  name="staff_select_all" id="staff_select_all" onclick="staff_select_all_func();"></h4>
       </div>
       <div class="row justify-content-start">                     
         <div class="row mb-6">
             <div class="col-1">
                 <b>Sl.No</b>
             </div>
             <div class="col-1"></div>
             <div class="col-5">
                 <strong>Menu</strong>
             </div>
             <div class="col-1">
                 <strong>Add</strong>
             </div>
             <div class="col-1">
                 <strong> Edit</strong>
             </div>
             <div class="col-1">
                 <strong>View</strong>
             </div>
             <div class="col-1">
                 <strong>Delete</strong>
             </div>
             <div class="col-1">
                 <strong>Export</strong>
             </div> 
         </div>
         @php
             $si=1;
             $staff_row=0;
         @endphp
         @foreach($staff_management as $key => $staff_managements)               
         <div class="row mb-6"> 
             <div class="col-1">
                 {{ $si}}
             </div>
             <div class="col-1">
                 <input type="checkbox" class="staff_checkox" name="staff_select_all_row_wise_{{$si}}" id="staff_select_all_row_wise_{{$si}}" onclick="staff_row({{$si}});">
             </div>
             <div class="col-5">                  
             <span class="pl-3"> {{$staff_managements}}</span>
             <input type="hidden" name="staff_menu_name[]" id="staff_menu_name" value="{{$key}}">                   
             </div>
             <div class="col-1">
                 <input type="checkbox" class="staff_checkox" name="staff_add[]"  value="0" id="staff_add_{{$si}}">
             </div>
             <div class="col-1">
                 <input type="checkbox" class="staff_checkox" name="staff_edit[]"  value="0" id="staff_edit_{{$si}}" >
             </div>
             <div class="col-1">
                 <input type="checkbox" class="staff_checkox" name="staff_view[]"  value="0" id="staff_view_{{$si}}">
             </div>
             <div class="col-1">
                 <input type="checkbox" class="staff_checkox" name="staff_delete[]"  value="0" id="staff_delete_{{$si}}">
             </div>
             <div class="col-1">
                 <input type="checkbox" class="staff_checkox" name="staff_export[]"  value="0" id="staff_export_{{$si}}">
             </div>    
         </div>
     @php
     $si++;
     $staff_row++;
     @endphp           
     @endforeach
     <input type="hidden" name="staff_row_count" id="staff_row_count" value="{{$staff_row}}">
</div> <br>
  <!-- Staff Management Menu Checkbox End -->

<!-- Document Locker Menu Checkbox Start -->

    <div class="col-12 pb-3">
        <h4> <strong>Document Locker</strong>
         <input type="checkbox"  name="dl_select_all" id="dl_select_all" onclick="dl_select_all_func();"></h4>
       </div>
       <div class="row justify-content-start">                     
         <div class="row mb-6">
             <div class="col-1">
                 <b>Sl.No</b>
             </div>
             <div class="col-1"></div>
             <div class="col-5">
                 <strong>Menu</strong>
             </div>
             <div class="col-1">
                 <strong>Add</strong>
             </div>
             <div class="col-1">
                 <strong> Edit</strong>
             </div>
             <div class="col-1">
                 <strong>View</strong>
             </div>
             <div class="col-1">
                 <strong>Delete</strong>
             </div>
             <div class="col-1">
                 <strong>Export</strong>
             </div> 
         </div>
         @php
             $dli=1;
             $dl_row=0;
         @endphp
         @foreach($document_locker as $key => $document_lockers)               
         <div class="row mb-6"> 
             <div class="col-1">
                 {{ $dli}}
             </div>
             <div class="col-1">
                 <input type="checkbox" class="dl_checkox" name="dl_select_all_row_wise_{{$dli}}" id="dl_select_all_row_wise_{{$dli}}" onclick="dl_row({{$dli}});">
             </div>
             <div class="col-5">                  
             <span class="pl-3"> {{$document_lockers}}</span>
             <input type="hidden" name="dl_menu_name[]" id="dl_menu_name" value="{{$key}}">                   
             </div>
             <div class="col-1">
                 <input type="checkbox" class="dl_checkox" name="dl_add[]"  value="0" id="dl_add_{{$dli}}">
             </div>
             <div class="col-1">
                 <input type="checkbox" class="dl_checkox" name="dl_edit[]"  value="0" id="dl_edit_{{$dli}}" >
             </div>
             <div class="col-1">
                 <input type="checkbox" class="dl_checkox" name="dl_view[]"  value="0" id="dl_view_{{$dli}}">
             </div>
             <div class="col-1">
                 <input type="checkbox" class="dl_checkox" name="dl_delete[]"  value="0" id="dl_delete_{{$dli}}">
             </div>
             <div class="col-1">
                 <input type="checkbox" class="dl_checkox" name="dl_export[]"   value="0" id="dl_export_{{$dli}}">
             </div>    
         </div>
     @php
     $dli++;
     $dl_row++;
     @endphp           
     @endforeach
     <input type="hidden" name="dl_row_count" id="dl_row_count" value="{{$dl_row}}">
</div> <br>
  <!-- Document Locker Menu Checkbox End -->

  <!-- Block Mapping Menu Checkbox Start -->

  <div class="col-12 pb-3">
    <h4> <strong>Block Mapping</strong>
     <input type="checkbox"  name="bm_select_all" id="bm_select_all" onclick="bm_select_all_func();"></h4>
   </div>
   <div class="row justify-content-start">                     
     <div class="row mb-6">
         <div class="col-1">
             <b>Sl.No</b>
         </div>
         <div class="col-1"></div>
         <div class="col-5">
             <strong>Menu</strong>
         </div>
         <div class="col-1">
             <strong>Add</strong>
         </div>
         <div class="col-1">
             <strong> Edit</strong>
         </div>
         <div class="col-1">
             <strong>View</strong>
         </div>
         <div class="col-1">
             <strong>Delete</strong>
         </div>
         <div class="col-1">
             <strong>Export</strong>
         </div> 
     </div>
     @php
         $bmi=1;
         $bm_row=0;
     @endphp
     @foreach($block_mapping as $key => $block_mappings)               
     <div class="row mb-6"> 
         <div class="col-1">
             {{ $bmi}}
         </div>
         <div class="col-1">
             <input type="checkbox" class="bm_checkox" name="bm_select_all_row_wise_{{$bmi}}" id="bm_select_all_row_wise_{{$bmi}}" onclick="bm_row({{$bmi}});">
         </div>
         <div class="col-5">                  
         <span class="pl-3"> {{$block_mappings}}</span>
         <input type="hidden" name="bm_menu_name[]" id="bm_menu_name" value="{{$key}}">                   
         </div>
         <div class="col-1">
             <input type="checkbox" class="bm_checkox" name="bm_add[]"  value="0" id="bm_add_{{$bmi}}">
         </div>
         <div class="col-1">
             <input type="checkbox" class="bm_checkox" name="bm_edit[]"  value="0" id="bm_edit_{{$bmi}}" >
         </div>
         <div class="col-1">
             <input type="checkbox" class="bm_checkox" name="bm_view[]"  value="0" id="bm_view_{{$bmi}}">
         </div>
         <div class="col-1">
             <input type="checkbox" class="bm_checkox" name="bm_delete[]"  value="0" id="bm_delete_{{$bmi}}">
         </div>
         <div class="col-1">
             <input type="checkbox" class="bm_checkox" name="bm_export[]"  value="0" id="bm_export_{{$bmi}}">
         </div>    
     </div>
 @php
 $bmi++;
 $bm_row++;
 @endphp           
 @endforeach
 <input type="hidden" name="bm_row_count" id="bm_row_count" value="{{$bm_row}}">
</div> <br>
<!--Block Mapping Menu Checkbox End -->


<!-- Attendance Management Menu Checkbox Start -->

<div class="col-12 pb-3">
    <h4> <strong> Attendance Management</strong>
     <input type="checkbox"  name="att_man_select_all" id="att_man_select_all" onclick="att_man_select_all_func();"></h4>
   </div>
   <div class="row justify-content-start">                     
     <div class="row mb-6">
         <div class="col-1">
             <b>Sl.No</b>
         </div>
         <div class="col-1"></div>
         <div class="col-5">
             <strong>Menu</strong>
         </div>
         <div class="col-1">
             <strong>Add</strong>
         </div>
         <div class="col-1">
             <strong> Edit</strong>
         </div>
         <div class="col-1">
             <strong>View</strong>
         </div>
         <div class="col-1">
             <strong>Delete</strong>
         </div>
         <div class="col-1">
             <strong>Export</strong>
         </div> 
     </div>
     @php
         $att_mani=1;
         $att_man_row=0;
     @endphp
     @foreach($attendance_management as $key => $attendance_managements)               
     <div class="row mb-6"> 
         <div class="col-1">
             {{ $att_mani}}
         </div>
         <div class="col-1">
             <input type="checkbox" class="att_man_checkox" name="att_man_select_all_row_wise_{{$att_mani}}" id="att_man_select_all_row_wise_{{$att_mani}}" onclick="att_man_row({{$att_mani}});">
         </div>
         <div class="col-5">                  
         <span class="pl-3"> {{$attendance_managements}}</span>
         <input type="hidden" name="att_man_menu_name[]" id="att_man_menu_name" value="{{$key}}">                   
         </div>
         <div class="col-1">
             <input type="checkbox" class="att_man_checkox" name="att_man_add[]"  value="0" id="att_man_add_{{$att_mani}}">
         </div>
         <div class="col-1">
             <input type="checkbox" class="att_man_checkox" name="att_man_edit[]"  value="0"  id="att_man_edit_{{$att_mani}}" >
         </div>
         <div class="col-1">
             <input type="checkbox" class="att_man_checkox" name="att_man_view[]"   value="0" id="att_man_view_{{$att_mani}}">
         </div>
         <div class="col-1">
             <input type="checkbox" class="att_man_checkox" name="att_man_delete[]"   value="0" id="att_man_delete_{{$att_mani}}">
         </div>
         <div class="col-1">
             <input type="checkbox" class="att_man_checkox" name="att_man_export[]"  value="0"  id="att_man_export_{{$att_mani}}">
         </div>    
     </div>
 @php
 $att_mani++;
 $att_man_row++;
 @endphp           
 @endforeach
 <input type="hidden" name="att_man_row_count" id="att_man_row_count" value="{{$att_man_row}}">
</div> <br>
<!-- Attendance Management Menu Checkbox End -->

<!-- Leave Management Menu Checkbox Start -->

<div class="col-12 pb-3">
    <h4> <strong> Leave Management</strong>
     <input type="checkbox"  name="lm_select_all" id="lm_select_all" onclick="lm_select_all_func();"></h4>
   </div>
   <div class="row justify-content-start">                     
     <div class="row mb-6">
         <div class="col-1">
             <b>Sl.No</b>
         </div>
         <div class="col-1"></div>
         <div class="col-5">
             <strong>Menu</strong>
         </div>
         <div class="col-1">
             <strong>Add</strong>
         </div>
         <div class="col-1">
             <strong> Edit</strong>
         </div>
         <div class="col-1">
             <strong>View</strong>
         </div>
         <div class="col-1">
             <strong>Delete</strong>
         </div>
         <div class="col-1">
             <strong>Export</strong>
         </div> 
     </div>
     @php
         $lmi=1;
         $lm_row=0;
     @endphp
     @foreach($leave_management as $key => $leave_managements)               
     <div class="row mb-6"> 
         <div class="col-1">
             {{ $lmi}}
         </div>
         <div class="col-1">
             <input type="checkbox" class="lm_checkox" name="lm_select_all_row_wise_{{$lmi}}" id="lm_select_all_row_wise_{{$lmi}}" onclick="lm_row({{$lmi}});">
         </div>
         <div class="col-5">                  
         <span class="pl-3"> {{$leave_managements}}</span>
         <input type="hidden" name="lm_menu_name[]" id="lm_menu_name" value="{{$key}}">                   
         </div>
         <div class="col-1">
             <input type="checkbox" class="lm_checkox" name="lm_add[]"  value="0" id="lm_add_{{$lmi}}">
         </div>
         <div class="col-1">
             <input type="checkbox" class="lm_checkox" name="lm_edit[]"  value="0"  id="lm_edit_{{$lmi}}" >
         </div>
         <div class="col-1">
             <input type="checkbox" class="lm_checkox" name="lm_view[]"  value="0"  id="lm_view_{{$lmi}}">
         </div>
         <div class="col-1">
             <input type="checkbox" class="lm_checkox" name="lm_delete[]" value="0"  id="lm_delete_{{$lmi}}">
         </div>
         <div class="col-1">
             <input type="checkbox" class="lm_checkox" name="lm_export[]"  value="0" id="lm_export_{{$lmi}}">
         </div>    
     </div>
 @php
 $lmi++;
 $lm_row++;
 @endphp           
 @endforeach
 <input type="hidden" name="lm_row_count" id="lm_row_count" value="{{$lm_row}}">
</div> 
<br>
<!-- Leave Management Menu Checkbox End -->

<!-- Payroll Management Menu Checkbox Start -->

<div class="col-12 pb-3">
    <h4> <strong> Payroll Management</strong>
     <input type="checkbox"  name="prm_select_all" id="prm_select_all" onclick="prm_select_all_func();"></h4>
   </div>
   <div class="row justify-content-start">                     
     <div class="row mb-6">
         <div class="col-1">
             <b>Sl.No</b>
         </div>
         <div class="col-1"></div>
         <div class="col-5">
             <strong>Menu</strong>
         </div>
         <div class="col-1">
             <strong>Add</strong>
         </div>
         <div class="col-1">
             <strong> Edit</strong>
         </div>
         <div class="col-1">
             <strong>View</strong>
         </div>
         <div class="col-1">
             <strong>Delete</strong>
         </div>
         <div class="col-1">
             <strong>Export</strong>
         </div> 
     </div>
     @php
         $prmi=1;
         $prm_row=0;
     @endphp
     @foreach($payroll_management as $key => $payroll_managements)               
     <div class="row mb-6"> 
         <div class="col-1">
             {{ $prmi}}
         </div>
         <div class="col-1">
             <input type="checkbox" class="prm_checkox" name="prm_select_all_row_wise_{{$prmi}}" id="prm_select_all_row_wise_{{$prmi}}" onclick="prm_row({{$prmi}});">
         </div>
         <div class="col-5">                  
         <span class="pl-3"> {{$payroll_managements}}</span>
         <input type="hidden" name="prm_menu_name[]" id="prm_menu_name" value="{{$key}}">                   
         </div>
         <div class="col-1">
             <input type="checkbox" class="prm_checkox" name="prm_add[]"  value="0"  id="prm_add_{{$prmi}}">
         </div>
         <div class="col-1">
             <input type="checkbox" class="prm_checkox" name="prm_edit[]"  value="0"  id="prm_edit_{{$prmi}}" >
         </div>
         <div class="col-1">
             <input type="checkbox" class="prm_checkox" name="prm_view[]"  value="0" id="prm_view_{{$prmi}}">
         </div>
         <div class="col-1">
             <input type="checkbox" class="prm_checkox" name="prm_delete[]"   value="0" id="prm_delete_{{$prmi}}">
         </div>
         <div class="col-1">
             <input type="checkbox" class="prm_checkox" name="prm_export[]"  value="0" id="prm_export_{{$prmi}}">
         </div>    
     </div>
 @php
 $prmi++;
 $prm_row++;
 @endphp           
 @endforeach
 <input type="hidden" name="prm_row_count" id="prm_row_count" value="{{$prm_row}}">
</div> 
<br>
<!-- Payroll Management Menu Checkbox End -->

    <!-- Master Menu Checkbox Start -->
            <div class="col-12 pb-3">
               <h4> <strong>Master Menu</strong>
                <input type="checkbox"  name="master_select_all" id="master_select_all" onclick="master_select_all_func();"></h4>
              </div>
              <div class="row justify-content-start">                     
                <div class="row mb-6">
                    <div class="col-1">
                        <b>Sl.No</b>
                    </div>
                    <div class="col-1"></div>
                    <div class="col-5">
                        <strong>Menu</strong>
                    </div>
                    <div class="col-1">
                        <strong>Add</strong>
                    </div>
                    <div class="col-1">
                        <strong> Edit</strong>
                    </div>
                    <div class="col-1">
                        <strong>View</strong>
                    </div>
                    <div class="col-1">
                        <strong>Delete</strong>
                    </div>
                    <div class="col-1">
                        <strong>Export</strong>
                    </div> 
                </div>
                @php
                    $i=1;
                    $master_row=0;
                @endphp
                @foreach($master_menu as $key => $master_menus)               
                <div class="row mb-6"> 
                    <div class="col-1">
                        {{ $i}}
                    </div>
                    <div class="col-1">
                        <input type="checkbox" class="master_checkox" name="select_all_row_wise_{{$i}}" id="select_all_row_wise_{{$i}}" onclick="master_row({{$i}});">
                    </div>
                    <div class="col-5">                  
                    <span class="pl-3"> {{$master_menus}}</span>
                    <input type="hidden" name="menu_name[]" id="menu_name" value="{{$key}}">                   
                    </div>
                    <div class="col-1">
                        <input type="checkbox" class="master_checkox" name="master_add[]"  value="0" id="master_add_{{$i}}">
                    </div>
                    <div class="col-1">
                        <input type="checkbox" class="master_checkox" name="master_edit[]"  value="0" id="master_edit_{{$i}}" >
                    </div>
                    <div class="col-1">
                        <input type="checkbox" class="master_checkox" name="master_view[]"  value="0"  id="master_view_{{$i}}">
                    </div>
                    <div class="col-1">
                        <input type="checkbox" class="master_checkox" name="master_delete[]"  value="0"  id="master_delete_{{$i}}">
                    </div>
                    <div class="col-1">
                        <input type="checkbox" class="master_checkox" name="master_export[]"  value="0" id="master_export_{{$i}}">
                    </div>    
                </div>
            @php
            $i++;
            $master_row++;
            @endphp           
            @endforeach
            <input type="hidden" name="master_row_count" id="master_row_count" value="{{$master_row}}">
                    </div> 
                    <div class="form-group mb-10 text-end">
                        <button type="button" class="btn btn-light-primary" data-bs-dismiss="modal"> Cancel </button>
                        <button type="submit" class="btn btn-primary" id=""> 
                            <span class="indicator-label">
                                Submit
                            </span>
                            <span class="indicator-progress">
                                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
        <!-- Master Menu Checkbox End -->

                </div>
            </div>
            <!--end::Table-->
            </div></form>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
@endsection
@section('add_on_script')
@endsection
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
    function permission_table_show()
    {
        var role_id=$("#role_id").val();
        if(role_id=='')
        {   
            $("#kt_table_users_wrapper").hide();
        }
        else
        {
            $("#kt_table_users_wrapper").show();
        }              
    }
   /* $( document ).ready(function() {
    $(":checkbox").click(function(){
     //alert(" you checked");
     if($(this).is(':checked'))
     {
         var checked1=$(this).val();
         //alert("The value for selected checkbox = "+checked1);
         $(this).val('1');
        //alert("checkedval="+$(this).val());
     }
     else
     {
        $(this).val('0');
     }
});
});*/


//Account Meu Checkbox Start
function account_row(acc_i)
    {
        var acc_select_all_row_wise = document.getElementById("account_select_all_row_wise_"+acc_i);
        if (acc_select_all_row_wise.checked == true)
        {
            document.getElementById("account_add_"+acc_i).checked = true;
            document.getElementById("account_edit_"+acc_i).checked = true;
            document.getElementById("account_view_"+acc_i).checked = true;
            document.getElementById("account_delete_"+acc_i).checked = true;
            document.getElementById("account_export_"+acc_i).checked = true;
            $("#account_add_"+acc_i).val('1');
            $("#account_edit_"+acc_i).val('1');
            $("#account_view_"+acc_i).val('1');
            $("#account_delete_"+acc_i).val('1');
            $("#account_export_"+acc_i).val('1');
        }
        else
        {
            document.getElementById("account_add_"+acc_i).checked = false;
            document.getElementById("account_edit_"+acc_i).checked = false;
            document.getElementById("account_view_"+acc_i).checked = false;
            document.getElementById("account_delete_"+acc_i).checked = false;
            document.getElementById("account_export_"+acc_i).checked = false;
            $("#account_select_all").prop("checked", false);
            $("#account_add_"+acc_i).val('0');
            $("#account_edit_"+acc_i).val('0');
            $("#account_view_"+acc_i).val('0');
            $("#account_delete_"+acc_i).val('0');
            $("#account_export_"+acc_i).val('0');
           
        }
    }

    function account_select_all_func()
    {
        var account_selectall = document.getElementById("account_select_all");
        var account_row_count = $("#account_row_count").val();
        if (account_selectall.checked == true)
        {
            for (let acc_i = 1; acc_i <= account_row_count; acc_i++) 
            {  
                document.getElementById("account_select_all_row_wise_"+acc_i).checked = true;            
                document.getElementById("account_add_"+acc_i).checked = true;
                document.getElementById("account_edit_"+acc_i).checked = true;
                document.getElementById("account_view_"+acc_i).checked = true;
                document.getElementById("account_delete_"+acc_i).checked = true;
                document.getElementById("account_export_"+acc_i).checked = true;
                $("#account_add_"+acc_i).val('1');
                $("#account_edit_"+acc_i).val('1');
                $("#account_view_"+acc_i).val('1');
                $("#account_delete_"+acc_i).val('1');
                $("#account_export_"+acc_i).val('1');
            }
        }
        else
        {
            for (let acc_j = 1; acc_j <= account_row_count; acc_j++) 
            {
                document.getElementById("account_select_all_row_wise_"+acc_j).checked = false;
                document.getElementById("account_add_"+acc_j).checked = false;
                document.getElementById("account_edit_"+acc_j).checked = false;
                document.getElementById("account_view_"+acc_j).checked = false;
                document.getElementById("account_delete_"+acc_j).checked = false;
                document.getElementById("account_export_"+acc_j).checked = false;
                $("#account_add_"+acc_i).val('0');
                $("#account_edit_"+acc_i).val('0');
                $("#account_view_"+acc_i).val('0');
                $("#account_delete_"+acc_i).val('0');
                $("#account_export_"+acc_i).val('0');
            }
        }
    }
//Account Meu Checkbox End

//Authentication Meu Checkbox Start
function auth_row(auth_i)
    {
        var auth_select_all_row_wise = document.getElementById("auth_select_all_row_wise_"+auth_i);
        if (auth_select_all_row_wise.checked == true)
        {
            document.getElementById("auth_add_"+auth_i).checked = true;
            document.getElementById("auth_edit_"+auth_i).checked = true;
            document.getElementById("auth_view_"+auth_i).checked = true;
            document.getElementById("auth_delete_"+auth_i).checked = true;
            document.getElementById("auth_export_"+auth_i).checked = true;
            $("#auth_add_"+auth_i).val('1');
            $("#auth_edit_"+auth_i).val('1');
            $("#auth_view_"+auth_i).val('1');
            $("#auth_delete_"+auth_i).val('1');
            $("#auth_export_"+auth_i).val('1');
        }
        else
        {
            document.getElementById("auth_add_"+auth_i).checked = false;
            document.getElementById("auth_edit_"+auth_i).checked = false;
            document.getElementById("auth_view_"+auth_i).checked = false;
            document.getElementById("auth_delete_"+auth_i).checked = false;
            document.getElementById("auth_export_"+auth_i).checked = false;
            $("#auth_select_all").prop("checked", false);
            $("#auth_add_"+auth_i).val('0');
            $("#auth_edit_"+auth_i).val('0');
            $("#auth_view_"+auth_i).val('0');
            $("#auth_delete_"+auth_i).val('0');
            $("#auth_export_"+auth_i).val('0');
           
        }
    }

    function auth_select_all_func()
    {
        var auth_selectall = document.getElementById("auth_select_all");
        var auth_row_count = $("#auth_row_count").val();
        if (auth_selectall.checked == true)
        {
            for (let auth_i = 1; auth_i <= auth_row_count; auth_i++) 
            {  
                document.getElementById("auth_select_all_row_wise_"+auth_i).checked = true;            
                document.getElementById("auth_add_"+auth_i).checked = true;
                document.getElementById("auth_edit_"+auth_i).checked = true;
                document.getElementById("auth_view_"+auth_i).checked = true;
                document.getElementById("auth_delete_"+auth_i).checked = true;
                document.getElementById("auth_export_"+auth_i).checked = true;
                $("#auth_add_"+auth_i).val('1');
                $("#auth_edit_"+auth_i).val('1');
                $("#auth_view_"+auth_i).val('1');
                $("#auth_delete_"+auth_i).val('1');
                $("#auth_export_"+auth_i).val('1');
            }
        }
        else
        {
            for (let auth_j = 1; auth_j <= auth_row_count; auth_j++) 
            {
                document.getElementById("auth_select_all_row_wise_"+auth_j).checked = false;
                document.getElementById("auth_add_"+auth_j).checked = false;
                document.getElementById("auth_edit_"+auth_j).checked = false;
                document.getElementById("auth_view_"+auth_j).checked = false;
                document.getElementById("auth_delete_"+auth_j).checked = false;
                document.getElementById("auth_export_"+auth_j).checked = false;
                $("#auth_add_"+auth_i).val('0');
                $("#auth_edit_"+auth_i).val('0');
                $("#auth_view_"+auth_i).val('0');
                $("#auth_delete_"+auth_i).val('0');
                $("#auth_export_"+auth_i).val('0');
            }
        }
    }
//Authentication Menu Checkbox End

// Staff Menu Checkbox Start
function staff_row(staff_i)
    {
        var staff_select_all_row_wise = document.getElementById("staff_select_all_row_wise_"+staff_i);
        if (staff_select_all_row_wise.checked == true)
        {
            document.getElementById("staff_add_"+staff_i).checked = true;
            document.getElementById("staff_edit_"+staff_i).checked = true;
            document.getElementById("staff_view_"+staff_i).checked = true;
            document.getElementById("staff_delete_"+staff_i).checked = true;
            document.getElementById("staff_export_"+staff_i).checked = true;
            $("#staff_add_"+staff_i).val('1');
            $("#staff_edit_"+staff_i).val('1');
            $("#staff_view_"+staff_i).val('1');
            $("#staff_delete_"+staff_i).val('1');
            $("#staff_export_"+staff_i).val('1');
        }
        else
        {
            document.getElementById("staff_add_"+staff_i).checked = false;
            document.getElementById("staff_edit_"+staff_i).checked = false;
            document.getElementById("staff_view_"+staff_i).checked = false;
            document.getElementById("staff_delete_"+staff_i).checked = false;
            document.getElementById("staff_export_"+staff_i).checked = false;
            $("#staff_select_all").prop("checked", false);
            $("#staff_add_"+staff_i).val('0');
            $("#staff_edit_"+staff_i).val('0');
            $("#staff_view_"+staff_i).val('0');
            $("#staff_delete_"+staff_i).val('0');
            $("#staff_export_"+staff_i).val('0');           
        }
    }

    function staff_select_all_func()
    {
        var staff_selectall = document.getElementById("staff_select_all");
        var staff_row_count = $("#staff_row_count").val();
        if (staff_selectall.checked == true)
        {
            for (let staff_i = 1; staff_i <= staff_row_count; staff_i++) 
            {  
                document.getElementById("staff_select_all_row_wise_"+staff_i).checked = true;
                document.getElementById("staff_add_"+staff_i).checked = true;
                document.getElementById("staff_edit_"+staff_i).checked = true;
                document.getElementById("staff_view_"+staff_i).checked = true;
                document.getElementById("staff_delete_"+staff_i).checked = true;
                document.getElementById("staff_export_"+staff_i).checked = true;
                $("#staff_add_"+staff_i).val('1');
                $("#staff_edit_"+staff_i).val('1');
                $("#staff_view_"+staff_i).val('1');
                $("#staff_delete_"+staff_i).val('1');
                $("#staff_export_"+staff_i).val('1');
            }
        }
        else
        {
            for (let staff_j = 1; staff_j <= staff_row_count; staff_j++) 
            {
                document.getElementById("staff_select_all_row_wise_"+staff_j).checked = false;
                document.getElementById("staff_add_"+staff_j).checked = false;
                document.getElementById("staff_edit_"+staff_j).checked = false;
                document.getElementById("staff_view_"+staff_j).checked = false;
                document.getElementById("staff_delete_"+staff_j).checked = false;
                document.getElementById("staff_export_"+staff_j).checked = false;
                $("#staff_add_"+staff_i).val('0');
                $("#staff_edit_"+staff_i).val('0');
                $("#staff_view_"+staff_i).val('0');
                $("#staff_delete_"+staff_i).val('0');
                $("#staff_export_"+staff_i).val('0');    
            }
        }
    }
//Staff Menu Checkbox End


// Document Locker Menu Checkbox Start
function dl_row(dl_i)
    {
        var dl_select_all_row_wise = document.getElementById("dl_select_all_row_wise_"+dl_i);
        if (dl_select_all_row_wise.checked == true)
        {
            document.getElementById("dl_add_"+dl_i).checked = true;
            document.getElementById("dl_edit_"+dl_i).checked = true;
            document.getElementById("dl_view_"+dl_i).checked = true;
            document.getElementById("dl_delete_"+dl_i).checked = true;
            document.getElementById("dl_export_"+dl_i).checked = true;
            $("#dl_add_"+dl_i).val('1');
            $("#dl_edit_"+dl_i).val('1');
            $("#dl_view_"+dl_i).val('1');
            $("#dl_delete_"+dl_i).val('1');
            $("#dl_export_"+dl_i).val('1');
        }
        else
        {
            document.getElementById("dl_add_"+dl_i).checked = false;
            document.getElementById("dl_edit_"+dl_i).checked = false;
            document.getElementById("dl_view_"+dl_i).checked = false;
            document.getElementById("dl_delete_"+dl_i).checked = false;
            document.getElementById("dl_export_"+dl_i).checked = false;
            $("#dl_select_all").prop("checked", false);
            $("#dl_add_"+dl_i).val('0');
            $("#dl_edit_"+dl_i).val('0');
            $("#dl_view_"+dl_i).val('0');
            $("#dl_delete_"+dl_i).val('0');
            $("#dl_export_"+dl_i).val('0');
           
        }
    }

    function dl_select_all_func()
    {
        var dl_selectall = document.getElementById("dl_select_all");
        var dl_row_count = $("#dl_row_count").val();
        if (dl_selectall.checked == true)
        {
            for (let dl_i = 1; dl_i <= dl_row_count; dl_i++) 
            {  
                document.getElementById("dl_select_all_row_wise_"+dl_i).checked = true;
                document.getElementById("dl_add_"+dl_i).checked = true;
                document.getElementById("dl_edit_"+dl_i).checked = true;
                document.getElementById("dl_view_"+dl_i).checked = true;
                document.getElementById("dl_delete_"+dl_i).checked = true;
                document.getElementById("dl_export_"+dl_i).checked = true;
                $("#dl_add_"+dl_i).val('1');
                $("#dl_edit_"+dl_i).val('1');
                $("#dl_view_"+dl_i).val('1');
                $("#dl_delete_"+dl_i).val('1');
                $("#dl_export_"+dl_i).val('1');
            }
        }
        else
        {
            for (let dl_j = 1; dl_j <= dl_row_count; dl_j++) 
            {
                document.getElementById("dl_select_all_row_wise_"+dl_j).checked = false;
                document.getElementById("dl_add_"+dl_j).checked = false;
                document.getElementById("dl_edit_"+dl_j).checked = false;
                document.getElementById("dl_view_"+dl_j).checked = false;
                document.getElementById("dl_delete_"+dl_j).checked = false;
                document.getElementById("dl_export_"+dl_j).checked = false;
                $("#dl_add_"+dl_i).val('0');
                $("#dl_edit_"+dl_i).val('0');
                $("#dl_view_"+dl_i).val('0');
                $("#dl_delete_"+dl_i).val('0');
                $("#dl_export_"+dl_i).val('0');
            }
        }
    }
//Document Locker Checkbox End

// Block Mapping  Checkbox Start
function bm_row(bm_i)
    {
        var bm_select_all_row_wise = document.getElementById("bm_select_all_row_wise_"+bm_i);
        if (bm_select_all_row_wise.checked == true)
        {
            document.getElementById("bm_add_"+bm_i).checked = true;
            document.getElementById("bm_edit_"+bm_i).checked = true;
            document.getElementById("bm_view_"+bm_i).checked = true;
            document.getElementById("bm_delete_"+bm_i).checked = true;
            document.getElementById("bm_export_"+bm_i).checked = true;
            $("#bm_add_"+bm_i).val('1');
            $("#bm_edit_"+bm_i).val('1');
            $("#bm_view_"+bm_i).val('1');
            $("#bm_delete_"+bm_i).val('1');
            $("#bm_export_"+bm_i).val('1');
        }
        else
        {
            document.getElementById("bm_add_"+bm_i).checked = false;
            document.getElementById("bm_edit_"+bm_i).checked = false;
            document.getElementById("bm_view_"+bm_i).checked = false;
            document.getElementById("bm_delete_"+bm_i).checked = false;
            document.getElementById("bm_export_"+bm_i).checked = false;
            $("#bm_select_all").prop("checked", false);
            $("#bm_add_"+bm_i).val('0');
            $("#bm_edit_"+bm_i).val('0');
            $("#bm_view_"+bm_i).val('0');
            $("#bm_delete_"+bm_i).val('0');
            $("#bm_export_"+bm_i).val('0');
           
        }
    }

    function bm_select_all_func()
    {
        var bm_selectall = document.getElementById("bm_select_all");
        var bm_row_count = $("#bm_row_count").val();
        if (bm_selectall.checked == true)
        {
            for (let bm_i = 1; bm_i <= bm_row_count; bm_i++) 
            {  
                document.getElementById("bm_select_all_row_wise_"+bm_i).checked = true;
                document.getElementById("bm_add_"+bm_i).checked = true;
                document.getElementById("bm_edit_"+bm_i).checked = true;
                document.getElementById("bm_view_"+bm_i).checked = true;
                document.getElementById("bm_delete_"+bm_i).checked = true;
                document.getElementById("bm_export_"+bm_i).checked = true;
                $("#bm_add_"+bm_i).val('1');
                $("#bm_edit_"+bm_i).val('1');
                $("#bm_view_"+bm_i).val('1');
                $("#bm_delete_"+bm_i).val('1');
                $("#bm_export_"+bm_i).val('1');
            }
        }
        else
        {
            for (let bm_j = 1; bm_j <= bm_row_count; bm_j++) 
            {
                document.getElementById("bm_select_all_row_wise_"+bm_j).checked = false;
                document.getElementById("bm_add_"+bm_j).checked = false;
                document.getElementById("bm_edit_"+bm_j).checked = false;
                document.getElementById("bm_view_"+bm_j).checked = false;
                document.getElementById("bm_delete_"+bm_j).checked = false;
                document.getElementById("bm_export_"+bm_j).checked = false;
                $("#bm_add_"+bm_i).val('0');
                $("#bm_edit_"+bm_i).val('0');
                $("#bm_view_"+bm_i).val('0');
                $("#bm_delete_"+bm_i).val('0');
                $("#bm_export_"+bm_i).val('0');
            }
        }
    }
//Block Mapping Locker Checkbox End


// Attendance Management  Checkbox Start
function att_man_row(att_man_i)
    {
        var att_man_select_all_row_wise = document.getElementById("att_man_select_all_row_wise_"+att_man_i);
        if (att_man_select_all_row_wise.checked == true)
        {
            document.getElementById("att_man_add_"+att_man_i).checked = true;
            document.getElementById("att_man_edit_"+att_man_i).checked = true;
            document.getElementById("att_man_view_"+att_man_i).checked = true;
            document.getElementById("att_man_delete_"+att_man_i).checked = true;
            document.getElementById("att_man_export_"+att_man_i).checked = true;
            $("#att_man_add_"+att_man_i).val('1');
            $("#att_man_edit_"+att_man_i).val('1');
            $("#att_man_view_"+att_man_i).val('1');
            $("#att_man_delete_"+att_man_i).val('1');
            $("#att_man_export_"+att_man_i).val('1');
        }
        else
        {
            document.getElementById("att_man_add_"+att_man_i).checked = false;
            document.getElementById("att_man_edit_"+att_man_i).checked = false;
            document.getElementById("att_man_view_"+att_man_i).checked = false;
            document.getElementById("att_man_delete_"+att_man_i).checked = false;
            document.getElementById("att_man_export_"+att_man_i).checked = false;
            $("#att_man_select_all").prop("checked", false);
            $("#att_man_add_"+att_man_i).val('0');
            $("#att_man_edit_"+att_man_i).val('0');
            $("#att_man_view_"+att_man_i).val('0');
            $("#att_man_delete_"+att_man_i).val('0');
            $("#att_man_export_"+att_man_i).val('0');
           
        }
    }

    function att_man_select_all_func()
    {
        var att_man_selectall = document.getElementById("att_man_select_all");
        var att_man_row_count = $("#att_man_row_count").val();
        if (att_man_selectall.checked == true)
        {
            for (let att_man_i = 1; att_man_i <= att_man_row_count; att_man_i++) 
            {  
                document.getElementById("att_man_select_all_row_wise_"+att_man_i).checked = true;
                document.getElementById("att_man_add_"+att_man_i).checked = true;
                document.getElementById("att_man_edit_"+att_man_i).checked = true;
                document.getElementById("att_man_view_"+att_man_i).checked = true;
                document.getElementById("att_man_delete_"+att_man_i).checked = true;
                document.getElementById("att_man_export_"+att_man_i).checked = true;
                $("#att_man_add_"+att_man_i).val('1');
                $("#att_man_edit_"+att_man_i).val('1');
                $("#att_man_view_"+att_man_i).val('1');
                $("#att_man_delete_"+att_man_i).val('1');
                $("#att_man_export_"+att_man_i).val('1');
            }
        }
        else
        {
            for (let att_man_j = 1; att_man_j <= att_man_row_count; att_man_j++) 
            {
                document.getElementById("att_man_select_all_row_wise_"+att_man_j).checked = false;
                document.getElementById("att_man_add_"+att_man_j).checked = false;
                document.getElementById("att_man_edit_"+att_man_j).checked = false;
                document.getElementById("att_man_view_"+att_man_j).checked = false;
                document.getElementById("att_man_delete_"+att_man_j).checked = false;
                document.getElementById("att_man_export_"+att_man_j).checked = false;
                $("#att_man_add_"+att_man_i).val('0');
                $("#att_man_edit_"+att_man_i).val('0');
                $("#att_man_view_"+att_man_i).val('0');
                $("#att_man_delete_"+att_man_i).val('0');
                $("#att_man_export_"+att_man_i).val('0');
            }
        }
    }
//Attendance Management Checkbox End


// Leave Management  Checkbox Start
function lm_row(lm_i)
    {
        var lm_select_all_row_wise = document.getElementById("lm_select_all_row_wise_"+lm_i);
        if (lm_select_all_row_wise.checked == true)
        {
            document.getElementById("lm_add_"+lm_i).checked = true;
            document.getElementById("lm_edit_"+lm_i).checked = true;
            document.getElementById("lm_view_"+lm_i).checked = true;
            document.getElementById("lm_delete_"+lm_i).checked = true;
            document.getElementById("lm_export_"+lm_i).checked = true;
            $("#lm_add_"+lm_i).val('1');
            $("#lm_edit_"+lm_i).val('1');
            $("#lm_view_"+lm_i).val('1');
            $("#lm_delete_"+lm_i).val('1');
            $("#lm_export_"+lm_i).val('1');
        }
        else
        {
            document.getElementById("lm_add_"+lm_i).checked = false;
            document.getElementById("lm_edit_"+lm_i).checked = false;
            document.getElementById("lm_view_"+lm_i).checked = false;
            document.getElementById("lm_delete_"+lm_i).checked = false;
            document.getElementById("lm_export_"+lm_i).checked = false;
            $("#lm_select_all").prop("checked", false);
            $("#lm_add_"+lm_i).val('0');
            $("#lm_edit_"+lm_i).val('0');
            $("#lm_view_"+lm_i).val('0');
            $("#lm_delete_"+lm_i).val('0');
            $("#lm_export_"+lm_i).val('0');
           
        }
    }

    function lm_select_all_func()
    {
        var lm_selectall = document.getElementById("lm_select_all");
        var lm_row_count = $("#lm_row_count").val();
        if (lm_selectall.checked == true)
        {
            for (let lm_i = 1; lm_i <= lm_row_count; lm_i++) 
            {  
                document.getElementById("lm_select_all_row_wise_"+lm_i).checked = true;
                document.getElementById("lm_add_"+lm_i).checked = true;
                document.getElementById("lm_edit_"+lm_i).checked = true;
                document.getElementById("lm_view_"+lm_i).checked = true;
                document.getElementById("lm_delete_"+lm_i).checked = true;
                document.getElementById("lm_export_"+lm_i).checked = true;
                $("#lm_add_"+lm_i).val('1');
                $("#lm_edit_"+lm_i).val('1');
                $("#lm_view_"+lm_i).val('1');
                $("#lm_delete_"+lm_i).val('1');
                $("#lm_export_"+lm_i).val('1');
            }
        }
        else
        {
            for (let lm_j = 1; lm_j <= lm_row_count; lm_j++) 
            {
                document.getElementById("lm_select_all_row_wise_"+lm_j).checked = false;
                document.getElementById("lm_add_"+lm_j).checked = false;
                document.getElementById("lm_edit_"+lm_j).checked = false;
                document.getElementById("lm_view_"+lm_j).checked = false;
                document.getElementById("lm_delete_"+lm_j).checked = false;
                document.getElementById("lm_export_"+lm_j).checked = false;
                $("#lm_add_"+lm_i).val('0');
                $("#lm_edit_"+lm_i).val('0');
                $("#lm_view_"+lm_i).val('0');
                $("#lm_delete_"+lm_i).val('0');
                $("#lm_export_"+lm_i).val('0');
            }
        }
    }
//Leave Management  Checkbox End


// Payroll Management  Checkbox Start
function prm_row(prm_i)
    {
        var prm_select_all_row_wise = document.getElementById("prm_select_all_row_wise_"+prm_i);
        if (prm_select_all_row_wise.checked == true)
        {
            document.getElementById("prm_add_"+prm_i).checked = true;
            document.getElementById("prm_edit_"+prm_i).checked = true;
            document.getElementById("prm_view_"+prm_i).checked = true;
            document.getElementById("prm_delete_"+prm_i).checked = true;
            document.getElementById("prm_export_"+prm_i).checked = true;
            $("#prm_add_"+prm_i).val('1');
            $("#prm_edit_"+prm_i).val('1');
            $("#prm_view_"+prm_i).val('1');
            $("#prm_delete_"+prm_i).val('1');
            $("#prm_export_"+prm_i).val('1');
        }
        else
        {
            document.getElementById("prm_add_"+prm_i).checked = false;
            document.getElementById("prm_edit_"+prm_i).checked = false;
            document.getElementById("prm_view_"+prm_i).checked = false;
            document.getElementById("prm_delete_"+prm_i).checked = false;
            document.getElementById("prm_export_"+prm_i).checked = false;
            $("#prm_select_all").prop("checked", false);
            $("#prm_add_"+prm_i).val('0');
            $("#prm_edit_"+prm_i).val('0');
            $("#prm_view_"+prm_i).val('0');
            $("#prm_delete_"+prm_i).val('0');
            $("#prm_export_"+prm_i).val('0');
           
        }
    }

    function prm_select_all_func()
    {
        var prm_selectall = document.getElementById("prm_select_all");
        var prm_row_count = $("#prm_row_count").val();
        if (prm_selectall.checked == true)
        {
            for (let prm_i = 1; prm_i <= prm_row_count; prm_i++) 
            {  
                document.getElementById("prm_select_all_row_wise_"+prm_i).checked = true;
                document.getElementById("prm_add_"+prm_i).checked = true;
                document.getElementById("prm_edit_"+prm_i).checked = true;
                document.getElementById("prm_view_"+prm_i).checked = true;
                document.getElementById("prm_delete_"+prm_i).checked = true;
                document.getElementById("prm_export_"+prm_i).checked = true;
                $("#prm_add_"+prm_i).val('1');
                $("#prm_edit_"+prm_i).val('1');
                $("#prm_view_"+prm_i).val('1');
                $("#prm_delete_"+prm_i).val('1');
                $("#prm_export_"+prm_i).val('1');
            }
        }
        else
        {
            for (let prm_j = 1; prm_j <= prm_row_count; prm_j++) 
            {
                document.getElementById("prm_select_all_row_wise_"+prm_j).checked = false;
                document.getElementById("prm_add_"+prm_j).checked = false;
                document.getElementById("prm_edit_"+prm_j).checked = false;
                document.getElementById("prm_view_"+prm_j).checked = false;
                document.getElementById("prm_delete_"+prm_j).checked = false;
                document.getElementById("prm_export_"+prm_j).checked = false;
                $("#prm_add_"+prm_i).val('0');
                $("#prm_edit_"+prm_i).val('0');
                $("#prm_view_"+prm_i).val('0');
                $("#prm_delete_"+prm_i).val('0');
                $("#prm_export_"+prm_i).val('0');
            }
        }
    }
//Payroll Management  Checkbox End

    function master_row(i)
    {
        var select_all_row_wise = document.getElementById("select_all_row_wise_"+i);
        if (select_all_row_wise.checked == true)
        {
            document.getElementById("master_add_"+i).checked = true;
            document.getElementById("master_edit_"+i).checked = true;
            document.getElementById("master_view_"+i).checked = true;
            document.getElementById("master_delete_"+i).checked = true;
            document.getElementById("master_export_"+i).checked = true;
            $("#master_add_"+i).val('1');
            $("#master_edit_"+i).val('1');
            $("#master_view_"+i).val('1');
            $("#master_delete_"+i).val('1');
            $("#master_export_"+i).val('1');
        }
        else
        {
            console.log('row wise uncheck');
            document.getElementById("master_add_"+i).checked = false;
            document.getElementById("master_edit_"+i).checked = false;
            document.getElementById("master_view_"+i).checked = false;
            document.getElementById("master_delete_"+i).checked = false;
            document.getElementById("master_export_"+i).checked = false;
            $("#master_select_all").prop("checked", false);
            $("#master_add_"+i).val('0');
            $("#master_edit_"+i).val('0');
            $("#master_view_"+i).val('0');
            $("#master_delete_"+i).val('0');
            $("#master_export_"+i).val('0');
           
        }
    }

    function master_select_all_func()
    {
        var master_selectall = document.getElementById("master_select_all");
        var row_count = $("#master_row_count").val();
        if (master_selectall.checked == true)
        {
            for (let i = 1; i <= row_count; i++) 
            {  
                document.getElementById("select_all_row_wise_"+i).checked = true;            
                document.getElementById("master_add_"+i).checked = true;
                document.getElementById("master_edit_"+i).checked = true;
                document.getElementById("master_view_"+i).checked = true;
                document.getElementById("master_delete_"+i).checked = true;
                document.getElementById("master_export_"+i).checked = true;
                $("#master_add_"+i).val('1');
                $("#master_edit_"+i).val('1');
                $("#master_view_"+i).val('1');
                $("#master_delete_"+i).val('1');
                $("#master_export_"+i).val('1');
            }
        }
        else
        {
            for (let j = 1; j <= row_count; j++) 
            {
                document.getElementById("select_all_row_wise_"+j).checked = false;
                document.getElementById("master_add_"+j).checked = false;
                document.getElementById("master_edit_"+j).checked = false;
                document.getElementById("master_view_"+j).checked = false;
                document.getElementById("master_delete_"+j).checked = false;
                document.getElementById("master_export_"+j).checked = false;
                $("#master_add_"+i).val('0');
                $("#master_edit_"+i).val('0');
                $("#master_view_"+i).val('0');
                $("#master_delete_"+i).val('0');
                $("#master_export_"+i).val('0');
            }
        }
    }
    

    
$( document ).ready(function() {
    //account  check box start
    $('.account_checkox').click(function() {
        var acc_row_count = $("#account_row_count").val();
        for (let acc_k = 1; acc_k <= acc_row_count; acc_k++) 
        {
            if((document.getElementById("account_add_"+acc_k).checked == false) || (document.getElementById("account_edit_"+acc_k).checked == false)
            || (document.getElementById("account_view_"+acc_k).checked == false) || (document.getElementById("account_delete_"+acc_k).checked == false)
            || (document.getElementById("account_export_"+acc_k).checked == false))
            {
                document.getElementById("account_select_all_row_wise_"+acc_k).checked = false;
                document.getElementById("account_select_all").checked = false; 
            }
            else if((document.getElementById("account_add_"+acc_k).checked == true) &&  (document.getElementById("account_edit_"+acc_k).checked == true)
            && (document.getElementById("account_view_"+acc_k).checked == true) && (document.getElementById("account_delete_"+acc_k).checked == true)
            && (document.getElementById("account_export_"+acc_k).checked == true))
            {
                //alert('select all');
                document.getElementById("account_select_all_row_wise_"+acc_k).checked = true;
                document.getElementById("account_select_all").checked = true;
            }         
        }

    });
    //account  check box end

       //authentication  check box start
       $('.auth_checkox').click(function() {
        var acc_row_count = $("#auth_row_count").val();
        for (let acc_k = 1; acc_k <= acc_row_count; acc_k++) 
        {
            if((document.getElementById("auth_add_"+acc_k).checked == false) || (document.getElementById("auth_edit_"+acc_k).checked == false)
            || (document.getElementById("auth_view_"+acc_k).checked == false) || (document.getElementById("auth_delete_"+acc_k).checked == false)
            || (document.getElementById("auth_export_"+acc_k).checked == false))
            {
                document.getElementById("auth_select_all_row_wise_"+acc_k).checked = false;
                document.getElementById("auth_select_all").checked = false; 
            }
            else if((document.getElementById("auth_add_"+acc_k).checked == true) &&  (document.getElementById("auth_edit_"+acc_k).checked == true)
            && (document.getElementById("auth_view_"+acc_k).checked == true) && (document.getElementById("auth_delete_"+acc_k).checked == true)
            && (document.getElementById("auth_export_"+acc_k).checked == true))
            {
                //alert('select all');
                document.getElementById("auth_select_all_row_wise_"+acc_k).checked = true;
                document.getElementById("auth_select_all").checked = true;
            }         
        }

    });
    //authentication  check box end

    //staff  check box start
      $('.staff_checkox').click(function() {
        var acc_row_count = $("#staff_row_count").val();
        for (let acc_k = 1; acc_k <= acc_row_count; acc_k++) 
        {
            if((document.getElementById("staff_add_"+acc_k).checked == false) || (document.getElementById("staff_edit_"+acc_k).checked == false)
            || (document.getElementById("staff_view_"+acc_k).checked == false) || (document.getElementById("staff_delete_"+acc_k).checked == false)
            || (document.getElementById("staff_export_"+acc_k).checked == false))
            {
                document.getElementById("staff_select_all_row_wise_"+acc_k).checked = false;
                document.getElementById("staff_select_all").checked = false; 
            }
            else if((document.getElementById("staff_add_"+acc_k).checked == true) &&  (document.getElementById("staff_edit_"+acc_k).checked == true)
            && (document.getElementById("staff_view_"+acc_k).checked == true) && (document.getElementById("staff_delete_"+acc_k).checked == true)
            && (document.getElementById("staff_export_"+acc_k).checked == true))
            {
                //alert('select all');
                document.getElementById("staff_select_all_row_wise_"+acc_k).checked = true;
                document.getElementById("staff_select_all").checked = true;
            }         
        }

    });
    //staff  check box end

    //Document Locker  check box start
    $('.dl_checkox').click(function() {
        var acc_row_count = $("#dl_row_count").val();
        for (let acc_k = 1; acc_k <= acc_row_count; acc_k++) 
        {
            if((document.getElementById("dl_add_"+acc_k).checked == false) || (document.getElementById("dl_edit_"+acc_k).checked == false)
            || (document.getElementById("dl_view_"+acc_k).checked == false) || (document.getElementById("dl_delete_"+acc_k).checked == false)
            || (document.getElementById("dl_export_"+acc_k).checked == false))
            {
                document.getElementById("dl_select_all_row_wise_"+acc_k).checked = false;
                document.getElementById("dl_select_all").checked = false; 
            }
            else if((document.getElementById("dl_add_"+acc_k).checked == true) &&  (document.getElementById("dl_edit_"+acc_k).checked == true)
            && (document.getElementById("dl_view_"+acc_k).checked == true) && (document.getElementById("dl_delete_"+acc_k).checked == true)
            && (document.getElementById("dl_export_"+acc_k).checked == true))
            {
                //alert('select all');
                document.getElementById("dl_select_all_row_wise_"+acc_k).checked = true;
                document.getElementById("dl_select_all").checked = true;
            }         
        }

    });
    //Document Locker  check box end

        //Block Mapping Locker  check box start
        $('.bm_checkox').click(function() {
        var acc_row_count = $("#bm_row_count").val();
        for (let acc_k = 1; acc_k <= acc_row_count; acc_k++) 
        {
            if((document.getElementById("bm_add_"+acc_k).checked == false) || (document.getElementById("bm_edit_"+acc_k).checked == false)
            || (document.getElementById("bm_view_"+acc_k).checked == false) || (document.getElementById("bm_delete_"+acc_k).checked == false)
            || (document.getElementById("bm_export_"+acc_k).checked == false))
            {
                document.getElementById("bm_select_all_row_wise_"+acc_k).checked = false;
                document.getElementById("bm_select_all").checked = false; 
            }
            else if((document.getElementById("bm_add_"+acc_k).checked == true) &&  (document.getElementById("bm_edit_"+acc_k).checked == true)
            && (document.getElementById("bm_view_"+acc_k).checked == true) && (document.getElementById("bm_delete_"+acc_k).checked == true)
            && (document.getElementById("bm_export_"+acc_k).checked == true))
            {
                //alert('select all');
                document.getElementById("bm_select_all_row_wise_"+acc_k).checked = true;
                document.getElementById("bm_select_all").checked = true;
            }         
        }

    });
    //Block Mapping  check box end

    //Attendance Management  check box start
    $('.att_man_checkox').click(function() {
        var acc_row_count = $("#att_man_row_count").val();
        for (let acc_k = 1; acc_k <= acc_row_count; acc_k++) 
        {
            if((document.getElementById("att_man_add_"+acc_k).checked == false) || (document.getElementById("att_man_edit_"+acc_k).checked == false)
            || (document.getElementById("att_man_view_"+acc_k).checked == false) || (document.getElementById("att_man_delete_"+acc_k).checked == false)
            || (document.getElementById("att_man_export_"+acc_k).checked == false))
            {
                document.getElementById("att_man_select_all_row_wise_"+acc_k).checked = false;
                document.getElementById("att_man_select_all").checked = false; 
            }
            else if((document.getElementById("att_man_add_"+acc_k).checked == true) &&  (document.getElementById("att_man_edit_"+acc_k).checked == true)
            && (document.getElementById("att_man_view_"+acc_k).checked == true) && (document.getElementById("att_man_delete_"+acc_k).checked == true)
            && (document.getElementById("att_man_export_"+acc_k).checked == true))
            {
                //alert('select all');
                document.getElementById("att_man_select_all_row_wise_"+acc_k).checked = true;
                document.getElementById("att_man_select_all").checked = true;
            }         
        }

    });
    //Attendance Management  check box end


    //Leave Management  check box start
    $('.lm_checkox').click(function() {
        var acc_row_count = $("#lm_row_count").val();
        for (let acc_k = 1; acc_k <= acc_row_count; acc_k++) 
        {
            if((document.getElementById("lm_add_"+acc_k).checked == false) || (document.getElementById("lm_edit_"+acc_k).checked == false)
            || (document.getElementById("lm_view_"+acc_k).checked == false) || (document.getElementById("lm_delete_"+acc_k).checked == false)
            || (document.getElementById("lm_export_"+acc_k).checked == false))
            {
                document.getElementById("lm_select_all_row_wise_"+acc_k).checked = false;
                document.getElementById("lm_select_all").checked = false; 
            }
            else if((document.getElementById("lm_add_"+acc_k).checked == true) &&  (document.getElementById("lm_edit_"+acc_k).checked == true)
            && (document.getElementById("lm_view_"+acc_k).checked == true) && (document.getElementById("lm_delete_"+acc_k).checked == true)
            && (document.getElementById("lm_export_"+acc_k).checked == true))
            {
                //alert('select all');
                document.getElementById("lm_select_all_row_wise_"+acc_k).checked = true;
                document.getElementById("lm_select_all").checked = true;
            }         
        }

    });
    //Leave Management  check box end

    
    //Payroll Management  check box start
    $('.prm_checkox').click(function() {
        var acc_row_count = $("#prm_row_count").val();
        for (let acc_k = 1; acc_k <= acc_row_count; acc_k++) 
        {
            if((document.getElementById("prm_add_"+acc_k).checked == false) || (document.getElementById("prm_edit_"+acc_k).checked == false)
            || (document.getElementById("prm_view_"+acc_k).checked == false) || (document.getElementById("prm_delete_"+acc_k).checked == false)
            || (document.getElementById("prm_export_"+acc_k).checked == false))
            {
                document.getElementById("prm_select_all_row_wise_"+acc_k).checked = false;
                document.getElementById("prm_select_all").checked = false; 
            }
            else if((document.getElementById("prm_add_"+acc_k).checked == true) &&  (document.getElementById("prm_edit_"+acc_k).checked == true)
            && (document.getElementById("prm_view_"+acc_k).checked == true) && (document.getElementById("prm_delete_"+acc_k).checked == true)
            && (document.getElementById("prm_export_"+acc_k).checked == true))
            {
                //alert('select all');
                document.getElementById("prm_select_all_row_wise_"+acc_k).checked = true;
                document.getElementById("prm_select_all").checked = true;
            }         
        }

    });
    //Payroll Management  check box end

    $('.master_checkox').click(function() {
        var row_count = $("#master_row_count").val();
        for (let k = 1; k <= row_count; k++) 
        {
            if((document.getElementById("master_add_"+k).checked == false) || (document.getElementById("master_edit_"+k).checked == false)
            || (document.getElementById("master_view_"+k).checked == false) || (document.getElementById("master_delete_"+k).checked == false)
            || (document.getElementById("master_export_"+k).checked == false))
            {
                document.getElementById("select_all_row_wise_"+k).checked = false;
                document.getElementById("master_select_all").checked = false; 
            }
            else if((document.getElementById("master_add_"+k).checked == true) &&  (document.getElementById("master_edit_"+k).checked == true)
            && (document.getElementById("master_view_"+k).checked == true) && (document.getElementById("master_delete_"+k).checked == true)
            && (document.getElementById("master_export_"+k).checked == true))
            {
                document.getElementById("select_all_row_wise_"+k).checked = true;
                document.getElementById("master_select_all").checked = true;
            }         
        }
    });
});

</script>