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
                @can('create.' . strtolower($moduleName))
                    <a href="{{ route('admin.wallets.create') }}" class="btn btn-primary">
                        <i class="ti ti-square-rounded-plus-filled me-1"></i>
                        Add Balance
                    </a>
                @endcan
            </x-slot:header>

            <div class="table-responsive custom-table">
                <table class="table table-nowrap dataTableReload" id="manage-module-list">
                    <thead class="table-light">
                        <tr>
                            <th class="no-sort">#</th>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Balance</th>
                            <th>Approvement</th>
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


    <!-- Standard modal content -->
    <div id="walletModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="wallet-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="wallet-modalLabel">Wallet History</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body historyData">
                </div>
            </div>
        </div>
    </div>

    <!-- Reason Modal -->
    <div id="reasonModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="reason-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="reason-modalLabel">Reason</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="reasonText" style="white-space: pre-wrap; font-size: 15px; color: #333;"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {


                $(document).on('click', '.showWalletHistory', function (e) {
                    e.preventDefault();
                    $('#walletModal').modal('show');

                    var url = $(this).attr('href');
                    var modal = $('#walletModal');

                    if (url) {

                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },

                            beforeSend: function () {
                                $('.historyData').html('Loading...');
                            },

                            success: function (response) {

                                $('.historyData').html('');
                                $('.historyData').html(response.html);

                                modal.modal('show');
                            },

                            error: function (xhr) {
                                $('.historyData').html('<center><p>History not found!</p></center>');
                                console.log(xhr.responseText);
                            }
                        });
                    }
                });

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
                            "url": "{{ route('admin.wallets.getData') }}",
                            "type": "POST",
                            data: function (d) {
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
                        drawCallback: function (settings) {
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
                            "data": "date",
                            "name": "date"
                        },
                        {
                            "data": "name",
                            "name": "name"
                        },
                        {
                            "data": "balance",
                            "name": "balance"
                        },
                        {
                            "data": "is_approved",
                            "name": "is_approved"
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

                    $('#dataTable-search').on('keyup', function () {
                        clearTimeout(timeout);
                        let value = this.value;

                        timeout = setTimeout(function () {
                            table.search(value).draw();
                        }, 500);
                    });
                }

                // Show Reason Popup
                $(document).on('click', '.show-reason', function(e) {
                    e.preventDefault();
                    var reason = $(this).attr('data-reason');
                    var title = $(this).attr('data-title');
                    
                    $('#reason-modalLabel').text(title);
                    $('#reasonText').text(reason ? reason : 'No reason provided.');
                    $('#reasonModal').modal('show');
                });

            });
        </script>
    @endpush
</x-master-layout>