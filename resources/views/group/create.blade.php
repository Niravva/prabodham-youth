<div class="modal-header">
    <h4 class="modal-title">Add Group</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg></span>
    </button>
</div>
<div class="modal-body">
    <form id="formCreateGroup" action="{{ route('groups.store') }}" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>{{ __('Group Name') }}*</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}" />
            @error('name')
                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        @if (in_array(get_current_admin_level(), [
                'Super_Admin',
                'Country_Admin',
                'State_Admin',
                'Pradesh_Admin',
                'Zone_Admin',
            ]))
            <div class="form-group">
                <label>{{ __('Sabha') }}*</label>
                <select name="sabha_id" class="form-control select2bs4-sabhas-livesearch">
                    <option value=""></option>
                </select>
                @error('sabha_id')
                    <span class="invalid-feedback" role="alert" style="display: inline-block;">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        @endif


        <button id="btn-submit-hiddn" type="submit" style="display: none;">Save</button>
    </form>
</div>
<div class="modal-footer justify-content-between">
    <button id="btn-cancel" type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
    <button id="btn-submit" type="submit" class="btn btn-primary px-4">Save</button>
</div>

<script>
    $(document).ready(function() {

        $('#btn-submit').click(function() {
            $('#btn-submit-hiddn').trigger('click');
        });


        $("#formCreateGroup").submit(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.
            var formData = $('#formCreateGroup').serialize();

            // button disabled true
            $("#btn-submit").prop("disabled", true).html(
                '<i class="fas fa-spinner fa-spin"></i> Processing');
            $("#btn-cancel").prop("disabled", true);

            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('groups.store') }}",
                data: formData,
                dataType: 'json',
                success: function(response) {

                    // button disabled false
                    $("#btn-submit").prop("disabled", false).html('Save');
                    $("#btn-cancel").prop("disabled", false);

                    if (response.success == 1) {
                        $("span.invalid-feedback").remove();
                        DT_groups.ajax.reload();
                        $('#modal-group-create').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Group has been added successfully.'
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

        $('.select2bs4-sabhas-livesearch').select2({
            theme: 'bootstrap4',
            placeholder: '',
            ajax: {
                url: "{{ route('sabhas.ajax-autocomplete-search') }}",
                data: function(params) {
                    var query = {
                        q: params.term,
                        whereSql: ''
                    }
                    return query;
                },
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });

    });
</script>
