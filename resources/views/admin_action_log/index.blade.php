@extends('layouts.app')

@section('title')
    Action Logs
@stop

@section('left_header_content')
    Action Logs
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card--c">
                <div class="card-body--c">
                    <table id="activityTable" class="table table-bordered nowrap" style="margin-top: 0 !important">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Description</th>
                                <th>Action By</th>
                                <th>Action At</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript">
        var DT_admin_action_log;
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            DT_admin_action_log = $('#activityTable').DataTable({
                stateSave: true,
                stateSaveParams: function(settings, data) {
                    delete data.length;
                    //console.log(data);
                },
                dom: "<'row'<'col-4 col-md-6'l><'col col-md-6'f>>" +
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
                columnDefs: [
                    {
                        orderable: false,
                        targets: '_all'
                    }
                ],
                order: [
                    [1, 'asc']
                ],
                ajax: "{{ route('action-logs.index') }}",
                columns: [{
                        data: 'action_type',
                        name: 'action_type'
                    },
                    {
                        data: 'action_description',
                        name: 'action_description'
                    },
                    {
                        data: 'action_by',
                        name: 'action_by'
                    },
                    {
                        data: 'action_date',
                        name: 'action_date'
                    },
                    {
                        data: 'ip_address',
                        name: 'ip_address'
                    }
                ]
            });

        });
    </script>
@endpush
