<!--begin::Navbar-->
@extends('layouts.template')
@section('breadcrum')
    @include('layouts.parts.breadcrum')
@endsection
@section('content')
    <!--begin::Card-->
    <div class="card" >
        <div class="card-title">
            <h4 class="ms-10 mt-10"><strong>Search Staff</strong></h4>
        </div>
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
             
            <!--begin::Card title-->
            <div class="card-title">
               
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                            <path
                                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                fill="currentColor"></path>
                        </svg>
                    </span>
                    
                    <!--end::Svg Icon-->
                    <input type="text" data-kt-user-table-filter="search" id="institution_datable_search123"
                        class="typeahead  form-control form-control-solid w-300px ps-14" placeholder="Search Staff Name or Staff ID">
                    <select class="form-select ms-4" id="search_institutions" style="width:140px;" >
                        <option>Institutions</option>
                        <option>Select SelectSelectSelectSelectSelectSelectSelect </option>
                    </select>
                    <select class="form-select ms-4" id="search_institutions w-200px" style="width:140px;" >
                        <option>Staff Type </option>
                        <option>Select SelectSelectSelectSelectSelectSelectSelect </option>
                    </select>
                    <select class="form-select ms-4" id="search_institutions w-200px" style="width:140px;" >
                        <option>Designation</option>
                        <option>Select SelectSelectSelectSelectSelectSelectSelect </option>
                    </select>
                    <select class="form-select ms-4" id="search_institutions w-200px" style="width:140px;" >
                        <option>Gender </option>
                        <option>Select SelectSelectSelectSelectSelectSelectSelect </option>
                    </select>
                    <button type="button" class="btn btn-primary ms-7">Search</button>
                    </div>
                <!--end::Search-->
            </div>
            <div class="card-title">
                <h4 class="mt-5"><strong>Document Locker</strong></h4>
            </div>
            <div class="count_deatils mt-5">
                <div class="row m-0">
                    <div class="col-md-4 staff_count_dl">
                        <p class="ss_count_text">Total Number of Staff</p>
                        <p class="ss_count">650 </p>
                        <img alt="Logo" src="{{ asset('assets/media/document/no_of_staff.png') }}"
                        class="logo document_images" />
                    </div>
                    <div class="col-md-4 staff_count_dl">
                        <p class="ss_count_text">Total Number of Documents Uploaded</p>
                        <p class="ss_count1">650 </p>
                        <img alt="Logo" src="{{ asset('assets/media/document/document_upload.png') }}"
                        class="logo document_images1" />
                    </div>
                    <div class="col-md-4 staff_count_dl ">
                        <p class="ss_count_text">Documents Review Pending </p>
                        <p class="ss_count1">650 </p>
                        <img alt="Logo" src="{{ asset('assets/media/document/document_pending.png') }}"
                        class="logo document_images1" />
                    </div>
                </div>
            </div>
            <!--begin::Card title-->
            <!--begin::Card toolbar-->
            

        <div class="card-body p-0">
            <div class="col-11">
                <div id="kt_table_users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <table class="table align-middle table-hover table-row m-0-dashed fs-6 dataTable no-footer"
                        id="staff_table">
                        <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
    
                                <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_table_users" row m-0span="1"
                                    colspan="1" 
                                    aria-label="User: activate to sort column ascending">
                                    Staff ID
                                </th>
                                <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_table_users" row m-0span="1"
                                    colspan="1" 
                                    aria-label="Role: activate to sort column ascending">
                                    Staff Name
                                </th>
                                <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_table_users" row m-0span="1"
                                    colspan="1" 
                                    aria-label="Last login: activate to sort column ascending">
                                    Staff Type
                                </th>
                                <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_table_users" row m-0span="1"
                                    colspan="1" 
                                    aria-label="Two-step: activate to sort column ascending">
                                    Documents Uploaded
                                </th>
                                <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_table_users" row m-0span="1"
                                    colspan="1" 
                                    aria-label="Joined Date: activate to sort column ascending">
                                    Prerequisite Documents
                                </th>                              
                                <th class="text-end min-w-100px sorting_disabled" row m-0span="1" colspan="1"
                                    aria-label="Actions">
                                    Actions
                                </th>
                            </tr>
                            <tr>
                                <th class="text-end min-w-100px sorting_disabled"  colspan="4"
                                aria-label="Actions">                               
                                </th>
                                <th class="min-w-125px sorting table_head_color" tabindex="0" aria-controls="kt_table_users"                                
                                aria-label="Joined Date: activate to sort column ascending" >
                                    <strong>SSLC Certificate </strong>
                                </th>
                                <th class="min-w-125px sorting table_head_color" tabindex="0" aria-controls="kt_table_users"                                
                                aria-label="Joined Date: activate to sort column ascending" >
                                   <strong> HSC Certificate </strong>
                                </th>
                                <th class="min-w-125px sorting table_head_color" tabindex="0" aria-controls="kt_table_users"                               
                                aria-label="Joined Date: activate to sort column ascending" >
                                    <strong> UG Certificate </strong>
                                </th>
                                <th class="min-w-125px sorting table_head_color" tabindex="0" aria-controls="kt_table_users" 
                                aria-label="Joined Date: activate to sort column ascending" >
                                    <strong> PG Certificate </strong>
                                </th>
                                <th class="min-w-125px sorting table_head_color" tabindex="0" aria-controls="kt_table_users" 
                                aria-label="Joined Date: activate to sort column ascending" >
                                    <strong> PhD/M.Phil. Certificates </strong>
                                </th>  
                            </tr>
                        </thead>
    
                        <tbody class="text-gray-600 fw-bold">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
@endsection

@section('add_on_script')

    <script>   
 
 var dtTable = $('#staff_table').DataTable({

processing: true,
serverSide: true,
type: 'POST',
ajax: {
    "url": "{{ route('user.document_locker') }}",
    "data": function(d) {
      //  console.log(d);
       // d.datatable_search = $('#staff_datable_search').val();
    }
},

columns: [{
        data: 'name',
        name: 'name'
    },
    {
        data: 'email',
        name: 'email'
    },
    {
        data: 'verification_status',
        name: 'verification_status'
    },
    {
        data: 'status',
        name: 'status'
    },
    {
        data: 'emp_code',
        name: 'emp_code'
    },
    {
        data: 'addedBy',
        name: 'addedBy'
    },
    {
        data: 'profile_status',
        name: 'profile_status'
    },
    {
        data: 'academic_id',
        name: 'academic_id'
    },
    {
        data: 'institute_id',
        name: 'institute_id'
    },
    {
        data: 'first_name',
        name: 'first_name'
    },
    {
        data: 'last_name',
        name: 'last_name'
    },
  
    {
        data: 'action',
        name: 'action',
        orderable: false,
        searchable: false
    },
],
language: {
    paginate: {
        next: '<i class="fa fa-angle-right"></i>', // or '→'
        previous: '<i class="fa fa-angle-left"></i>' // or '←' 
    }
},
"aaSorting": [],
"pageLength": 25
});

$('.dataTables_wrapper').addClass('position-relative');
$('.dataTables_info').addClass('position-absolute');
$('.dataTables_filter label input').addClass('form-control form-control-solid w-250px ps-14');
$('.dataTables_filter').addClass('position-absolute end-0 top-0');
$('.dataTables_length label select').addClass('form-control form-control-solid');

/*document.querySelector('#staff_datable_search').addEventListener("keyup", function(e) {
    dtTable.draw();
}),*/

$('#search-form').on('submit', function(e) {
    dtTable.draw();
    e.preventDefault();
});
$('#search-form').on('reset', function(e) {
$('select[name=filter_status]').val(0).change();

dtTable.draw();
e.preventDefault();
});

        function institutionChangeStatus(id, status) {

            Swal.fire({
                text: "Are you sure you would like to change status?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, Change it!",
                cancelButtonText: "No, return",
                customClass: {
                    confirmButton: "btn btn-danger",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function(result) {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: "{{ route('institutions.change.status') }}",
                        type: 'POST',
                        data: {
                            id: id,
                            status: status
                        },
                        success: function(res) {
                            dtTable.ajax.reload();
                            Swal.fire({
                                title: "Updated!",
                                text: res.message,
                                icon: "success",
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-success"
                                },
                                timer: 3000
                            });

                        },
                        error: function(xhr, err) {
                            if (xhr.status == 403) {
                                toastr.error(xhr.statusText, 'UnAuthorized Access');
                            }
                        }
                    });
                }
            });
        }

        $('#kt_common_add_form').on('hidden.bs.modal', function() {
            $(this).find('form').trigger('reset');
        })

        function getInstituteModal( id = '') {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('institutions.add_edit') }}",
                type: 'POST',
                data: {
                    id: id,
                },
                success: function(res) {
                    $('#kt_dynamic_app').modal('show');
                    $('#kt_dynamic_app').html(res);
                }
            })

        }

        function deleteInstitution(id) {
            Swal.fire({
                text: "Are you sure you would like to delete record?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, Delete it!",
                cancelButtonText: "No, return",
                customClass: {
                    confirmButton: "btn btn-danger",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function(result) {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: "{{ route('institutions.delete') }}",
                        type: 'POST',
                        data: {
                            id: id,
                        },
                        success: function(res) {
                            dtTable.ajax.reload();
                            Swal.fire({
                                title: "Updated!",
                                text: res.message,
                                icon: "success",
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-success"
                                },
                                timer: 3000
                            });

                        },
                        error: function(xhr, err) {
                            if (xhr.status == 403) {
                                toastr.error(xhr.statusText, 'UnAuthorized Access');
                            }
                        }
                    });
                }
            });
        }
       
       


    </script>
@endsection