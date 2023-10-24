<label>{{ __('State') }}*</label>
<select name="state_id" id="select_state_id" class="form-control">
    <option value="">Select State</option>
    @foreach ($states as $item)
        <option value="{{ $item->id }}" {{ $item->id == $selected ? 'selected' : '' }}>{{ $item->name }}</option>
    @endforeach
</select>
<script>
    $(document).ready(function() {
        $("#select_state_id").unbind().change(function() {
            var stateId = $(this).val();
            $(".city_dropdwon_wrap").html('');
            $.ajax({
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('cities.index') }}",
                data: {
                    state_id: stateId,
                    return_json: '1',
                    selected: (typeof selectedCityId === "undefined" ? 0 : selectedCityId)
                },
                success: function(result) {
                    $('.city_dropdwon_wrap').html(result.html);
                }
            });
        });

        //Initialize Select2 Elements
        $('#select_state_id').select2({
            theme: 'bootstrap4',
        });

        if(typeof selectedCityId !== "undefined"){
            $('#select_state_id').trigger('change');
        }
    });
</script>
