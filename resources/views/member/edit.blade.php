@extends('layouts.app')

@section('title')
    Member
@stop

@section('left_header_content')
    Update Member
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
                <div class="card-header--c p-0 border-bottom-0">
                    <ul class="nav nav-tabs mb-4" id="member-form-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="member-form-tab-basic" data-toggle="pill"
                                href="#member-form-tab-basic-content" role="tab" aria-controls="member-form-tab-basic"
                                aria-selected="true">Basic Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="member-form-tab-otherInfo" data-toggle="pill"
                                href="#member-form-tab-otherInfo-content" role="tab"
                                aria-controls="member-form-tab-otherInfo" aria-selected="false">Detail Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="member-form-tab-photo" data-toggle="pill"
                                href="#member-form-tab-photo-content" role="tab" aria-controls="member-form-tab-photo"
                                aria-selected="false">Photo</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="member-form-tab-tag" data-toggle="pill"
                                href="#member-form-tab-tag-content" role="tab" aria-controls="member-form-tab-tag"
                                aria-selected="false">Tags</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="member-form-tab-family" data-toggle="pill"
                                href="#member-form-tab-family-content" role="tab" aria-controls="member-form-tab-family"
                                aria-selected="false">Family Information</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body--c">
                    <div class="tab-content" id="member-form-tab-tabContent">
                        <div class="tab-pane fade show active" id="member-form-tab-basic-content" role="tabpanel"
                            aria-labelledby="member-form-tab-basic">
                            <form id="formEditMemberBasic" action="{{ route('members.update', $member->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @method('PUT')
                                <input type="hidden" name="tabName" value="memberBasic">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Reference Name') }}</label>
                                            <select name="reference_id" class="form-control select2bs4-members-livesearch">
                                                <option value="{{ $member->reference_id }}">
                                                    {{ $member->reference_id == 0 ? $member->ref_name : get_member_fullname($member->reference_id, false, true) }}
                                                </option>
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
                                                value="{{ $member->first_name }}" />
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
                                                value="{{ $member->middle_name }}" />
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
                                                value="{{ $member->surname }}" />
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
                                                    <input type="radio" class="custom-control-input" id="radioGenderMale" name="gender"
                                                        value="Male" {{ $member->gender == 'Male' ? 'checked' : '' }}>
                                                    <label for="radioGenderMale" class="custom-control-label">Male</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline">
                                                    <input type="radio" class="custom-control-input" id="radioGenderFemale" name="gender"
                                                        value="Female" {{ $member->gender == 'Female' ? 'checked' : '' }}>
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
                                                value="{{ $member->date_of_birth != '' ? date('d-m-Y', strtotime($member->date_of_birth)) : '' }}" />
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Member Type') }}*</label>
                                            <select name="member_is" id="member_is" class="form-control select2bs4">
                                                <option value="">Select Member Type</option>
                                                @foreach (get_member_types() as $type)
                                                    <option value="{{ $type }}"
                                                        {{ $member->member_is == $type ? 'selected' : '' }}>
                                                        {{ $type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="form-group">
                                            <label>AVD ID (Donor ID)</label>
                                            <input type="number" name="avd_id" id="avd_id" class="form-control" value="{{ $member->avd_id }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-5 col-md-3">
                                        <div class="form-group">
                                            <label>{{ __('Mobile') }}*</label>
                                            <input type="text" name="mobile"
                                                class="form-control @error('mobile') is-invalid @enderror"
                                                value="{{ $member->mobile }}" />
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
                                                value="{{ $member->email }}" />
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
                                                value="{{ $member->nick_name }}" />
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
                                                        name="attending_sabha" value="Yes"
                                                        {{ $member->attending_sabha == 'Yes' ? 'checked' : '' }}>
                                                    <label for="radioattending_sabha_yes" class="custom-control-label">Yes</label>
                                                </div>
                                                <div class="custom-control custom-radio d-inline">
                                                    <input type="radio" class="custom-control-input" id="radioattending_sabha_no"
                                                        name="attending_sabha" value="No"
                                                        {{ $member->attending_sabha == 'No' ? 'checked' : '' }}>
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
                                                <option value="{{ $data['zone_id'] }}">{{ $data['zone_name'] }}
                                                </option>
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
                                                <option value="{{ $data['sabha_id'] }}">{{ $data['sabha_name'] }}
                                                </option>
                                            </select>
                                            @error('sabha_id')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- display: {{ $member->attending_sabha == 'Yes' ? 'block' : 'none' }} --}}
                                    <div class="col-md-3 col-sm-12 depen-attending_sabha" style=""> 
                                        <div class="form-group group_dropdwon_wrap" style="{{ in_array(get_current_admin_level(), ['Followup_Admin']) ? 'pointer-events: none;opacity: 0.5;' : '' }}">
                                            <label>{{ __('Select Group') }}*</label>
                                            <select name="group_id" id="select_group_id"
                                                class="form-control select2bs4-group-livesearch">
                                                <option value="{{ $member->group_id }}">{{ $data['group_name'] }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    {{-- display: {{ $member->attending_sabha == 'Yes' ? 'block' : 'none' }} --}}
                                    <div class="col-md-3 col-sm-12 depen-attending_sabha" style="">
                                        <div class="form-group followupadmin_dropdwon_wrap" style="{{ in_array(get_current_admin_level(), ['Followup_Admin']) ? 'pointer-events: none;opacity: 0.5;' : '' }}">
                                            <label>{{ __('Select Followup') }}*</label>
                                            <select name="follow_up_by" id="select_follow_up_by"
                                                class="form-control select2bs4-admin-livesearch">
                                                <option value="{{ $member->follow_up_by }}">{{ $data['followup_name'] }}</option>
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
                                                value="{{ $member->flat_no }}" />
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
                                                value="{{ $member->building_name }}" />
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
                                                value="{{ $member->landmark }}" />
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
                                                value="{{ $member->street_name }}" />
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
                                                value="{{ $member->postcode }}" />
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
                                                value="{{ $member->joining_date != '' && $member->joining_date != '0000-00-00' ? date('d-m-Y', strtotime($member->joining_date)) : '' }}" />
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex mt-4 form-actions-btn">
                                    <a href="{{ route('members.index') }}" type="button"
                                        class="btn btn-default btn-cancel">Cancel</a>
                                    <button type="submit" class="btn btn-primary ml-3 btn-submit px-5">Save</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="member-form-tab-otherInfo-content" role="tabpanel"
                            aria-labelledby="member-form-tab-otherInfo">
                            <form id="formEditMemberOther" action="{{ route('members.update', $member->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @method('PUT')
                                <input type="hidden" name="tabName" value="memberOther">
                                <div class="row">
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label>{{ __('Educational Qualification') }}*</label>
                                            <select name="edu_qualification" id="edu_qualification"
                                                class="form-control select2bs4">
                                                <option value="">Select</option>
                                                @foreach (get_qualification_list() as $key => $row)
                                                    <option value="{{ $row['title'] }}" data-key="{{ $key }}"
                                                        {{ $row['title'] == $member->edu_qualification ? 'selected' : '' }}>
                                                        {{ $row['title'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('edu_qualification')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label>{{ __('Major Subject') }}*</label>
                                            <div class="subject_list_wrap"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 edu_subject_other" style="display: none;">
                                        <div class="form-group">
                                            <label>{{ __('Other Subject Name') }}</label>
                                            <input type="text" name="edu_other" class="form-control"
                                                value="{{ $member->edu_other }}">
                                            @error('edu_other')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label>{{ __('Educational Status') }}*</label>
                                            <select name="edu_status" class="form-control select2bs4">
                                                <option value="">Select</option>
                                                <option value="Pursuing"
                                                    {{ 'Pursuing' == $member->edu_status ? 'selected' : '' }}>
                                                    Pursuing
                                                </option>
                                                <option value="Completed"
                                                    {{ 'Completed' == $member->edu_status ? 'selected' : '' }}>
                                                    Completed
                                                </option>

                                            </select>
                                            @error('edu_status')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label>{{ __('Occupation') }}</label>
                                            <select name="occupation" class="form-control select2bs4">
                                                <option value="">Select</option>
                                                @foreach (get_occupation_list() as $row)
                                                    <option value="{{ $row }}"
                                                        {{ $row == $member->occupation ? 'selected' : '' }}>
                                                        {{ $row }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('occupation')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 for_student_wrap">
                                        <div class="form-group">
                                            <label>{{ __('School/College/Institute/University') }}</label>
                                            <input type="text" name="school_college" class="form-control"
                                                value="{{ $member->school_college }}">
                                            @error('school_college')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 for_employed_wrap">
                                        <div class="form-group">
                                            <label>{{ __('Name Of The Organization') }}</label>
                                            <input type="text" name="organization" class="form-control"
                                                value="{{ $member->organization }}">
                                            @error('organization')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 for_employed_wrap">
                                        <div class="form-group">
                                            <label>{{ __('Industry') }}</label>
                                            <input type="text" name="industry" class="form-control"
                                                value="{{ $member->industry }}">
                                            @error('industry')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 for_employed_wrap">
                                        <div class="form-group">
                                            <label>{{ __('Designation') }}</label>
                                            <input type="text" name="designation" class="form-control"
                                                value="{{ $member->designation }}">
                                            @error('designation')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label>{{ __('Marital Status') }}</label>
                                            <select name="marital_status" class="form-control select2bs4">
                                                <option value="">Select</option>
                                                @foreach (get_marital_status_list() as $row)
                                                    <option value="{{ $row }}"
                                                        {{ $row == $member->marital_status ? 'selected' : '' }}>
                                                        {{ $row }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('marital_status')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 for_Anniversery_Date">
                                        <div class="form-group">
                                            <label>{{ __('Anniversery Date') }}*</label>
                                            <input type="text" name="anniversery_date"
                                                class="form-control @error('anniversery_date') is-invalid @enderror"
                                               value="{{ $member->anniversery_date != '' && '0000-00-00' != $member->anniversery_date ? date('d-m-Y', strtotime($member->anniversery_date)) : '' }}" />
                                            {{-- <small class="text-muted">{{ __('DD-MM-YYYY') }}</small> --}}
                                            {{-- <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="anniversery_day" class="form-control select2bs4">
                                                            <option value="">Day</option>
                                                            @foreach (get_days() as $day)
                                                                <option value="{{ $day }}"
                                                                    {{ $day == $data['anniversery_day'] ? 'selected' : '' }}>
                                                                    {{ $day }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="anniversery_month" class="form-control select2bs4">
                                                            <option value="">Month</option>
                                                            @foreach (get_months() as $num => $month)
                                                                <option value="{{ $num }}"
                                                                    {{ $num == $data['anniversery_month'] ? 'selected' : '' }}>
                                                                    {{ $month }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input type="text" name="anniversery_year"
                                                            class="form-control" value="{{ $data['anniversery_year'] }}">
                                                    </div>
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label>{{ __('Blood Group') }}</label>
                                            <select name="blood_group" class="form-control select2bs4">
                                                <option value="">Select</option>
                                                @foreach (get_blood_group_list() as $row)
                                                    <option value="{{ $row }}"
                                                        {{ $row == $member->blood_group ? 'selected' : '' }}>
                                                        {{ $row }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('blood_group')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label>{{ __('Performing Puja?') }}</label>
                                            <select name="performing_puja" class="form-control select2bs4">
                                                <option value="">Select</option>
                                                <option value="Yes"
                                                    {{ 'Yes' == $member->performing_puja ? 'selected' : '' }}>
                                                    {{ 'Yes' }}
                                                </option>
                                                <option value="No"
                                                    {{ 'No' == $member->performing_puja ? 'selected' : '' }}>
                                                    {{ 'No' }}
                                                </option>
                                            </select>
                                            @error('performing_puja')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label>{{ __('Nishtawan?') }}</label>
                                            <select name="nishtawan" class="form-control select2bs4">
                                                <option value="">Select</option>
                                                <option value="Yes"
                                                    {{ 'Yes' == $member->nishtawan ? 'selected' : '' }}>
                                                    {{ 'Yes' }}
                                                </option>
                                                <option value="No"
                                                    {{ 'No' == $member->nishtawan ? 'selected' : '' }}>
                                                    {{ 'No' }}
                                                </option>
                                            </select>
                                            @error('performing_puja')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label>{{ __('Ambrish Code') }}</label>
                                            <input type="text" name="ambrish_code" class="form-control"
                                                value="{{ $member->ambrish_code }}">
                                            @error('ambrish_code')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>{{ __('Languages Known') }}</label>
                                            <div class="clearfix form-control pt-3" style="height: auto !important;">
                                                @foreach (get_language_list() as $row)
                                                    <div class="custom-control custom-checkbox d-inline mr-3">
                                                        <input type="checkbox" class="custom-control-input" id="languages_known_{{ $row }}" name="languages_known[]" value="{{ $row }}"
                                                            {{ in_array($row, $data['languages_known']) ? 'checked' : '' }}>
                                                        <label for="languages_known_{{ $row }}" class="custom-control-label">{{ $row }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex mt-4 form-actions-btn">
                                    <a href="{{ route('members.index') }}" type="button"
                                        class="btn btn-default btn-cancel">Cancel</a>
                                    <button type="submit" class="btn btn-primary ml-3 btn-submit px-5">Save</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="member-form-tab-photo-content" role="tabpanel"
                            aria-labelledby="member-form-tab-photo">
                            <form id="formEditMemberPhoto" action="{{ route('members.update', $member->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="tabName" value="memberPhoto">
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <h3 class="profile-username text-center">
                                            {{ $member->first_name . ' ' . $member->middle_name . ' ' . $member->surname }}
                                        </h3>
                                        <p>{!! get_member_type_badge($member->member_is) !!}</p>

                                        <div class="profile-user-img">
                                            @if ($member->photo)
                                                <img id="memberPhotoPreview" class="img-fluid"
                                                    src="{{ url('uploads/member_photo') }}/{{ $member->photo }}"
                                                    alt="User profile picture">
                                            @else
                                                <img id="memberPhotoPreview" class="img-fluid"
                                                    src="{{ asset('assets/img/blank-profile-picture.webp') }}"
                                                    alt="User profile picture">
                                            @endif
                                        </div>

                                        <div class="form-group mt-3">
                                            <small class="text-muted"><i>The photo must not be greater than
                                                    2mb.</i></small>
                                            <input type="file" name="photo" accept="image/*"
                                                onchange="memberPhotoPreviewImage(event);">
                                            @error('photo')
                                                <span class="invalid-feedback" role="alert" style="display: inline-block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex mt-4 form-actions-btn">
                                    <a href="{{ route('members.index') }}" type="button"
                                        class="btn btn-default btn-cancel">Cancel</a>
                                    <button type="submit" class="btn btn-primary ml-3 btn-submit px-5">Save</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="member-form-tab-tag-content" role="tabpanel"
                            aria-labelledby="member-form-tab-tag">
                            <form id="formEditMemberTag" action="{{ route('members.update', $member->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="tabName" value="memberTag">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tags</label>
                                            <select name="tag_ids[]" class="select2bs4-tags-livesearch"
                                                multiple="multiple" style="width: 100%;">
                                                @foreach ($data['memberTags'] as $item)
                                                    <option value="{{ $item->tag_id }}" selected>{{ $item->tagName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex mt-4 form-actions-btn">
                                    <a href="{{ route('members.index') }}" type="button"
                                        class="btn btn-default btn-cancel">Cancel</a>
                                    <button type="submit" class="btn btn-primary ml-3 btn-submit px-5">Save</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="member-form-tab-family-content" role="tabpanel"
                            aria-labelledby="member-form-tab-family">
                            <div class="text-center">Coming soon</div>
                        </div>

                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>

@endsection


@push('scripts')
    <script type="text/javascript">
        const memberPhotoPreviewImage = (event) => {
            const imageFiles = event.target.files;
            const imageFilesLength = imageFiles.length;
            if (imageFilesLength > 0) {
                const imageSrc = URL.createObjectURL(imageFiles[0]);
                const imagePreviewElement = document.querySelector("#memberPhotoPreview");
                imagePreviewElement.src = imageSrc;
                imagePreviewElement.style.display = "block";
            }
        };

        $(document).ready(function() {

            @if (session()->has('success'))
                Toast.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Photo has been updated successfully.'
                });
            @endif

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4',
            });

            $("#formEditMemberBasic").submit(function(e) {
                e.preventDefault();
                var formData = $('#formEditMemberBasic').serialize();
                updateMember(formData);
            });
            $("#formEditMemberOther").submit(function(e) {
                e.preventDefault();
                var formData = $('#formEditMemberOther').serialize();
                updateMember(formData);
            });
            $("#formEditMemberTag").submit(function(e) {
                e.preventDefault();
                var formData = $('#formEditMemberTag').serialize();
                updateMember(formData);
            });

            function updateMember(formData) {
                // button disabled true
                $(".btn-submit").prop("disabled", true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Processing');
                $(".btn-cancel").prop("disabled", true);

                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('members.update', $member->id) }}",
                    data: formData,
                    dataType: 'json',
                    success: function(response) {

                        // button disabled false
                        $(".btn-submit").prop("disabled", false).html('Save');
                        $(".btn-cancel").prop("disabled", false);

                        if (response.success == 1) {
                            $("span.invalid-feedback").remove();
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            }

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
            }

            $('a[data-toggle="pill"]').on('show.bs.tab', function(e) {
                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                $('a[href="' + activeTab + '"]').tab('show');
            }

            $("select[name='edu_qualification']").change(function() {
                var index_key = $(this).find(':selected').data('key');
                $(".subject_list_wrap").html('');
                if (index_key) {
                    $.ajax({
                        type: "GET",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('members.suject-list-html') }}",
                        data: {
                            index_key: index_key,
                            return_json: '1',
                            selected: '{{ $member->edu_subject }}'
                        },
                        success: function(result) {
                            $('.subject_list_wrap').html(result.html);
                        }
                    });
                }
            });
            $("select[name='edu_qualification']").trigger('change');

            $("select[name='occupation']").change(function() {
                var occupation = $(this).val();
                $('.for_student_wrap').hide();
                $('.for_employed_wrap').hide();

                if (occupation == 'Student') {
                    $('.for_student_wrap').show();
                }
                if (occupation == 'Self employed' || occupation == 'Service') {
                    $('.for_employed_wrap').show();
                }
            });
            $("select[name='occupation']").trigger('change');


            $("select[name='marital_status']").change(function() {
                var marital_status = $(this).val();
                $('.for_Anniversery_Date').hide();
                if (marital_status == 'Married') {
                    $('.for_Anniversery_Date').show();
                }
            });
            $("select[name='marital_status']").trigger('change');


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
            ///$('input[name="gender"]').trigger('change');


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
            var searchSabhaByZone = 'and s.zone_id = {{ $member->zone_id }}';
            $('select[name="zone_id"]').change(function() {
                searchSabhaByZone = " and s.zone_id = " + $(this).val() + " ";
                $('select[name="sabha_id"]').val('');
                $('select[name="sabha_id"]').trigger('change');
            });
            //sabha
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
            var searchGroupBySabha = "and g.sabha_id = {{ $member->sabha_id }}";
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
                    searchAdminByType = " and a.admin_type = 'Followup_Admin' and a.group_id = " + $(this).val() + " ";
                }
            });
            $('select[name="group_id"]').trigger('change');
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
                autoUpdateInput: false,
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 1901,
                maxYear: parseInt(moment().format('YYYY'), 10)
            });
            $('input[name="joining_date"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY'));
            });
            $('input[name="anniversery_date"]').on('apply.daterangepicker', function(ev, picker) {
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

            //tags
            $('.select2bs4-tags-livesearch').select2({
                theme: 'bootstrap4',
                placeholder: '',
                ajax: {
                    url: "{{ route('tagsMaster.ajax-autocomplete-search') }}",
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
            })
        });
    </script>
@endpush
