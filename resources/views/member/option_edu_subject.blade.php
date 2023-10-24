<select name="edu_subject" id="edu_subject" class="form-control">
    <option value="">Select</option>
    @foreach (get_qualification_list()[$index]['subjects'] as $row)
        <option value="{{ $row }}" {{ $row == $selected ? 'selected' : '' }}>
            {{ $row }}
        </option>
    @endforeach
</select>
@error('edu_subject')
    <span class="invalid-feedback" role="alert" style="display: inline-block;">
        <strong>{{ $message }}</strong>
    </span>
@enderror
<script>
    $(document).ready(function() {
        //Initialize Select2 Elements
        $('#edu_subject').select2({
            theme: 'bootstrap4',
        });

        $("select[name='edu_subject']").change(function() {
            var myVal = $(this).val();
            if (myVal == 'Others') {
                $('.edu_subject_other').show();
            } else {
                $('.edu_subject_other').hide();
            }
        });
        $("select[name='edu_subject']").trigger('change');
    });
</script>
