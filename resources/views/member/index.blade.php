@extends('layouts.app')

@section('title')
    Members
@stop

@section('left_header_content')
    Manage Members
@endsection

@section('right_header_content')
    @if (get_current_admin_level() != 'Followup_Admin')
        <div class="actions d-inline mr-2" style="position: relative;">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                More <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </button>
            <div class="dropdown-menu mt-3">
                <a class="dropdown-item" href="javascript:void;" data-toggle="modal" data-target="#modal-member-filter">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                    </svg>
                    Filters</a>
                {{-- <a class="dropdown-item" href="javascript:void;"><i class="fas fa-file-csv"></i> Export(CSV)</a> --}}
                {{-- <a class="dropdown-item" href="javascript:void;"><i class="fas fa-file-pdf"></i> Export(PDF)</a> --}}
            </div>
        </div>
        <a href="{{ route('members.create') }}" class="btn btn-default" title="Add New Member">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
            </svg>
        </a>
    @endif
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card--c">
                <div class="card-body--c">
                    <table id="membersTable" class="table table-bordered nowrap" style="margin-top: 0 !important">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Name</th>
                                <th>Photo</th>
                                <th>Member Type</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Birth Date</th>
                                <th>Reference</th>
                                <th>Zone</th>
                                <th>Sabha</th>
                                <th>Group</th>
                                <th>Follow Up</th>
                                <th>Flat/No</th>
                                <th>Building</th>
                                <th>Landmark</th>
                                <th>Street</th>
                                <th>Postcode</th>
                                <th>Address</th>
                                <th>Attending Sabha</th>
                                <th>AVD ID (Donor ID)</th>
                                <th>Ambrish Code</th>
                                <th>Joining Date</th>
                                <th>Created By</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-member-create" data-backdrop="static">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="text-center p-5">
                    <i class="text-muted fas fa-spinner fa-spin" style="font-size: 25px;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-member-edit" data-backdrop="static">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="text-center p-5">
                    <i class="text-muted fas fa-spinner fa-spin" style="font-size: 25px;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-member-view">
        <div class="modal-dialog modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="text-center p-5">
                    <i class="text-muted fas fa-spinner fa-spin" style="font-size: 25px;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-member-filter" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Filters</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg></span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formMemberFilter" enctype="multipart/form-data">
                        <div class="row">
                            @if (in_array(get_current_admin_level(), ['Super_Admin', 'Country_Admin', 'State_Admin', 'Pradesh_Admin']))
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Zone</label>
                                        <select name="zone_id" id="filterZoneId"
                                            class="form-control select2bs4-zones-livesearch">
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if (in_array(get_current_admin_level(), ['Super_Admin', 'Country_Admin', 'State_Admin', 'Pradesh_Admin', 'Zone_Admin']))
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Sabha</label>
                                        <select name="sabha_id" id="filterSabha_id"
                                            class="form-control select2bs4-sabhas-livesearch">
                                        </select>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Group</label>
                                    <select name="group_id" id="filterGroup_id"
                                        class="form-control select2bs4-group-livesearch">
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Followup</label>
                                    <select name="follow_up_by" id="filterFollowup_id"
                                        class="form-control select2bs4-admin-livesearch">
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 col-md-3">
                                <div class="form-group">
                                    <label for="">Last Sabha Status</label>
                                    <select name="last_sabha_status" id="filterLastSabhaStatus"
                                        class="form-control select2bs4">
                                        <option value="">Any</option>
                                        <option value="Present">Present</option>
                                        <option value="Absent">Absent</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-group">
                                    <label for="">Sabha Regularity</label>
                                    <select name="sabha_status" id="filterSabhaRegularity"
                                        class="form-control select2bs4">
                                        <option value="">Any</option>
                                        <option value="Fresh">Fresh</option>
                                        <option value="Regular">Regular</option>
                                        <option value="Irregular">Irregular</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Reference</label>
                                    <select name="reference_id" id="filterReferenceId"
                                        class="form-control select2bs4-members-livesearch">
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Member Type</label>
                                    <select name="member_is[]" id="filterMemberType" class="form-control select2bs4"
                                        multiple="multiple">
                                        @foreach (get_member_types() as $type)
                                            <option value="{{ $type }}">{{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tags</label>
                                    <select name="tag_ids[]" id="filterMemberTags" class="select2bs4-tags-livesearch"
                                        multiple="multiple" data-placeholder="Tags" style="width: 100%;">
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-6 col-md-3">
                                <div class="form-group">
                                    <label for="">Attending Sabha</label>
                                    <select name="attending_sabha" id="filterAttendingSabha"
                                        class="form-control select2bs4">
                                        <option value="">Any</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-group">
                                    <label for="">Blood Group</label>
                                    <select name="blood_group" id="filterBloodGroup" class="form-control select2bs4">
                                        <option value="">Any</option>
                                        @foreach (get_blood_group_list() as $row)
                                            <option value="{{ $row }}">{{ $row }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-group">
                                    <label for="">Marital Status</label>
                                    <select name="marital_status" id="filterMaritalStatus"
                                        class="form-control select2bs4">
                                        <option value="">Any</option>
                                        @foreach (get_marital_status_list() as $row)
                                            <option value="{{ $row }}">{{ $row }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="form-group">
                                    <label for="">Gender</label>
                                    <select name="gender" id="filterGender" class="form-control select2bs4">
                                        <option value="">Any</option>
                                        <option value="Female">Female</option>
                                        <option value="Male">Male</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row mt-3">
                                        <div class="col">
                                            <label for="">Age Form</label>
                                            <input type="number" name="min_age" id="filterFromAge" class="form-control"
                                                min="5">
                                        </div>
                                        <div class="col">
                                            <label for="">Age To</label>
                                            <input type="number" name="max_age" id="filterToAge" class="form-control"
                                                min="6" max="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"></div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    {{-- <button id="btn-cancel-filter" type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> --}}
                    <button id="btn-clear-filter" type="button" class="btn btn-danger"
                        onclick="ResetFilterForm()">Clear
                        Filter</button>
                    <button id="btn-submit-filter" type="submit" class="btn btn-primary px-4">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        var DT_members;
        $(document).ready(function() {

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            @if (session()->has('success'))
                Toast.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Member has been updated successfully.'
                });
            @endif

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            DT_members = $('#membersTable').DataTable({
                stateSave: true,
                stateSaveParams: function(settings, data) {
                    delete data.length;
                    //console.log(data);
                },
                dom: "<'row'<'col-4 col-md-4'l><'col col-md-4 datatabel-export-buttons'B><'col-sm-12 col-md-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                buttons: [{
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'colvis',
                        text: 'Column',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ],
                scrollX: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                pageLength: 25,
                language: {
                    search: "",
                    sSearchPlaceholder: "Search",
                    sLengthMenu: "Show _MENU_",
                },
                language: {
                    search: "",
                    sSearchPlaceholder: "Search",
                    sLengthMenu: "Show _MENU_",
                },
                columnDefs: [{
                        orderable: true,
                        className: 'reorder',
                        targets: 1,
                        order: 'asc'
                    },
                    {
                        orderable: false,
                        targets: '_all'
                    }
                ],
                order: [
                    [1, 'asc']
                ],
                ajax: {
                    url: "{{ route('members.index') }}",
                    data: function(data) {
                        data.filterZone_id = $('#filterZoneId').val();
                        data.filterSabha_id = $('#filterSabha_id').val();
                        data.filterGroup_id = $('#filterGroup_id').val();
                        data.filterFollowup_id = $('#filterFollowup_id').val();
                        data.filterMemberType = $('#filterMemberType').val();
                        data.filterAttendingSabha = $('#filterAttendingSabha').val();
                        data.filterBloodGroup = $('#filterBloodGroup').val();
                        data.filterMaritalStatus = $('#filterMaritalStatus').val();
                        data.filterGender = $('#filterGender').val();
                        data.filterMemberTags = $('#filterMemberTags').val();
                        data.filterFromAge = $('#filterFromAge').val();
                        data.filterToAge = $('#filterToAge').val();
                        data.filterReferenceId = $('#filterReferenceId').val();
                        data.filterLastSabhaStatus = $('#filterLastSabhaStatus').val();
                        data.filterSabhaRegularity = $('#filterSabhaRegularity').val();
                    },
                },
                columns: [{
                        data: 'action',
                        name: 'action'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'photo',
                        name: 'photo'
                    },
                    {
                        data: 'member_type',
                        name: 'member_type'
                    },
                    {
                        data: 'mobile',
                        name: 'mobile'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'dob',
                        name: 'dob'
                    },
                    {
                        data: 'ref_name',
                        name: 'ref_name'
                    },
                    {
                        data: 'zone',
                        name: 'zone'
                    },
                    {
                        data: 'sabha',
                        name: 'sabha'
                    },
                    {
                        data: 'group',
                        name: 'group'
                    },
                    {
                        data: 'followup_name',
                        name: 'followup_name'
                    },
                    {
                        data: 'flat_no',
                        name: 'flat_no'
                    },
                    {
                        data: 'building_name',
                        name: 'building_name'
                    },
                    {
                        data: 'landmark',
                        name: 'landmark'
                    },
                    {
                        data: 'street_name',
                        name: 'street_name'
                    },
                    {
                        data: 'postcode',
                        name: 'postcode'
                    },
                    {
                        data: 'full_address',
                        name: 'full_address'
                    },
                    {
                        data: 'attending_sabha',
                        name: 'attending_sabha'
                    },
                    {
                        data: 'donoarId',
                        name: 'donoarId'
                    },
                    {
                        data: 'ambrish_code',
                        name: 'ambrish_code'
                    },
                    {
                        data: 'joining_date',
                        name: 'joining_date'
                    },
                    {
                        data: 'created_by',
                        name: 'created_by'
                    }
                ]
            });

            $('body').on('click', '.confirm-delete', function(event) {
                var action = $(this).data("action");
                event.preventDefault();
                Swal.fire({
                    title: "Are you sure you want to delete this record?",
                    text: "If you delete this, it will be gone forever.",
                    icon: "warning",
                    type: "warning",
                    buttons: ["Cancel", "Yes!"],
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: action,
                            data: {
                                _method: 'DELETE'
                            },
                            dataType: 'json',
                            success: function(response) {
                                DT_members.ajax.reload();
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Member has been deleted successfully.'
                                });
                            }
                        });
                    }
                });
            });

            $('body').on('hidden.bs.modal', '#modal-member-create,#modal-member-edit,#modal-member-view',
                function() {
                    $(this).find('.modal-content').html(`<div class="text-center p-5">
                    <i class="text-muted fas fa-spinner fa-spin" style="font-size: 25px;"></i>
                </div>`);
                });
        });
    </script>

    <script>
        //Zone
        $('.select2bs4-zones-livesearch').select2({
            theme: 'bootstrap4',
            allowClear: true,
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


        //Sabha
        var searchSabhaByZone = '';
        $('select[name="zone_id"]').change(function() {
            if ($(this).val()) {
                searchSabhaByZone = " and s.zone_id = " + $(this).val() + " ";
            } else {
                searchSabhaByZone = '';
            }
        });
        $('.select2bs4-sabhas-livesearch').select2({
            theme: 'bootstrap4',
            allowClear: true,
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


        //Group
        var searchGroupBySabha = '';
        $('select[name="sabha_id"]').change(function() {
            if ($(this).val()) {
                searchGroupBySabha = " and g.sabha_id = " + $(this).val() + " ";
            } else {
                searchGroupBySabha = '';
            }
        });
        $('.select2bs4-group-livesearch').select2({
            theme: 'bootstrap4',
            allowClear: true,
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


        //Followup
        var searchAdminByGroup = "";
        $('select[name="group_id"]').change(function() {
            if ($(this).val()) {
                searchAdminByGroup = "and a.admin_type = 'Followup_Admin' and a.group_id = " + $(this).val() + " ";
            } else {
                searchAdminByGroup = "";
            }
        });
        $('.select2bs4-admin-livesearch').select2({
            theme: 'bootstrap4',
            allowClear: true,
            placeholder: '',
            ajax: {
                url: "{{ route('admins.ajax-autocomplete-search') }}",
                data: function(params) {
                    var query = {
                        q: params.term,
                        whereSql: searchAdminByGroup
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

        //tags
        $('.select2bs4-tags-livesearch').select2({
            theme: 'bootstrap4',
            allowClear: true,
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
        });


        $('#btn-submit-filter').click(function() {
            //$('#btn-submit-filter-hiddn').trigger('click');
            DT_members.draw();
            $('button[data-dismiss="modal"]').trigger('click');
        });

        function ResetFilterForm() {
            document.getElementById("formMemberFilter").reset();
            $("#formMemberFilter select").val('').trigger('change');

            DT_members.draw();
            $('button[data-dismiss="modal"]').trigger('click');
        }
        // $("#formMemberFilter").submit(function(e) {
        //     e.preventDefault(); // avoid to execute the actual submit of the form.
        //     var formData = $('#formMemberFilter').serialize();

        //     // button disabled true
        //     $("#btn-submit-filter").prop("disabled", true).html(
        //         '<i class="fas fa-spinner fa-spin"></i> Processing');
        //     $("#btn-cancel-filter").prop("disabled", true);

        //     $.ajax({
        //         type: "GET",
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         url: "{{ route('members.index') }}",
        //         data: formData,
        //         dataType: 'json',
        //         success: function(response) {

        //             // button disabled false
        //             $("#btn-submit-filter").prop("disabled", false).html('Submit');
        //             $("#btn-cancel-filter").prop("disabled", false);

        //         }
        //     });
        // });

        //Member
        $('.select2bs4-members-livesearch').select2({
            theme: 'bootstrap4',
            allowClear: true,
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
    </script>
@endpush
