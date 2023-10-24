@extends('layouts.app')

@section('title')
    Attendances
@stop

@section('left_header_content')
    Attendances
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
            <div class="card--c">
                <div class="card-body--c">
                    <table id="attendanceTable" class="table table-bordered nowrap" style="margin-top: 0 !important">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Sabha</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Present</th>
                                <th>Absence</th>
                                <th>Percentage</th>
                                <th>Sabha Date</th>
                                <th>Zone</th>
                                <th>1 Vakta</th>
                                <th>1 Topic</th>
                                <th>2 Vakta</th>
                                <th>2 Topic</th>
                                <th>Cancel Reason</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-add-edit-vakta" data-backdrop="static">
        <div class="modal-dialog modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="text-center p-5">
                    <i class="text-muted fas fa-spinner fa-spin" style="font-size: 25px;"></i>
                </div>
            </div>
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
                            <div class="col-6 col-md-4">
                                <div class="form-group">
                                    <label>Sabha Date</label>
                                    <input type="text" class="form-control" id="filterSabhaDate">
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select id="filterStatus" class="form-control select2bs4">
                                        <option value="">Any</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Cancel">Cancel</option>
                                    </select>
                                </div>
                            </div>
                        </div>


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
        var DT_attendances;
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

            DT_attendances = $('#attendanceTable').DataTable({
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
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                pageLength: 25,
                language: {
                    search: "",
                    sSearchPlaceholder: "Search",
                    sLengthMenu: "Show _MENU_",
                },
                columnDefs: [{
                    orderable: false,
                    targets: '_all'
                }],
                ajax: {
                    url: "{{ route('attendances.index') }}",
                    data: function(data) {
                        data.filterZone_id = $('#filterZone_id').val();
                        data.filterSabha_id = $('#filterSabha_id').val();
                        data.filterSabhaDate = $('#filterSabhaDate').val();
                        data.filterStatus = $('#filterStatus').val();
                    },
                },
                columns: [{
                        data: 'action',
                        name: 'action'
                    },
                    {
                        data: 'sabha_name',
                        name: 'sabha_name'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'total_member',
                        name: 'total_member'
                    },
                    {
                        data: 'present_member',
                        name: 'present_member'
                    },
                    {
                        data: 'absence_member',
                        name: 'absence_member'
                    },
                    {
                        data: 'percentage',
                        name: 'percentage'
                    },
                    {
                        data: 'sabha_date',
                        name: 'sabha_date'
                    },
                    {
                        data: 'zone_name',
                        name: 'zone_name'
                    },
                    {
                        data: '1vakta',
                        name: '1vakta'
                    },
                    {
                        data: '1topic',
                        name: '1topic'
                    },
                    {
                        data: '2vakta',
                        name: '2vakta'
                    },
                    {
                        data: '2topic',
                        name: '2topic'
                    },
                    {
                        data: 'reason',
                        name: 'reason'
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
                        const reason = result.value;
                        // Proceed with the cancellation logic using the 'reason' variable
                        $.ajax({
                            type: "POST",
                            url: action,
                            data: {
                                _method: 'DELETE'
                            },
                            dataType: 'json',
                            success: function(response) {
                                DT_attendances.ajax.reload();
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Attendance has been deleted successfully.'
                                });
                            }
                        });
                    }
                });
            });

            $('body').on('click', '.cancel_sabha', function(event) {
                var action = $(this).data("action");
                var sabhaId = $(this).data("sabha");
                event.preventDefault();
                Swal.fire({
                    title: "Are you sure you want to cancel this sabha?",
                    icon: "question",
                    type: "question",
                    allowOutsideClick: false,
                    input: 'text', // Change input type to text
                    inputPlaceholder: 'Sabha cancellation reason',
                    inputAttributes: {
                        minlength: 10,
                        autocapitalize: 'off',
                        autocorrect: 'off'
                    },
                    confirmButtonColor: 'rgb(243 111 36)',
                    confirmButtonText: '&nbsp;&nbsp;Yes!&nbsp;&nbsp;',
                    cancelButtonText: '&nbsp;&nbsp;No!&nbsp;&nbsp;',
                    showCancelButton: true,
                    inputValidator: (value) => {
                        if (!value || value.length < 10) {
                            return 'Please enter sabha cancelation reason (at least 10 characters)';
                        }
                    },
                    preConfirm: (reason) => {
                        return reason; // This will resolve the promise and close the dialog
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const reason = result.value;
                        // Proceed with the cancellation logic using the 'reason' variable
                        $.ajax({
                            type: "POST",
                            url: action,
                            data: {
                                sabha: sabhaId,
                                reason: reason
                            },
                            dataType: 'json',
                            success: function(response) {
                                DT_attendances.ajax.reload();
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Sabha cancelation successfully.'
                                });
                            }
                        });
                    }
                });
            });

            $('body').on('hidden.bs.modal',
                '#modal-attendance-edit,#modal-add-edit-vakta',
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


        $('#btn-submit-filter').click(function() {
            DT_attendances.draw();
            $('button[data-dismiss="modal"]').trigger('click');
        });

        function ResetFilterForm() {
            document.getElementById("formFilter").reset();
            $("#formFilter select").val('').trigger('change');

            DT_attendances.draw();
            $('button[data-dismiss="modal"]').trigger('click');
        }



        $('#filterSabhaDate').daterangepicker({
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
        $('#filterSabhaDate').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY'));
        });
    </script>
@endpush
