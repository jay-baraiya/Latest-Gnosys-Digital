<x-master-layout>
    <div class="content pb-0">
        <x-page-header title="Manage {{ $moduleName }}" badge="0">
            <x-slot:breadcrumbs>
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Manage {{ $moduleName }}</li>
            </x-slot:breadcrumbs>

            <x-slot:actions>
                {{-- <div class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle btn btn-outline-light px-2 shadow"
                        data-bs-toggle="dropdown"><i class="ti ti-package-export me-2"></i>Export</a>
                    <div class="dropdown-menu  dropdown-menu-end">
                        <ul>
                            <li><a href="javascript:void(0);" class="dropdown-item"><i
                                        class="ti ti-file-type-pdf me-1"></i>Export as PDF</a></li>
                            <li><a href="javascript:void(0);" class="dropdown-item"><i
                                        class="ti ti-file-type-xls me-1"></i>Export as Excel </a></li>
                        </ul>
                    </div>
                </div> --}}
                <a href="javascript:void(0);" class="btn btn-outline-light shadow buyerRecode" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Deleted Recodes" data-bs-original-title="See Buyer Users" data-buyer-value="0">
                    See Buyer Users
                </a>
                <a href="javascript:void(0);" class="btn btn-outline-light shadow deletedRecode" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Deleted Recodes" data-bs-original-title="Deleted Recodes" data-value="1">
                    Deleted Recodes
                </a>
                <input type="hidden" name="is_deleted" id="is_deleted" value="0">
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip"
                    data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i
                        class="ti ti-refresh"></i></a>
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip"
                    data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse"
                    id="collapse-header"><i class="ti ti-transition-top"></i></a>
            </x-slot:actions>
        </x-page-header>

        <x-card>
            <x-slot:header>
                <div class="input-icon input-icon-start position-relative">
                    <span class="input-icon-addon text-dark"><i class="ti ti-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search" id="dataTable-search">
                </div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i
                        class="ti ti-square-rounded-plus-filled me-1"></i>Add
                    {{ rtrim($moduleName, 's') }}</a>
            </x-slot:header>

            <div class="table-responsive custom-table">
                <table class="table table-nowrap dataTableReload" id="manage-module-list">
                    <thead class="table-light">
                        <tr>
                            <th class="no-sort">#</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th class="text-end no-sort">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="datatable-length"></div>
                </div>
                <div class="col-md-6">
                    <div class="datatable-paginate"></div>
                </div>
            </div>

        </x-card>

    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                if ($('#manage-module-list').length > 0) {
                    var table = $('#manage-module-list').DataTable({
                        "bFilter": true,
                        "sDom": 'Btlpi',
                        "ordering": true,
                        "autoWidth": false,
                        "responsive": true,
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            "url": "{{ route('admin.users.getData') }}",
                            "type": "POST",
                            data: function(d) {
                                d.is_deleted = $('#is_deleted').val();
                                d.is_buyer = $('.buyerRecode').attr('data-buyer-value');
                            }
                        },
                        "language": {
                            search: ' ',
                            sLengthMenu: '_MENU_',
                            searchPlaceholder: "Search",
                            info: "_START_ - _END_ of _TOTAL_ items",
                            "lengthMenu": "Show _MENU_ entries",
                            paginate: {
                                next: '<i class="ti ti-chevron-right"></i> ',
                                previous: '<i class="ti ti-chevron-left"></i> '
                            },
                        },
                        initComplete: (settings, json) => {
                            $('.dataTables_paginate').appendTo('.datatable-paginate');
                            $('.dataTables_length').appendTo('.datatable-length');
                        },
                        drawCallback: function(settings) {
                            var api = this.api();
                            $('.record-count').text(api.ajax.json().total_users ?? 0);
                        },
                        "columns": [{
                                "data": "DT_RowIndex",
                                "name": "DT_RowIndex",
                                "orderable": false,
                                "searchable": false
                            },
                            {
                                "data": "name",
                                "name": "name"
                            },
                            {
                                "data": "role",
                                "name": "role"
                            },
                            {
                                "data": "email",
                                "name": "email"
                            },
                            {
                                "data": "phone",
                                "name": "phone"
                            },
                            {
                                "data": "status",
                                "name": "status",
                                "orderable": false,
                                "searchable": false
                            },
                            {
                                "data": "actions",
                                "name": "actions",
                                "orderable": false,
                                "searchable": false
                            }
                        ]
                    });

                    let timeout;

                    $('#dataTable-search').on('keyup', function() {
                        clearTimeout(timeout);
                        let value = this.value;

                        timeout = setTimeout(function() {
                            table.search(value).draw();
                        }, 500);
                    });
                }
            });
        </script>
    @endpush
</x-master-layout>
