<div class="modal-header">
    <h4 class="modal-title">Add Sabha</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg></span>
    </button>
</div>
<div class="modal-body">
    <form id="formCreateSabha" action="{{ route('sabhas.store') }}" method="POST" enctype="multipart/form-data">
        <div class="row">
            @if (in_array(get_current_admin_level(), ['Super_Admin', 'Country_Admin', 'State_Admin', 'Pradesh_Admin']))
                <div class="col-md-3">
                    <div class="form-group depend_admin_type zone-list-wrap">
                        <label>{{ __('Select Zone') }}*</label>
                        <select name="zone_id" class="form-control select2bs4-zones-livesearch">

                        </select>
                        @error('zone_id')
                            <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            @endif
            <div class="col-4 col-md-2">
                <div class="form-group">
                    <label>{{ __('Sabha Code') }}*</label>
                    <input type="text" name="sabha_code"
                        class="form-control @error('sabha_code') is-invalid @enderror"
                        value="{{ old('sabha_code') }}" />
                    @error('sabha_code')
                        <span class="invalid-feedback" role="alert" style="display: inline-block;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col col-md-3">
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
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ __('Sabha Head') }}</label>
                    <select name="sabha_head_id" class="form-control select2bs4-members-livesearch">
                    </select>
                    @error('sabha_head_id')
                        <span class="invalid-feedback" role="alert" style="display: inline-block;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col col-md-3">
                <div class="form-group">
                    <label>{{ __('Sabha Type') }}*</label>
                    <select name="sabha_type" class="form-control select2bs4">
                        <option value=""></option>
                        @foreach (get_sabha_types() as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                    @error('sabha_type')
                        <span class="invalid-feedback" role="alert" style="display: inline-block;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col col-md-3">
                <div class="form-group">
                    <label>{{ __('Occurance') }}*</label>
                    <select name="occurance" class="form-control select2bs4">
                        <option value=""></option>
                        @foreach (get_sabha_occurrences() as $occurance)
                            <option value="{{ $occurance }}">{{ $occurance }}</option>
                        @endforeach
                    </select>
                    @error('occurance')
                        <span class="invalid-feedback" role="alert" style="display: inline-block;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">

                <div class="row">
                    <div class="col-5 col-md-4">
                        <div class="form-group">
                            <label>{{ __('Day') }}*</label>
                            <select name="sabha_day" class="form-control select2bs4">
                                <option value=""></option>
                                @foreach (get_sabha_days() as $dayNum => $sabha_day)
                                    <option value="{{ $dayNum }}">{{ $sabha_day }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col col-md-3">
                        <div class="form-group">
                            <label>{{ __('Hour') }}*</label>
                            <select name="sabha_hour" class="form-control select2bs4">
                                <option value=""></option>
                                @foreach (get_sabha_hours() as $hour)
                                    <option value="{{ $hour }}">{{ $hour }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col col-md-3">
                        <div class="form-group">
                            <label>{{ __('Minute') }}*</label>
                            <select name="sabha_minute" class="form-control select2bs4">
                                <option value=""></option>
                                @foreach (get_sabha_minutes() as $minute)
                                    <option value="{{ $minute }}">{{ $minute }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div class="row">
            <div class="col-4 col-md col-sm-12">
                <div class="form-group">
                    <label>{{ __('Flat No') }}</label>
                    <input type="text" name="flat_no" class="form-control @error('flat_no') is-invalid @enderror"
                        value="{{ old('flat_no') }}" />
                    @error('flat_no')
                        <span class="invalid-feedback" role="alert" style="display: inline-block;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col col-md col-sm-12">
                <div class="form-group">
                    <label>{{ __('Building Name') }}</label>
                    <input type="text" name="building_name"
                        class="form-control @error('building_name') is-invalid @enderror"
                        value="{{ old('building_name') }}" />
                    @error('building_name')
                        <span class="invalid-feedback" role="alert" style="display: inline-block;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-md col-sm-12">
                <div class="form-group">
                    <label>{{ __('Landmark') }}</label>
                    <input type="text" name="landmark"
                        class="form-control @error('landmark') is-invalid @enderror" value="{{ old('landmark') }}" />
                    @error('landmark')
                        <span class="invalid-feedback" role="alert" style="display: inline-block;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col col-md col-sm-12">
                <div class="form-group">
                    <label>{{ __('Street Name') }}</label>
                    <input type="text" name="street_name"
                        class="form-control @error('street_name') is-invalid @enderror"
                        value="{{ old('street_name') }}" />
                    @error('street_name')
                        <span class="invalid-feedback" role="alert" style="display: inline-block;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-4 col-md col-sm-12">
                <div class="form-group">
                    <label>{{ __('Postcode') }}</label>
                    <input type="text" name="postcode"
                        class="form-control @error('postcode') is-invalid @enderror" value="{{ old('postcode') }}" />
                    @error('postcode')
                        <span class="invalid-feedback" role="alert" style="display: inline-block;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col col-md-3">
                <div class="form-group">
                    <label>{{ __('Latitude') }}</label>
                    <input type="text" name="latitude"
                        class="form-control @error('latitude') is-invalid @enderror" value="{{ old('latitude') }}" />
                    @error('latitude')
                        <span class="invalid-feedback" role="alert" style="display: inline-block;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col col-md-3">
                <div class="form-group">
                    <label>{{ __('Longitude') }}</label>
                    <input type="text" name="longitude"
                        class="form-control @error('longitude') is-invalid @enderror"
                        value="{{ old('longitude') }}" />
                    @error('longitude')
                        <span class="invalid-feedback" role="alert" style="display: inline-block;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
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

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4',
            placeholder: ''
        });

        $("#formCreateSabha").submit(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.
            var formData = $('#formCreateSabha').serialize();

            // button disabled true
            $("#btn-submit").prop("disabled", true).html(
                '<i class="fas fa-spinner fa-spin"></i> Processing');
            $("#btn-cancel").prop("disabled", true);

            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('sabhas.store') }}",
                data: formData,
                dataType: 'json',
                success: function(response) {

                    // button disabled false
                    $("#btn-submit").prop("disabled", false).html('Save');
                    $("#btn-cancel").prop("disabled", false);

                    if (response.success == 1) {
                        $("span.invalid-feedback").remove();
                        DT_sabhas.ajax.reload();
                        $('#modal-sabha-create').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Sabha has been added successfully.'
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

        //Member
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

        //Zone
        $('.select2bs4-zones-livesearch').select2({
            theme: 'bootstrap4',
            placeholder: '',
            ajax: {
                url: "{{ route('zones.ajax-autocomplete-search') }}",
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
