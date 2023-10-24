<div class="modal-header">
    <h4 class="modal-title">Update Admin</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </span>
    </button>
</div>
<div class="modal-body">
    <form id="formEditAdmin" action="{{ route('admins.update', $admin->id) }}" method="POST"
        enctype="multipart/form-data">
        @method('PUT')
        @if (get_current_admin_level() == 'Group_Admin')
            <input type="hidden" name="admin_type" value="Followup_Admin">
            <input type="hidden" name="sabha_id" value="{{ Auth::user()->sabha_id }}">
            <input type="hidden" name="group_id" value="{{ Auth::user()->group_id }}">
        @else
            <div class="form-group">
                <label>{{ __('Admin Type') }}*</label>
                <select name="admin_type" class="form-control select2bs4">
                    <option value=""></option>
                    @foreach (get_admin_type_list_by_level() as $type)
                        <option value="{{ $type }}" {{ $admin->admin_type == $type ? 'selected' : '' }}>
                            {{ $type }}</option>
                    @endforeach
                </select>
                @error('admin_type')
                    <span class="invalid-feedback" role="alert" style="display: inline-block;">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group depend_admin_type country-list-wrap" style="display: none;">
                <label>{{ __('Select Country') }}*</label>
                <select name="country_id" class="form-control select2bs4">
                    <option value=""></option>
                    @foreach (get_country_list_by_level() as $country)
                        <option value="{{ $country->id }}" {{ $admin->country_id == $country->id ? 'selected' : '' }}>
                            {{ $country->name }}</option>
                    @endforeach
                </select>
                @error('country_id')
                    <span class="invalid-feedback" role="alert" style="display: inline-block;">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group depend_admin_type state-list-wrap" style="display: none;">
                <label>{{ __('Select State') }}*</label>
                <select name="state_id" class="form-control select2bs4">
                    <option value=""></option>
                    @foreach (get_state_list_by_level() as $state)
                        <option value="{{ $state->id }}" {{ $admin->state_id == $state->id ? 'selected' : '' }}>
                            {{ $state->name }}</option>
                    @endforeach
                </select>
                @error('state_id')
                    <span class="invalid-feedback" role="alert" style="display: inline-block;">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group depend_admin_type pradesh-list-wrap" style="display: none;">
                <label>{{ __('Select Pradesh') }}*</label>
                <select name="pradesh_id" class="form-control select2bs4">
                    <option value=""></option>
                    @foreach (get_pradesh_list_by_level() as $pradesh)
                        <option value="{{ $pradesh->id }}"
                            {{ $admin->pradesh_id == $pradesh->id ? 'selected' : '' }}>{{ $pradesh->name }}
                            ({{ $pradesh->state_name }})
                        </option>
                    @endforeach
                </select>
                @error('pradesh_id')
                    <span class="invalid-feedback" role="alert" style="display: inline-block;">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group depend_admin_type zone-list-wrap" style="display: none;">
                <label>{{ __('Select Zone') }}*</label>
                <select name="zone_id" class="form-control select2bs4-zones-livesearch">
                    <option value="{{ $admin->zone_id }}">{{ $data['zone_name'] }}</option>
                </select>
                @error('zone_id')
                    <span class="invalid-feedback" role="alert" style="display: inline-block;">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="row">
                <div class="col-6 col-md-6">
                    <div class="form-group depend_admin_type sabha-list-wrap" style="display: none;">
                        <label>{{ __('Select Sabha') }}*</label>
                        <select name="sabha_id" class="form-control select2bs4-sabhas-livesearch">
                            <option value="{{ $admin->sabha_id }}">{{ $data['sabha_name'] }}</option>
                        </select>
                        @error('sabha_id')
                            <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-6 col-md-6">
                    <div class="form-group depend_admin_type group-list-wrap" style="display: none;">
                        <label>{{ __('Select Group') }}*</label>
                        <select name="group_id" class="form-control select2bs4-group-livesearch">
                            <option value="{{ $admin->group_id }}">{{ $data['group_name'] }}</option>
                        </select>
                        @error('group_id')
                            <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

        @endif

        <div class="form-group">
            <label>{{ __('Name') }}*</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ $admin->name }}" />
            @error('name')
                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label>{{ __('Mobile Number') }}*</label>
            <input type="text" name="mobile_number"
                class="form-control @error('mobile_number') is-invalid @enderror"
                value="{{ $admin->mobile_number }}" />
            @error('mobile_number')
                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label>{{ __('Email') }}*</label>
            <input type="text" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ $admin->email }}" />
            @error('email')
                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label>{{ __('Status') }}</label>
            <div class="clearfix form-control pt-3">
                <div class="custom-control custom-radio d-inline mr-3">
                    <input type="radio" class="custom-control-input" id="radioStatusActive" name="status"
                        value="Active" {{ $admin->status == 'Active' ? 'checked' : '' }}>
                    <label for="radioStatusActive" class="custom-control-label">Active</label>
                </div>
                <div class="custom-control custom-radio d-inline">
                    <input type="radio" class="custom-control-input" id="radioStatusInactive" name="status"
                        value="Inactive" {{ $admin->status == 'Inactive' ? 'checked' : '' }}>
                    <label for="radioStatusInactive" class="custom-control-label">Inactive</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>{{ __('Do you want to change the password?') }}</label>
            <div class="clearfix form-control pt-3">
                <div class="custom-control custom-radio d-inline mr-3">
                    <input type="radio" class="custom-control-input" id="radioChangePasswordYes"
                        name="isChangePassword" value="Yes">
                    <label for="radioChangePasswordYes" class="custom-control-label">Yes</label>
                </div>
                <div class="custom-control custom-radio d-inline">
                    <input type="radio" class="custom-control-input" id="radioChangePasswordNo"
                        name="isChangePassword" value="No">
                    <label for="radioChangePasswordNo" class="custom-control-label">No</label>
                </div>
            </div>
        </div>
        <div class="form-group change-password-wrap" style="display: none;">
            <label>{{ __('New Password') }}*</label>
            <input autocomplete="new-password" type="password" name="password"
                class="form-control @error('password') is-invalid @enderror" />
            <small class="text-muted"><i>Minimum 8 Characters Password required containing (A – Z,a – z,0 – 9, !, $, #,
                    or %)</i></small>
            @error('password')
                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
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
            placeholder: '',
        });


        $("#formEditAdmin").submit(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.
            var formData = $('#formEditAdmin').serialize();

            // button disabled true
            $("#btn-submit").prop("disabled", true).html(
                '<i class="fas fa-spinner fa-spin"></i> Processing');
            $("#btn-cancel").prop("disabled", true);

            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('admins.update', $admin->id) }}",
                data: formData,
                dataType: 'json',
                success: function(response) {

                    // button disabled false
                    $("#btn-submit").prop("disabled", false).html('Save');
                    $("#btn-cancel").prop("disabled", false);

                    if (response.success == 1) {
                        $("span.invalid-feedback").remove();
                        DT_admins.ajax.reload();
                        $('#modal-admin-edit').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Admin has been updated successfully.'
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

        var selectedAdminType = '';
        $('select[name="admin_type"]').change(function() {
            var admin_type = $(this).val();
            $('.depend_admin_type').hide();
            selectedAdminType = admin_type;
            if (admin_type == 'Country_Admin') {
                $('.country-list-wrap').show();
            } else if (admin_type == 'State_Admin') {
                $('.state-list-wrap').show();
            } else if (admin_type == 'Pradesh_Admin') {
                $('.pradesh-list-wrap').show();
            } else if (admin_type == 'Zone_Admin') {
                $('.zone-list-wrap').show();
            } else if (admin_type == 'Sabha_Admin' || admin_type == 'Group_Admin' || admin_type ==
                'Followup_Admin') {
                @if (!in_array(get_current_admin_level(), ['Sabha_Admin', 'Group_Admin']))
                    $('.sabha-list-wrap').show();
                @endif
            }

            //
            if (admin_type == 'Group_Admin' || admin_type == 'Followup_Admin') {
                $('.group-list-wrap').show();
            } else {
                $('.group-list-wrap').hide();
            }
        });

        //
        var searchGroupBySabha = '';
        $('select[name="sabha_id"]').change(function() {
            searchGroupBySabha = " and g.sabha_id = " + $(this).val() + " ";
            if (selectedAdminType == 'Group_Admin' || selectedAdminType == 'Followup_Admin') {
                $('.group-list-wrap').show();
            } else {
                $('.group-list-wrap').hide();
            }
        });

        //
        @if (get_current_admin_level() == 'Sabha_Admin')
            var searchGroupBySabha = " and g.sabha_id = {{ Auth::user()->sabha_id }} ";
        @endif
        $('.select2bs4-group-livesearch').select2({
            theme: 'bootstrap4',
            placeholder: '',
            ajax: {
                url: "{{ route('groups.ajax-autocomplete-search') }}",
                data: function(params) {
                    var query = {
                        q: params.term,
                        whereSql: searchGroupBySabha
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
        $('select[name="admin_type"]').trigger('change');

        $('input[name="isChangePassword"]').change(function() {
            var isChangePassword = $(this).val();
            //console.log(isChangePassword);
            if (isChangePassword == 'Yes') {
                $('.change-password-wrap').show();
            } else {
                $('.change-password-wrap').hide();
            }
        });

        //Sabha
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
