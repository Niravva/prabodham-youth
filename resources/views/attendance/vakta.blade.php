<div class="modal-header">
    <h4 class="modal-title">Sabha Vakta</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg></span>
    </button>
</div>
<div class="modal-body">
    <form id="formAddEditVakta" action="{{ route('attendances.vaktaStoreUpdate', $attendance->id) }}" method="POST"
        enctype="multipart/form-data">
        @method('PUT')
        <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">

        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td>
                        <div class="form-group mt-3">
                            <label>1 Vakta Name</label>
                            <select name="vakta1" class="form-control select2bs4-members-livesearch" required>
                                <option value="{{ $attendance->vakta1 }}">
                                    {{ $attendance->vakta1 > 0 ? get_member_fullname($attendance->vakta1, false, true) : '' }}
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>1 Topic</label>
                            <input type="text" name="vakta1_topic" class="form-control"
                                value="{{ $attendance->vakta1_topic }}" required>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <label>2 Vakta Name</label>
                            <select name="vakta2" class="form-control select2bs4-members-livesearch">
                                <option value="{{ $attendance->vakta2 }}">
                                    {{ $attendance->vakta2 > 0 ? get_member_fullname($attendance->vakta2, false, true) : '' }}
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>2 Topic</label>
                            <input type="text" name="vakta2_topic" class="form-control"
                                value="{{ $attendance->vakta2_topic }}">
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <button id="btn-submit-hiddn" type="submit" style="display: none;">Save</button>
    </form>
</div>
<div class="modal-footer justify-content-end">
    <button id="btn-submit" type="button" class="btn btn-primary px-4">Save</button>
</div>

<script>
    $(document).ready(function() {
        $('#btn-submit').click(function() {
            $('#btn-submit-hiddn').trigger('click');
        });

        $("#formAddEditVakta").submit(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.
            var formData = $('#formAddEditVakta').serialize();

            // button disabled true
            $("#btn-submit").prop("disabled", true).html(
                '<i class="fas fa-spinner fa-spin"></i> Processing');
            $("#btn-cancel").prop("disabled", true);

            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('attendances.vaktaStoreUpdate', $attendance->id) }}",
                data: formData,
                dataType: 'json',
                success: function(response) {

                    // button disabled false
                    $("#btn-submit").prop("disabled", false).html('Save');
                    $("#btn-cancel").prop("disabled", false);

                    if (response.success == 1) {
                        $("span.invalid-feedback").remove();
                        DT_attendances.ajax.reload();
                        $('#modal-add-edit-vakta').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Vakta has been Add/Updated successfully.'
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

        $('.select2bs4-members-livesearch').select2({
            theme: 'bootstrap4',
            placeholder: '',
            ajax: {
                url: "{{ route('members.ajax-autocomplete-search') }}",
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
