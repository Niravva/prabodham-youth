<label>{{ __('City') }}*</label>
<select name="city_id" id="select_city_id" class="form-control">
    <option value="">Select City</option>
    @foreach ($cities as $item)
        <option value="{{ $item->id }}" {{ $item->id == $selected ? 'selected' : '' }}>{{ $item->name }}</option>
    @endforeach
</select>
<script>
    $(document).ready(function() {

        //Initialize Select2 Elements
        $('#select_city_id').select2({
            theme: 'bootstrap4',
        });
    });
</script>
