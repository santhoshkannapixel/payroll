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

        <div class="row  pt-6 px-10">
            <div class="col-3">
                <!--end::Svg Icon-->
                <input type="text" data-kt-user-table-filter="search" id="staff_name_id"
                    class="typeahead  form-control form-control-solid w-180px  " placeholder="Search Name or Staff ID">
            
            </div>
            <div class="col-2">
                <select class="form-select ms-4" id="search_institutions"  >
                    <option value="">Institutions</option>
                    @foreach ($institution as $ins_value)
                        <option value="{{$ins_value->id}}">{{$ins_value->name}}</option>                        
                    @endforeach
                   
                </select>
              
            </div>
         
            <div class="col-2">
                <select class="form-select ms-4" id="search_institutions " >
                    <option value="">Department </option>
                    @foreach ($department as $department_value)
                        <option value="{{$department_value->id}}">{{$department_value->name}}</option>                        
                    @endforeach
                </select>
          </div>
            <div class="col-2">
                <select class="form-select ms-4" id="search_institutions "  >
                    <option value="">Designation</option>
                    @foreach ($designation as $desig_value)
                        <option value="{{$desig_value->id}}">{{$desig_value->name}}</option>                        
                    @endforeach
                </select>
          </div>
        
      <div class="col-2">
        <button type="button" class="btn btn-primary ms-7">Search</button>
  </div>
        </div>

        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
             
            <!--begin::Card title-->
           
            <div class="card-title">
                <h4 class="mt-5"><strong>Document Locker</strong></h4>
            </div>
            <div class="count_deatils mt-5">
                <div class="row m-0">
                    <div class="col-md-3 staff_count_dl">
                        <p class="ss_count_text">Total Number of Staff</p>
                        <p class="ss_count">{{$user_count}} </p>
                        <img alt="Logo" src="{{ asset('assets/media/document/no_of_staff.png') }}"
                        class="logo document_images" />
                    </div>                    
                    <div class="col-md-3 staff_count_dl">
                        <p class="ss_count_text">Total Number of Documents Uploaded</p>
                        <p class="ss_count1">{{$total_documents}} </p>
                        <img alt="Logo" src="{{ asset('assets/media/document/document_upload.png') }}"
                        class="logo document_images1" />
                    </div>
                    <div class="col-md-3 staff_count_dl ">
                        <p class="ss_count_text">Documents Review Pending </p>
                        <p class="ss_count1">{{$review_pending_documents}} </p>
                        <img alt="Logo" src="{{ asset('assets/media/document/document_pending.png') }}"
                        class="logo document_images1" />
                    </div>
                </div>
            </div>
        </div>
            <!--begin::Card title-->
            <!--begin::Card toolbar-->
            

        <div class="card-body p-10">
            <div class="col-12">
                <div id="kt_table_users_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                      <table id="document_locker" class="table align-middle text-center table-hover table-bordered table-striped fs-7 no-footer"
                      style="width:100%">
                      <thead class="bg-primary">
                        <tr class="text-start text-center text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="text-center text-white" style="width:85px;">Staff  ID</th>
                                <th class="text-center text-white">Staff Name</th>
                                <th class="text-center text-white">Department</th>
                                <th class="text-center text-white">Designation</th>
                                <th class="text-center text-white">Total Documents</th>                                
                                <th class="text-center text-white">Aprroved Documents</th>
                                <th  class="text-center text-white">Pending Documents</th>
                                <th  class="text-center text-white">Action</th>
                            </tr>
                           
                        </thead>
                        <tbody>
                           
                                @foreach ($user as $users )
                                <tr>
                                <td>{{$users->emp_code}}</td>
                                <td>{{$users->name}}</td>
                                <td>{{$users->position->department->name ??''}}</td>
                                <td>{{$users->position->designation->name ??''}}</td>
                                <td>{{$users->staffDocuments->count() ??''}}</td>
                                <td>{{$users->staffDocumentsApproved->count() ??''}}</td>
                                <td>{{$users->staffDocumentsPending->count() ??''}}</td>
                               
                                <td><a href=" {{route('user.dl_view', ['id' => $users->id])}}" class="btn btn-icon btn-active-info btn-light-info mx-1 w-30px h-30px"> 
                                    <i class="fa fa-eye"></i>
                                </a></td>
                                
                            </tr>
                                @endforeach
                                
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->

    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
@endsection

@section('add_on_script')

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    <script>   


$(document).ready(function () {
    $('#document_locker').DataTable({   
        "scrollX": true
    });
});
  var route = "{{ url('autocomplete-search') }}";
        $('#staff_name_id').typeahead({
            source: function (query, process) {
                return $.get(route, {
                    query: query
                }, function (data) {
                    console.log(data);
                    //var details=data[name]+'-'+data[emp_code]; console.log(details);
                    return process(data);
                });
            }
        });
        


 
 var dtTable = $('#staff_table_data').DataTable({

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
    }
  
    // {
    //     data: 'action',
    //     name: 'action',
    //     orderable: false,
    //     searchable: false
    // },
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
