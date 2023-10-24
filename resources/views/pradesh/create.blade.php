<form id="formCreatePradesh" action="{{ route('pradeshs.store') }}" method="POST" enctype="multipart/form-data">
    <div class="modal-header">
        <h4 class="modal-title">Add Pradesh</h4>
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
                value="{{ old('name') }}" />
            @error('name')
                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        @if (get_current_admin_level() == 'Super_Admin')
            <div class="form-group country_dropdwon_wrap">
                {!! $country_dropdwon !!}
            </div>
            <div class="form-group state_dropdwon_wrap"></div>
            <div class="form-group city_dropdwon_wrap"></div>
        @elseif (get_current_admin_level() == 'Country_Admin')
            <div class="form-group state_dropdwon_wrap">
                {!! $state_dropdwon !!}
            </div>
            <div class="form-group city_dropdwon_wrap"></div>
        @elseif (get_current_admin_level() == 'State_Admin')
            <div class="form-group city_dropdwon_wrap">
                {!! $city_dropdwon !!}
            </div>
        @endif
        <script>
            var selectedStateId = undefined;
            var selectedCityId = undefined;
        </script>


    </div>
    <div class="modal-footer justify-content-between">
        <button id="btn-cancel" type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button id="btn-submit" type="submit" class="btn btn-primary px-4">Save</button>
    </div>
</form>
<script>
    $(document).ready(function() {
        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4',
        });

        $("#formCreatePradesh").submit(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.
            var formData = $('#formCreatePradesh').serialize();

            // button disabled true
            $("#btn-submit").prop("disabled", true).html(
                '<i class="fas fa-spinner fa-spin"></i> Processing');
            $("#btn-cancel").prop("disabled", true);

            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('pradeshs.store') }}",
                data: formData,
                dataType: 'json',
                success: function(response) {

                    // button disabled false
                    $("#btn-submit").prop("disabled", false).html('Save');
                    $("#btn-cancel").prop("disabled", false);

                    if (response.success == 1) {
                        $("span.invalid-feedback").remove();
                        DT_pradeshs.ajax.reload();
                        $('#modal-pradesh-create').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Pradesh has been added successfully.'
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
    });
</script>
