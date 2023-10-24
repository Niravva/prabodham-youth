<div class="modal-header">
    <h4 class="modal-title">Add Admin</h4>
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
    <form id="formCreateAdmin" action="{{ route('admins.store') }}" method="POST" enctype="multipart/form-data">
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
                        <option value="{{ $type }}">{{ $type }}</option>
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
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
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
                        <option value="{{ $state->id }}">{{ $state->name }}</option>
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
                        <option value="{{ $pradesh->id }}">{{ $pradesh->name }} ({{ $pradesh->state_name }})
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
            <label>Name (Search Member)</label>
            <select name="member_id" class="form-control select2bs4-members-livesearch">
            </select>
            <small class="m-2 text-center"><i>OR</i></small>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}" />
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
                value="{{ old('mobile_number') }}" />
            @error('mobile_number')
                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label>{{ __('Email') }}*</label>
            <input type="text" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" />
            @error('email')
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

        $("#formCreateAdmin").submit(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.
            var formData = $('#formCreateAdmin').serialize();

            // button disabled true
            $("#btn-submit").prop("disabled", true).html(
                '<i class="fas fa-spinner fa-spin"></i> Processing');
            $("#btn-cancel").prop("disabled", true);

            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('admins.store') }}",
                data: formData,
                dataType: 'json',
                success: function(response) {

                    // button disabled false
                    $("#btn-submit").prop("disabled", false).html('Save');
                    $("#btn-cancel").prop("disabled", false);

                    if (response.success == 1) {
                        $("span.invalid-feedback").remove();
                        DT_admins.ajax.reload();
                        $('#modal-admin-create').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Admin has been added successfully.'
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

            $('select[name="group_id"]').val('');
            $('select[name="group_id"]').trigger('change');
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


        var searchMemerBySabha = '';
        @if (in_array(get_current_admin_level(), ['Sabha_Admin', 'Group_Admin']))
            searchMemerBySabha = " and m.sabha_id = {{ Auth::user()->sabha_id }} ";
        @elseif (get_current_admin_level() == 'Country_Admin')
            searchMemerBySabha = " and m.country_id = {{ Auth::user()->country_id }} ";
        @elseif (get_current_admin_level() == 'State_Admin')
            searchMemerBySabha = " and m.state_id = {{ Auth::user()->state_id }} ";
        @elseif (get_current_admin_level() == 'Pradesh_Admin')
            searchMemerBySabha = " and m.pradesh_id = {{ Auth::user()->pradesh_id }} ";
        @elseif (get_current_admin_level() == 'Zone_Admin')
            searchMemerBySabha = " and m.zone_id = {{ Auth::user()->zone_id }} ";
        @endif
        $('.select2bs4-members-livesearch').select2({
            theme: 'bootstrap4',
            placeholder: '',
            ajax: {
                url: "{{ route('members.ajax-autocomplete-search') }}",
                data: function(params) {
                    var query = {
                        q: params.term,
                        whereSql: searchMemerBySabha
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
                                id: item.id,
                                email: item.email,
                                mobile: item.mobile,
                                name: item.first_name + " " + item.middle_name + " " + item.surname
                            }
                        })
                    };
                },
                cache: true
            }
        });

        $('select.select2bs4-members-livesearch').change(function() {
            const selectedMemberData = jQuery(".select2bs4-members-livesearch").select2('data')[0];
            $('input[name="name"]').val(selectedMemberData.name);
            if (selectedMemberData.mobile) {
                $('input[name="mobile_number"]').val(selectedMemberData.mobile);
            } else {
                $('input[name="mobile_number"]').val('');
            }
            if (selectedMemberData.email) {
                $('input[name="email"]').val(selectedMemberData.email);
            } else {
                $('input[name="email"]').val('');
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
