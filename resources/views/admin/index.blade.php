@extends('layouts.app')

@section('title')
    Admins
@stop

@section('left_header_content')
    Manage Admins
@endsection

@section('right_header_content')
    <a href="javascript:void;" class="btn btn-default" data-toggle="modal" data-target="#modal-admin-create"
        data-remote="{{ route('admins.create') }}" title="Add New Admin">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
        </svg>
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card--c">
                <div class="card-body--c">
                    <table id="adminsTable" class="table table-bordered nowrap" style="margin-top: 0 !important">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Zone</th>
                                <th>Sabha</th>
                                <th>Group</th>
                                <th>Mobile</th>
                                <th>Email</th>
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

    <div class="modal fade" id="modal-admin-create" data-backdrop="static">
        <div class="modal-dialog modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="text-center p-5">
                    <i class="text-muted fas fa-spinner fa-spin" style="font-size: 25px;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-admin-edit" data-backdrop="static">
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
        var DT_admins;
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            DT_admins = $('#adminsTable').DataTable({
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
                    url: "{{ route('admins.index') }}",
                    data: function(data) {},
                },
                columns: [{
                        data: 'action',
                        name: 'action',
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'admin_type',
                        name: 'admin_type',
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
                        data: 'mobile_number',
                        name: 'mobile_number',
                    },
                    {
                        data: 'email',
                        name: 'email',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'created_by',
                        name: 'created_by',
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
                                DT_admins.ajax.reload();
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Admin has been deleted successfully.'
                                });
                            }
                        });
                    }
                });
            });

            $('body').on('hidden.bs.modal', '#modal-admin-create,#modal-admin-edit', function() {
                $(this).find('.modal-content').html(`<div class="text-center p-5">
                    <i class="text-muted fas fa-spinner fa-spin" style="font-size: 25px;"></i>
                </div>`);
            });
        });
    </script>
@endpush
