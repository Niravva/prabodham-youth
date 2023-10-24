@extends('layouts.app')

@section('title')
    Sabhas
@stop

@section('left_header_content')
    Manage Sabhas
@endsection

@section('right_header_content')
    <a href="javascript:void;" class="btn btn-default" data-toggle="modal" data-target="#modal-sabha-create"
        data-remote="{{ route('sabhas.create') }}" title="Add New Sabha">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
        </svg>
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card--c">
                <div class="card-body--c">
                    <table id="sabhasTable" class="table table-bordered nowrap" style="margin-top: 0 !important">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Zone</th>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Members</th>
                                <th>Head</th>
                                <th>Type</th>
                                <th>Flat/No</th>
                                <th>Building Name</th>
                                <th>Landmark</th>
                                <th>Street Name</th>
                                <th>Postcode</th>
                                <th>Status</th>
                                <th>Created By</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-sabha-create" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="text-center p-5">
                    <i class="text-muted fas fa-spinner fa-spin" style="font-size: 25px;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-sabha-edit" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="text-center p-5">
                    <i class="text-muted fas fa-spinner fa-spin" style="font-size: 25px;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-sabha-view" data-backdrop="static">
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
        var DT_sabhas;
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            DT_sabhas = $('#sabhasTable').DataTable({
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
                    url: "{{ route('sabhas.index') }}",
                    data: function(data) {},
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
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'zone_name',
                        name: 'zone_name'
                    },
                    {
                        data: 'day',
                        name: 'day'
                    },
                    {
                        data: 'time',
                        name: 'time'
                    },
                    {
                        data: 'number_of_member',
                        name: 'number_of_member'
                    },
                    {
                        data: 'sabha_head',
                        name: 'sabha_head'
                    },
                    {
                        data: 'sabha_type',
                        name: 'sabha_type'
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
                        data: 'status',
                        name: 'status'
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
                                DT_sabhas.ajax.reload();
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Sabha has been deleted successfully.'
                                });
                            }
                        });
                    }
                });
            });

            $('body').on('hidden.bs.modal', '#modal-sabha-create,#modal-sabha-edit,#modal-sabha-view', function() {
                $(this).find('.modal-content').html(`<div class="text-center p-5">
                    <i class="text-muted fas fa-spinner fa-spin" style="font-size: 25px;"></i>
                </div>`);
            });
        });
    </script>
@endpush
