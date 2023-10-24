@extends('layouts.app')

@section('title')
    Member
@stop

@section('left_header_content')
    Add Member
@endsection

@section('left_header_back_button')
    <a class="nav-link mr-3 pl-1" href="{{ route('members.index') }}" role="button">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#fff"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
        </svg>
    </a>
@endsection

@section('content')

    <div class="row" style="margin-top: -0.5rem;">
        <div class="col-sm-12">
            <div class="card--c">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="member-form-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="member-form-tab-basic" data-toggle="pill"
                                href="#member-form-tab-basic-content" role="tab" aria-controls="member-form-tab-basic"
                                aria-selected="true">Basic Information</a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link" id="member-form-tab-edu" data-toggle="pill"
                                href="#member-form-tab-edu-content" role="tab" aria-controls="member-form-tab-edu"
                                aria-selected="false">Educational Info</a>
                        </li> --}}
                    </ul>
                </div>
                <div class="card-body--c">
                    <div class="tab-content" id="member-form-tab-tabContent">
                        <div class="tab-pane fade show active" id="member-form-tab-basic-content" role="tabpanel"
                            aria-labelledby="member-form-tab-basic">
                            <form id="formCreateMember" action="{{ route('members.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Reference Name') }}</label>
                                            <select name="reference_id" class="form-control select2bs4-members-livesearch">
                                                <option value="">Select Reference Name</option>
                                            </select>
                                            @error('reference_id')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('First Name') }}*</label>
                                            <input type="text" name="first_name"
                                                class="form-control @error('first_name') is-invalid @enderror"
                                                value="{{ old('first_name') }}" />

                                            @error('first_name')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Middle Name') }}*</label>
                                            <input type="text" name="middle_name"
                                                class="form-control @error('middle_name') is-invalid @enderror"
                                                value="{{ old('middle_name') }}" />
                                            @error('middle_name')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Surname') }}*</label>
                                            <input type="text" name="surname"
                                                class="form-control @error('surname') is-invalid @enderror"
                                                value="{{ old('surname') }}" />
                                            @error('surname')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6 col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Gender') }}*</label>
                                            <div class="clearfix form-control pt-3">
                                                <div class="custom-control custom-radio d-inline mr-3">
                                                    <input type="radio" class="custom-control-input" id="radioGenderMale" name="gender" value="Male"
                                                        checked="">
                                                    <label for="radioGenderMale" class="custom-control-label">Male</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline">
                                                    <input type="radio" class="custom-control-input" id="radioGenderFemale" name="gender"
                                                        value="Female">
                                                    <label for="radioGenderFemale" class="custom-control-label">Female</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Date of Birth') }}*</label>
                                            <input type="text" name="date_of_birth"
                                                class="form-control @error('date_of_birth') is-invalid @enderror"
                                                value="{{ old('date_of_birth') }}" />
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Member Type') }}*</label>
                                            <select name="member_is" id="member_is" class="form-control select2bs4">
                                                <option value=""></option>
                                                @foreach (get_member_types() as $type)
                                                    <option value="{{ $type }}">{{ $type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="form-group">
                                            <label>AVD ID (Donor ID)</label>
                                            <input type="number" name="avd_id" id="avd_id" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-5 col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Mobile') }}*</label>
                                            <input type="text" name="mobile"
                                                class="form-control @error('mobile') is-invalid @enderror"
                                                value="{{ old('mobile') }}" />
                                            @error('mobile')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Email') }}</label>
                                            <input type="text" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email') }}" />
                                            @error('email')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Nick Name') }}</label>
                                            <input type="text" name="nick_name"
                                                class="form-control @error('nick_name') is-invalid @enderror"
                                                value="{{ old('nick_name') }}" />
                                            @error('nick_name')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Attending Sabha?') }}*</label>
                                            <div class="clearfix form-control pt-3">
                                                <div class="custom-control custom-radio d-inline mr-3">
                                                    <input type="radio" class="custom-control-input" id="radioattending_sabha_yes"
                                                        name="attending_sabha" value="Yes" checked="">
                                                    <label for="radioattending_sabha_yes" class="custom-control-label">Yes</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline">
                                                    <input type="radio" class="custom-control-input" id="radioattending_sabha_no"
                                                        name="attending_sabha" value="No">
                                                    <label for="radioattending_sabha_no" class="custom-control-label">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col col-md-3"
                                        style="{{ in_array(get_current_admin_level(), ['Zone_Admin', 'Sabha_Admin', 'Group_Admin', 'Followup_Admin']) ? 'display: none;' : '' }}">
                                        <div class="form-group">
                                            <label>{{ __('Select Zone') }}*</label>
                                            <select name="zone_id" id="select_zone_id"
                                                class="form-control select2bs4-zones-livesearch">
                                                <option value="{{ Auth::user()->zone_id }}">Select Zone</option>
                                            </select>
                                            @error('zone_id')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col col-md-3"
                                        style="{{ in_array(get_current_admin_level(), ['Sabha_Admin', 'Group_Admin', 'Followup_Admin']) ? 'display: none;' : '' }}">
                                        <div class="form-group">
                                            <label>{{ __('Select Sabha') }}*</label>
                                            <select name="sabha_id" id="select_sabha_id"
                                                class="form-control select2bs4-sabhas-livesearch">
                                                <option value="{{ Auth::user()->sabha_id }}">Select Sabha</option>
                                            </select>
                                            @error('sabha_id')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 depen-attending_sabha">
                                        <div class="form-group group_dropdwon_wrap">
                                            <label>{{ __('Select Group') }}*</label>
                                            <select name="group_id" id="select_group_id"
                                                class="form-control select2bs4-group-livesearch">
                                                <option value="">Select Group</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 depen-attending_sabha">
                                        <div class="form-group followupadmin_dropdwon_wrap">
                                            <label>{{ __('Select Followup') }}*</label>
                                            <select name="follow_up_by" id="select_follow_up_by"
                                                class="form-control select2bs4-admin-livesearch">
                                                <option value="">Select Followup</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- <h6 class="text-muted mt-4">Address</h6> --}}
                                <div class="row">
                                    <div class="col-4 col-md col-sm-12">
                                        <div class="form-group">
                                            <label>{{ __('Flat No') }}</label>
                                            <input type="text" name="flat_no"
                                                class="form-control @error('flat_no') is-invalid @enderror"
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
                                                class="form-control @error('landmark') is-invalid @enderror"
                                                value="{{ old('landmark') }}" />
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
                                    <div class="col-4 col-md-2 col-sm-12">
                                        <div class="form-group">
                                            <label>{{ __('Postcode') }}</label>
                                            <input type="text" name="postcode"
                                                class="form-control @error('postcode') is-invalid @enderror"
                                                value="{{ old('postcode') }}" />
                                            @error('postcode')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Joining Date') }}</label>
                                            <input type="text" name="joining_date"
                                                class="form-control @error('joining_date') is-invalid @enderror"
                                                value="{{ old('joining_date') }}" />
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex mt-4 form-actions-btn">
                                    <a href="{{ route('members.index') }}" id="btn-cancel" type="button"
                                        class="btn btn-default">Cancel</a>
                                    <button id="btn-submit" type="submit"
                                        class="btn btn-primary ml-3 px-5">Save</button>
                                </div>
                            </form>
                        </div>


                        {{-- <div class="tab-pane fade" id="member-form-tab-edu-content" role="tabpanel"
                            aria-labelledby="member-form-tab-edu">
                        </div> --}}
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>

@endsection


@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4',
            });

            $("#formCreateMember").submit(function(e) {
                e.preventDefault(); // avoid to execute the actual submit of the form.
                var formData = $('#formCreateMember').serialize();

                // button disabled true
                $("#btn-submit").prop("disabled", true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Processing');
                $("#btn-cancel").prop("disabled", true);

                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('members.store') }}",
                    data: formData,
                    dataType: 'json',
                    success: function(response) {

                        // button disabled false
                        $("#btn-submit").prop("disabled", false).html('Save');
                        $("#btn-cancel").prop("disabled", false);

                        if (response.success == 1) {
                            $("span.invalid-feedback").remove();
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            }
                            Toast.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Member has been added successfully.'
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



            /*
            $('#dob_year').on('keyup', function() {
                var year = $(this).val();
                var currentyear = new Date().getFullYear() // returns the current year
                if (year.length == 4) {
                    var age = (currentyear - parseInt(year));
                    //console.log(age);
                    if ($('input[name="gender"]:checked').val() == 'Male') {
                        if (age <= 11) {
                            $('#member_is').val('Bal').trigger('change');
                        } else if (age > 12 && age < 60) {
                            $('#member_is').val('Yuvak').trigger('change');
                        } else if (age >= 60) {
                            $('#member_is').val('Vadil').trigger('change');
                        }
                    } else {
                        if (age <= 11) {
                            $('#member_is').val('Balika').trigger('change');
                        } else if (age > 12 && age < 60) {
                            $('#member_is').val('Yuvati').trigger('change');
                        }
                    }
                }
            });
            */

            $('input[name="gender"]').change(function() {
                if ($('input[name="gender"]:checked').val() == 'Male') {
                    $('#member_is').val('Yuvak').trigger('change');
                } else {
                    $('#member_is').val('Yuvati').trigger('change');
                }
                $('#dob_year').trigger('keyup');
            });
            $('input[name="gender"]').trigger('change');


            // $('input[name="attending_sabha"]').change(function() {
            //     if ($('input[name="attending_sabha"]:checked').val() == 'Yes') {
            //         $('div.depen-attending_sabha').show();
            //     } else {
            //         $('div.depen-attending_sabha').hide();
            //     }
            // });


            //Member
            $('.select2bs4-members-livesearch').select2({
                theme: 'bootstrap4',
                placeholder: '',
                ajax: {
                    url: "{{ route('members.ajax-autocomplete-search') }}",
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

            //
            var searchSabhaByZone = '';
            $('select[name="zone_id"]').change(function() {
                searchSabhaByZone = " and s.zone_id = " + $(this).val() + " ";
                $('select[name="sabha_id"]').val('');
                $('select[name="sabha_id"]').trigger('change');
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
                            whereSql: searchSabhaByZone
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

            //
            var searchGroupBySabha = '';
            $('select[name="sabha_id"]').change(function() {
                searchGroupBySabha = " and g.sabha_id = " + $(this).val() + " ";
                $('select[name="group_id"]').val('');
                $('select[name="group_id"]').trigger('change');
            });
            @if (in_array(get_current_admin_level(), ['Sabha_Admin', 'Group_Admin', 'Followup_Admin']))
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

            //admin
            var searchAdminByType = "";
            $('select[name="group_id"]').change(function() {
                if ($(this).val()) {
                    searchAdminByType = "and a.admin_type = 'Followup_Admin' and a.group_id = " + $(this)
                        .val() + " ";
                }
            });
            $('.select2bs4-admin-livesearch').select2({
                theme: 'bootstrap4',
                placeholder: '',
                ajax: {
                    url: "{{ route('admins.ajax-autocomplete-search') }}",
                    data: function(params) {
                        var query = {
                            q: params.term,
                            whereSql: searchAdminByType
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


            $('input[name="joining_date"],input[name="anniversery_date"]').daterangepicker({
                locale: {
                    format: 'DD-MM-YYYY'
                },
                autoUpdateInput: true,
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 1901,
                maxYear: parseInt(moment().format('YYYY'), 10)
            });
            $('input[name="joining_date"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY'));
            });

            $('input[name="date_of_birth"]').daterangepicker({
                locale: {
                    format: 'DD-MM-YYYY'
                },
                autoUpdateInput: false,
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 1901,
                maxYear: parseInt(moment().format('YYYY'), 10)
            }, function(start, end, label) {
                var age = moment().diff(start, 'years');
                //alert("You are " + age + " years old!");
                if ($('input[name="gender"]:checked').val() == 'Male') {
                    if (age <= 11) {
                        $('#member_is').val('Bal').trigger('change');
                    } else if (age > 12 && age < 60) {
                        $('#member_is').val('Yuvak').trigger('change');
                    } else if (age >= 60) {
                        $('#member_is').val('Vadil').trigger('change');
                    }
                } else {
                    if (age <= 11) {
                        $('#member_is').val('Balika').trigger('change');
                    } else if (age > 12 && age < 60) {
                        $('#member_is').val('Yuvati').trigger('change');
                    }
                }
            });

            $('input[name="date_of_birth"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY'));
            });


        });
    </script>
@endpush
