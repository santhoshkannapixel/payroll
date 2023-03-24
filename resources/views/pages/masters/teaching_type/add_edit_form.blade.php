<form action="" class="" id="dynamic_form">
   
    <div class="fv-row form-group mb-10">
        <label class="form-label required" for="">
            Teaching Type
        </label>
        <div>
            <input type="text" class="form-control" name="teaching_type" id="teaching_type" required >
        </div>
    </div>
   
    <div class="form-group mb-10">
    </div>
    <div class="form-group mb-10 text-end">
        <button type="button" class="btn btn-light-primary" data-bs-dismiss="modal"> Cancel </button>
        <button type="button" class="btn btn-primary" id="form-submit-btn"> 
            <span class="indicator-label">
                Submit
            </span>
            <span class="indicator-progress">
                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
    </div>
</form>

<script>

var KTAppEcommerceSavePlace = function () {

    const handleSubmit = () => {
        
        let validator;
        
        const form = document.getElementById('dynamic_form');
        const submitButton = document.getElementById('form-submit-btn');

        validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'teaching_type': {
						validators: {
							notEmpty: {
								message: 'Teaching type is required'
							},
						}
					},
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );
        
        submitButton.addEventListener('click', e => {
            e.preventDefault();
            
            if (validator) {
                validator.validate().then(function (status) {

                    if (status == 'Valid') {

                        var forms = $('#dynamic_form')[0];
                        var formData = new FormData(forms);
                        $.ajax({
                            url:"{{ route('save.teaching_type') }}",
                            type:"POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(res) {
                                
                                submitButton.disabled = false;
                                submitButton.removeAttribute('data-kt-indicator');
                                if( res.error == 1 ) {
                                    if( res.message ) {
                                        res.message.forEach(element => {
                                            toastr.error("Error", element);
                                        });
                                    }
                                } else{
                                    toastr.success("Teaching Type added successfully");
                                    if( res.inserted_data ) {
                                        $('#kt_dynamic_app').modal('hide');
                                        $('#teaching_type_id').append(`<option value="${res.inserted_data.id}">${res.inserted_data.name}</option>`)
                                        $('#teaching_type_id').val(res.inserted_data.id).trigger('change');
                                    }
                                }
                            }
                        })

                    } 
                });
            }
        })
    }

    return {
        init: function () {
            handleSubmit();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTAppEcommerceSavePlace.init();
});
    
    
</script>