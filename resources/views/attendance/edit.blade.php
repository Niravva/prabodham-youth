@extends('layouts.app')

@section('title')
    Attendances
@stop

@section('left_header_content')
    {{ $sabha_date }}
@endsection

@section('right_header_content')
    @if (!in_array(get_current_admin_level(), ['Followup_Admin']))
        <button id="btn-submit" type="button" class="btn btn-default px-4">Save</button>
    @endif
@endsection

@section('left_header_back_button')
    <a class="nav-link mr-3 pl-1" href="{{ route('attendances.index') }}" role="button">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#fff"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
        </svg>
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card--c">
                <div class="card-body--c">
                    <form id="formEditAttendances" action="{{ route('attendances.update', $attendance->id) }}"
                        method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
                        <table id="attendanceTable2" class="table table-bordered nowrap" style="margin-top: 0 !important">
                            <thead>
                                <tr>
                                    <th>Name/Mobile</th>
                                    <th>Is Present?</th>
                                    <th>Absentes</th>
                                    <th>Group</th>
                                    <th>Followup Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attenders as $key => $row)
                                    <tr>
                                        <td>
                                            <p class="mb-0">
                                                <strong>{{ $row->first_name . ' ' . $row->middle_name . ' ' . $row->surname }}</strong>
                                            </p>
                                            <p class="mb-0">{{ $row->mobile }}</p>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input name="attdmem[]" class="custom-control-input" type="checkbox"
                                                    value="{{ $row->member_id }}" id="attdmem-{{ $row->member_id }}"
                                                    {{ $row->present == 'Yes' ? 'checked' : '' }}
                                                    onclick="attendanceSingleMember({{ $row->id }})">
                                                <label for="attdmem-{{ $row->member_id }}" class="custom-control-label">
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            {{ get_member_attendance_last_absence_count($row->member_id) }}
                                        </td>
                                        <td>
                                            {{ get_group_name($row->group_id) }}
                                        </td>
                                        <td>{{ get_admin_name($row->follow_up_by) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button id="btn-submit-hiddn" type="submit" style="display: none;">Save</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="attendance-count-footer">
        <div class="row align-items-center text-center w-100">
            <div class="col-4 border-right text-success" onclick="hide_show_list_by_type('present')">
                <h5 class="m-0" id="PresentCount">0</h5>
                <div>Present</div>
            </div>
            <div class="col-4 border-right text-danger" onclick="hide_show_list_by_type('absent')">
                <h5 class="m-0" id="AbsentCount">0</h5>
                <div>Absent</div>
            </div>
            <div class="col-4 text-dark" onclick="hide_show_list_by_type('total')">
                <h5 class="m-0" id="TotalCount">0</h5>
                <div>Total</div>
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
@endsection

@push('scripts')
    <script type="text/javascript">
        show_footer_count();
        var DT_attendances2;
        $(document).ready(function() {

            $(document).on('click', '#btn-submit', function() {
                $('#btn-submit-hiddn').trigger('click');
            });

            DT_attendances2 = $('#attendanceTable2').DataTable({
                stateSave: true,
                dom: "<'row'<'col-sm-12 col-md-6'><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                scrollX: true,
                autoWidth: false,
                columnDefs: [ // see https://datatables.net/reference/option/columns.searchable
                    {
                        searchable: false,
                        targets: [1, 2, 3, 4]
                    },
                ],
                lengthChange: false,
                ordering: false,
                pageLength: 999999,
                language: {
                    search: "",
                    sSearchPlaceholder: "Search",
                    sLengthMenu: "Show _MENU_",
                },
                bPaginate: false,
                info: false,
            });

            $("#formEditAttendances").submit(function(e) {
                e.preventDefault(); // avoid to execute the actual submit of the form.
                var formData = $('#formEditAttendances').serialize();

                // button disabled true
                $("#btn-submit").prop("disabled", true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Processing');
                $("#btn-cancel").prop("disabled", true);

                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('attendances.update', $attendance->id) }}",
                    data: formData,
                    dataType: 'json',
                    success: function(response) {

                        // button disabled false
                        $("#btn-submit").prop("disabled", false).html('Save');
                        $("#btn-cancel").prop("disabled", false);

                        if (response.success == 1) {
                            $("span.invalid-feedback").remove();
                            $('#modal-attendance-edit').modal('hide');
                            Toast.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Attendance has been updated successfully.'
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
        });

        function attendanceSingleMember(id) {
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('attendances.attendanceSingleMember') }}",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {

                    $("#TotalCount").text(response.TotalCount);
                    $("#PresentCount").text(response.PresentCount);
                    $("#AbsentCount").text(response.AbsentCount);

                    if (response.success == 1) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Attendance has been taken successfully.'
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
        }

        function show_footer_count() {
            $("#TotalCount").text($("#formEditAttendances tbody tr").length);
            $("#PresentCount").text($("#formEditAttendances tbody tr input[type='checkbox']:checked").length);
            $("#AbsentCount").text($("#formEditAttendances tbody tr input[type='checkbox']:not(:checked)").length);
        }

        function hide_show_list_by_type(type) {
            $("#formEditAttendances tbody tr").hide();
            if ("total" == type) {
                $("#formEditAttendances tbody tr").show();
            }
            if ("present" == type) {
                $("#formEditAttendances tbody tr input[type='checkbox']:checked").parents("tr").show();
            }
            if ("absent" == type) {
                $("#formEditAttendances tbody tr input[type='checkbox']:not(:checked)").parents("tr").show();
            }
        }
    </script>
@endpush
