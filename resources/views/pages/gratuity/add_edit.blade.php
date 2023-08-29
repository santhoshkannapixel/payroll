@extends('layouts.template')
@section('breadcrum')
    @include('layouts.parts.breadcrum')
@endsection
@section('content')
    <div class="card">
        <div class="mx-9 pt-9">
            <h3>{{ $page_title }}</h3>
        </div>
        <div class="card-header border-0 pt-6">

            <div class="card-title">

            </div>
            <div class="card-toolbar">

            </div>
        </div>

        <div class="card-body py-4">
            <form id="gratuity_form" action="{{ route('gratuity.preview') }}" class="card " method="POST" target="_blank">
                @csrf
                <div class="col-sm-8">
                    <div class="row border border-1 p-4">
                        <div class="row  mt-3">
                            <div class="col-sm-6">
                                <label for="" class="required">Select Staff</label>
                            </div>
                            <div class="col-sm-6">
                                <select name="staff_id" id="staff_id" onchange="getStaffGratuityFormDetails(this.value)"
                                    class="form-control form-control-sm" required>
                                    <option value="">--select--</option>
                                    @if (isset($user) && !empty($user))
                                        @foreach ($user as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }} -
                                                {{ $item->institute_emp_code }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row" id="ajax_gratuity_pane">

                            @include('pages.gratuity._ajax_form')
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-3">
                            <label for="">Date of issue of gratuity</label>
                        </div>
                        <div class="col-sm-3">
                            <input type="date" name="date_of_issue" id="date_of_issue"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col-sm-3">
                            <textarea name="issue_remarks" id="issue_remarks" cols="30" rows="1" class="form-control form-control-sm"></textarea>
                        </div>
                        <div class="col-sm-3">
                            <input type="file" name="issue_attachment" id="issue_attachment"
                                class="form-control form-contro-sm">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-3">
                            <label for=""> Mode of Payment </label>
                        </div>
                        <div class="col-sm-3">
                            <select name="mode_of_payment" id="mode_of_payment" class="form-control form-control-sm">
                                <option value="">--select--</option>
                                <option value="cash">Cash</option>
                                <option value="cheque">Cheque</option>
                                <option value="net_banking">Net Banking</option>
                                <option value="upi">UPI</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <textarea name="payment_remarks" id="payment_remarks" cols="30" rows="1"
                                class="form-control form-control-sm"></textarea>
                        </div>
                        <div class="col-sm-3">
                            <input type="file" name="payment_attachment" id="payment_attachment"
                                class="form-control form-contro-sm">
                        </div>
                    </div>
                    <div class="row  mt-3">
                        <div class="col-sm-6">
                            <label for="" class="required"> Verification Status </label>
                        </div>
                        <div class="col-sm-6">
                            <input type="radio" name="verification_status" value="pending" checked id="pending">
                            <label for="pending"> Pending </label>
                            <input type="radio" name="verification_status" value="verified" id="verified">
                            <label for="verified"> Verified </label>
                            <input type="radio" name="verification_status" value="failed" id="failed">
                            <label for="failed"> Failed </label>
                        </div>
                    </div>
                    <div class="row text-end my-5">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-dark btn-sm"> Cancel </button>
                            <button type="submit" class="btn btn-info btn-sm"> Generate Preview </button>
                            <button type="button" class="btn btn-primary btn-sm" onclick="submitGratuityCalculation()"> Save Gratuity </button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="row">

                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('add_on_script')
    <script>
        function getStaffGratuityFormDetails(staff_id) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('gratuity.ajax.form') }}",
                type: 'POST',
                data: {
                    staff_id: staff_id,
                    page_type: '{{ $page_type }}'
                },
                success: function(res) {
                    $('#ajax_gratuity_pane').html(res);
                }
            })

        }

        function qulifyingServiceAmountCalculation() {

            var gross_service_year = $('#gross_service_year').val();
            var gross_service_month = $('#gross_service_month').val();
            var extraordinary_leave = $('#extraordinary_leave').val();
            var suspension_qualifying_service = $('#suspension_qualifying_service').val();
            // var net_qualifying_service = $('#net_qualifying_service').val();

            var grat_amount = tot_emul_amount = gs_year = ts_month = gs_month = ex_leave = s_server = net_service = qlify_service = 0;
            if (gross_service_year != '' && gross_service_year != undefined && gross_service_year != 'undefined') {
                gs_year = parseInt(gross_service_year);
            }
            if (gross_service_month != '' && gross_service_month != undefined && gross_service_month != 'undefined') {
                gs_month = parseInt(gross_service_month);
            }
            if (extraordinary_leave != '' && extraordinary_leave != undefined && extraordinary_leave != 'undefined') {
                ex_leave = parseInt(extraordinary_leave);
            }
            if (suspension_qualifying_service != '' && suspension_qualifying_service != undefined &&
                suspension_qualifying_service != 'undefined') {
                s_server = parseInt(suspension_qualifying_service);
            }
            if (gs_year > 0) {
                ts_month = (gs_year * 12) + gs_month;
                net_service = ts_month - (ex_leave + s_server);
                $('#net_qualifying_service').val(net_service);
                qlify_service = net_service * 2;
                $('#qualify_service_expressed').val(qlify_service);
            }

            setTimeout(() => {
                var total_emuluments = $('#total_emuluments').val();
                
                if (total_emuluments != '' && total_emuluments != undefined && total_emuluments != 'undefined') {
                    tot_emul_amount = parseInt(total_emuluments);
                }
                grat_amount = (1 / 4) * qlify_service * tot_emul_amount;
    
                $('#gratuity_calculation').val(grat_amount.toFixed(2));
                $('#total_payable_gratuity').val(grat_amount.toFixed(2));
            }, 300);

        }
       

        function deleteCareer(id) {
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
                        url: "{{ route('career.delete') }}",
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
