@extends('layouts.app')

@section('title') Attendance Report @stop

@section('left_header_content')
    Attendance Report
@endsection

@section('right_header_content')
    <a href="javascript:void;" data-toggle="modal" data-target="#modal-filter" class="btn btn-default" title="Filter">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
        </svg>
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <table id="attendanceReportTable" class="table table-bordered nowrap" style="margin-top: 0 !important">
                <thead>
                    <tr>
                        <th>Sabha Date</th>
                        <th>Zone</th>
                        <th>Sabha</th>
                        <th>Group Name</th>
                        <th>Followup</th>
                        <th>Presence</th>
                        {{-- <th>Absentes</th> --}}
                        <th>Member Name</th>
                        <th>Member Type</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Address</th>
                        <th>Gender</th>
                        <th>Date of Birth</th>
                        <th>Marital Status</th>
                        {{-- <th>Anniversery Date</th> --}}
                        <th>Attending Sabha</th>
                        <th>Joining Date</th>
                        <th>Ref Name</th>
                        <th>AVD ID</th>
                        <th>Ambrish Code</th>
                        <th>Performing Puja</th>
                        <th>Nishtawan</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modal-filter" data-backdrop="static">
        <div class="modal-dialog modal-md modal-dialog-scrollable">
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
                    <form id="formFilter" enctype="multipart/form-data">

                        <div class="row">
                            <div class="col-4 col-md-3">
                                <div class="form-group">
                                    <label>Presence</label>
                                    <select name="presence" id="filterPresence" class="form-control select2bs4">
                                        <option value="">Any</option>
                                        <option value="Yes">Present</option>
                                        <option value="No">Absent</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-8 col-md-9">
                                <div class="form-group">
                                    <label>Sabha Dates</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="filterFormDate" placeholder="Form">
                                        <input type="text" class="form-control" id="filterToDate" placeholder="To">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label>Conditions</label>
                                    <div class="input-group">
                                        <select name="presence" id="filterConditions" class="form-control select2bs4">
                                            <option value="<"><</option>
                                            <option value=">="><=</option>
                                            <option value=">">></option>
                                            <option value=">=">>=</option>
                                        </select>
                                        <input style="width:60%;" type="number" class="form-control" id="filterNoOfSabha" placeholder="Last Absence Number of Sabha">
                                    </div>
                                </div>
                            </div>
                        </div> --}}


                        <div class="row">
                            @if (in_array(get_current_admin_level(), ['Super_Admin', 'Country_Admin', 'State_Admin', 'Pradesh_Admin']))
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Zone</label>
                                        <select name="zone_id" id="filterZone_id"
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

                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button id="btn-clear-filter" type="button" class="btn btn-danger" onclick="ResetFilterForm()">Clear
                        Filter</button>
                    <button id="btn-submit-filter" type="submit" class="btn btn-primary px-4">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script type="text/javascript">
        var DT_attendanceReportTable;
        $(document).ready(function() {

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            DT_attendanceReportTable = $('#attendanceReportTable').DataTable({
                dom: "<'row'<'col-4 col-md-4'><'col col-md-4 mb-2 datatabel-export-buttons'B><'col-sm-12 col-md-4'>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'>>",
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
                pageLength: 999999999,
                columnDefs: [{
                    orderable: false,
                    targets: '_all'
                }],
                ajax: {
                    url: "{{ route('reports.attendance') }}",
                    data: function(data) {
                        data.filterZone_id = $('#filterZone_id').val();
                        data.filterSabha_id = $('#filterSabha_id').val();
                        data.filterGroup_id = $('#filterGroup_id').val();
                        data.filterFollowup_id = $('#filterFollowup_id').val();
                        data.filterFormDate = $('#filterFormDate').val();
                        data.filterToDate = $('#filterToDate').val();
                        data.filterPresence = $('#filterPresence').val();
                        // data.filterConditions = $('#filterConditions').val();
                        // data.filterNoOfSabha = $('#filterNoOfSabha').val();
                    },
                }
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

        $('#btn-submit-filter').click(function() {
            DT_attendanceReportTable.draw();
            $('button[data-dismiss="modal"]').trigger('click');
        });

        function ResetFilterForm() {
            document.getElementById("formFilter").reset();
            $("#formFilter select").val('').trigger('change');

            DT_attendanceReportTable.draw();
            $('button[data-dismiss="modal"]').trigger('click');
        }



        $('#filterFormDate, #filterToDate').daterangepicker({
            parentEl: "modal-body",
            locale: {
                format: 'DD-MM-YYYY'
            },
            autoUpdateInput: false,
            singleDatePicker: true,
            //showDropdowns: true,
            minYear: 1901,
            maxYear: parseInt(moment().format('YYYY'), 10)
        });
        $('#filterFormDate').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY'));
        });
        $('#filterToDate').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY'));
        });
    </script>
@endpush
