@extends('layouts.app')

@section('title')
    Pradeshs
@stop

@section('left_header_content')
    Manage Pradeshs
@endsection

@section('right_header_content')
    <a href="javascript:void;" class="btn btn-default" data-toggle="modal" data-target="#modal-pradesh-create"
        data-remote="{{ route('pradeshs.create') }}" title="Add New Pradesh">
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
                    <table id="pradeshsTable" class="table table-bordered nowrap" style="margin-top: 0 !important">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Name</th>
                                <th>State</th>
                                <th>Zones</th>
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

    <div class="modal fade" id="modal-pradesh-create" data-backdrop="static">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="text-center p-5">
                    <i class="text-muted fas fa-spinner fa-spin" style="font-size: 25px;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-pradesh-edit" data-backdrop="static">
        <div class="modal-dialog modal-sm">
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
        var DT_pradeshs;
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            DT_pradeshs = $('#pradeshsTable').DataTable({
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
                    //sLengthMenu: "_MENU_",
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
                    url: "{{ route('pradeshs.index') }}",
                    data: function(data) {
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
                        data: 'state_name',
                        name: 'state_name'
                    },
                    {
                        data: 'number_of_zone',
                        name: 'number_of_zone'
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
                                DT_pradeshs.ajax.reload();
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Pradesh has been deleted successfully.'
                                });
                            }
                        });
                    }
                });
            });

            $('body').on('hidden.bs.modal', '#modal-pradesh-create,#modal-pradesh-edit', function() {
                $(this).find('.modal-content').html(`<div class="text-center p-5">
                    <i class="text-muted fas fa-spinner fa-spin" style="font-size: 25px;"></i>
                </div>`);
            });
        });
    </script>
@endpush
