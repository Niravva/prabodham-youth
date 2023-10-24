<form id="formProfile" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
    <div class="modal-header">
        <h4 class="modal-title">Profile</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg></span>
        </button>
    </div>
    <div class="modal-body">

        <div class="form-group">
            <label>{{ __('Name') }}*</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                placeholder="{{ __('John Doe') }}" value="{{ Auth::user()->name }}" />
            @error('name')
                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label>{{ __('Mobile Number') }}*</label>
            <input type="text" name="mobile_number" class="form-control @error('mobile_number') is-invalid @enderror"
                placeholder="{{ __('1234567890') }}" value="{{ Auth::user()->mobile_number }}" />
            @error('mobile_number')
                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label>{{ __('Email') }}*</label>
            <input type="text" name="email" class="form-control @error('email') is-invalid @enderror"
                placeholder="{{ __('Johndoe@example.com') }}" value="{{ Auth::user()->email }}" readonly />
            @error('email')
                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label>{{ __('Do you want to change the password?') }}</label>
            <div class="clearfix form-control pt-3">
                <div class="custom-control custom-radio d-inline mr-3">
                    <input type="radio" class="custom-control-input" id="radioChangePasswordYes" name="isChangePassword" value="Yes">
                    <label for="radioChangePasswordYes" class="custom-control-label">Yes</label>
                </div>
                <div class="custom-control custom-radio d-inline">
                    <input type="radio" class="custom-control-input" id="radioChangePasswordNo" name="isChangePassword" value="No">
                    <label for="radioChangePasswordNo" class="custom-control-label">No</label>
                </div>
            </div>
        </div>
        <div class="form-group change-password-wrap" style="display: none;">
            <label>{{ __('New Password') }}*</label>
            <input autocomplete="new-password" type="password" name="password" value=""
                class="form-control @error('password') is-invalid @enderror" />
            <small>Minimum 8 Characters Password required containing (A – Z,a – z,0 – 9, !, $, #, or %)</small>
            @error('password')
                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>


    </div>
    <div class="modal-footer justify-content-between">
        <button id="btn-cancel" type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button id="btn-submit" type="submit" class="btn btn-primary">Save Change</button>
    </div>
</form>
<script>
    $(document).ready(function() {
        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4',
        });

        
        $("#formProfile").submit(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.
            var formData = $('#formProfile').serialize();

            // button disabled true
            $("#btn-submit").prop("disabled", true).html(
                '<i class="fas fa-spinner fa-spin"></i> Processing');
            $("#btn-cancel").prop("disabled", true);

            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('profile.update') }}",
                data: formData,
                dataType: 'json',
                success: function(response) {

                    // button disabled false
                    $("#btn-submit").prop("disabled", false).html('Save');
                    $("#btn-cancel").prop("disabled", false);

                    if (response.success == 1) {
                        $("span.invalid-feedback").remove();
                        $('#modal-admin-profile').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Profile has been updated successfully.'
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Some fields are getting error, please check.'
                        });
                        printErrorMsg(response.errors);
                    }
                }
            });
        });

        $('input[name="isChangePassword"]').change(function() {
            var isChangePassword = $(this).val();
            //console.log(isChangePassword);
            if (isChangePassword == 'Yes') {
                $('.change-password-wrap').show();
            } else {
                $('.change-password-wrap').hide();
            }
        });
    });
</script>
