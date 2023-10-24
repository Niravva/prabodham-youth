<label>{{ __('Country') }}*</label>
<select name="country_id" id="select_country_id" class="form-control">
    <option value="">Select Country</option>
    @foreach ($countries as $item)
        <option value="{{ $item->id }}" {{ $item->id == $selected ? 'selected' : '' }}>{{ $item->name }}</option>
    @endforeach
</select>
<script>
    $(document).ready(function() {
        $("#select_country_id").unbind().change(function() {
            var countryId = $(this).val();
            $(".state_dropdwon_wrap").html('');
            $.ajax({
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('states.index') }}",
                data: {
                    country_id: countryId,
                    return_json: '1',
                    selected: (typeof selectedStateId === "undefined" ? 0 : selectedStateId)
                },
                success: function(result) {
                    $('.state_dropdwon_wrap').html(result.html);
                }
            });
        });

        //Initialize Select2 Elements
        $('#select_country_id').select2({
            theme: 'bootstrap4',
        });

        if (typeof selectedStateId !== "undefined") {
            $('#select_country_id').trigger('change');
        }
    });
</script>
