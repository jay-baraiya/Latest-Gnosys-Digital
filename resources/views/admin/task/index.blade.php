<x-master-layout>
    <div class="content pb-0">
        <x-page-header title="Manage {{ $moduleName }}" badge="0">
            <x-slot:breadcrumbs>
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Manage {{ $moduleName }}</li>
            </x-slot:breadcrumbs>

            <x-slot:actions>
                <input type="hidden" name="is_deleted" id="is_deleted" value="0">
                <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip"
                    data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh" id="refresh-table"><i
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
                <a href="{{ route('admin.tasks.create') }}" class="btn btn-primary"><i
                        class="ti ti-square-rounded-plus-filled me-1"></i>Add
                    {{ rtrim($moduleName, 's') }}</a>
            </x-slot:header>

            <div class="table-responsive custom-table">
                <table class="table table-nowrap dataTableReload align-middle" id="manage-module-list">
                    <thead class="table-light">
                        <tr>
                            <th class="no-sort" style="width: 50px;">#</th>
                            <th>Order Number</th>
                            <th>Order Date</th>
                            <th class="no-sort">Ticket Number</th>
                            <th class="no-sort">Ticket Name</th>
                            <th>Customer Name</th>
                            <th>Developer Name</th>
                            <th>Total Amount</th>
                            <th class="text-end no-sort">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="row align-items-center mt-3">
                <div class="col-md-6">
                    <div class="datatable-length"></div>
                </div>
                <div class="col-md-6">
                    <div class="datatable-paginate"></div>
                </div>
            </div>

        </x-card>

    </div>

    <div id="viewTicketListModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="viewTicketListModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="viewTicketListModalLabel">Tickets List</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="tickets-listing">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Modal Heading</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Text in a modal</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {

                $(document).on('click', '.assign-dev', function() {
                    let $span = $(this);
                    let $parent = $span.closest('.dev-section');
                    let ticketId = $span.data('ticket-id');

                    if ($parent.find('.dev-select').length > 0) return;

                    $span.hide();

                    let $select = $('<select>', {
                        class: 'form-select form-select-sm dev-select',
                        'data-ticket-id': ticketId
                    }).append('<option value="">Loading...</option>');

                    $parent.append($select);
                    $select.focus();

                    $.ajax({
                        url: '{{ route('admin.tasks.dev.user') }}',
                        method: 'POST',
                        success: function(response) {
                            $select.empty().append('<option value="">Select Developer...</option>');
                            if (response.data) {
                                $.each(response.data, function(index, dev) {
                                    let roleText = (dev.roles && dev.roles.length > 0) ? ` - ( ${dev.roles[0].name} ) ` : '';
                                    $select.append($('<option>', {
                                        value: dev.id,
                                        text: dev.name + roleText
                                    }));
                                });
                            }
                        },
                        error: function() {
                            alert('Failed to load developers. Please try again.');
                            $select.remove();
                            $span.show();
                        }
                    });
                });

                $(document).on('change', '.dev-select', function() {
                    let $select = $(this);
                    let devId = $select.val();
                    let devName = $select.find('option:selected').text();
                    let ticketId = $select.data('ticket-id');
                    let $parent = $select.closest('.dev-section');

                    if (devId) {
                        $select.prop('disabled', true);

                        $.ajax({
                            url: '{{ route('admin.tasks.assign.dev.user') }}',
                            method: 'POST',
                            data: {
                                ticket_id: ticketId,
                                developer_id: devId,
                            },
                            success: function(response) {
                                if (response.success) {
                                    $parent.html(`<span class="fw-medium text-dark cursor-pointer">${devName}</span>`);
                                } else {
                                    alert('Error: ' + response.message);
                                    $select.prop('disabled', false);
                                }
                            },
                            error: function() {
                                alert('Something went wrong. Please try again.');
                                $select.prop('disabled', false);
                            }
                        });
                    } else {
                        $select.remove();
                        $parent.find('.assign-dev').show();
                    }
                });

                $(document).on('focusout', '.dev-select', function() {
                    let $select = $(this);
                    setTimeout(function() {
                        if (!$select.val()) {
                            let $parent = $select.closest('.dev-section');
                            $select.remove();
                            $parent.find('.assign-dev').show();
                        }
                    }, 100);
                });

                $(document).on('click', '.view-tickets-btn', function() {
                    var encryptedID = $(this).data('order-id');

                    if (!encryptedID) return;

                    $('#tickets-listing').html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading tickets...</p></div>');

                    $.ajax({
                        url: "{{ route('admin.tasks.order.ticket.listing') }}",
                        type: "POST",
                        data: {
                            order_id: encryptedID
                        },
                        success: function(response) {
                            if(response.status === 'success') {
                                $('#tickets-listing').html(response.html);
                            } else {
                                $('#tickets-listing').html('<div class="alert alert-danger">Failed to load tickets.</div>');
                            }
                        },
                        error: function(xhr) {
                            $('#tickets-listing').html('<div class="alert alert-danger">Something went wrong. Please try again.</div>');
                        }
                    });

                    $('#viewTicketListModal').modal('show');
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
                            // Updated route to fetch task/order data
                            "url": "{{ route('admin.tasks.getData') }}",
                            "type": "POST",
                            data: function(d) {
                                d.is_deleted = $('#is_deleted').val();
                                // Optional: Keep this if you still use it for filtering
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
                            // Updated to read total_tasks from the new controller setup
                            $('.record-count').text(api.ajax.json().total_tasks ?? 0);
                        },
                        "columns": [
                            { "data": "DT_RowIndex", "name": "DT_RowIndex", "orderable": false, "searchable": false },
                            { "data": "order_number", "name": "order_number" },
                            { "data": "order_date", "name": "date_time" },
                            { "data": "ticket_number", "name": "ticket_number", "orderable": false, "searchable": false },
                            { "data": "ticket_name", "name": "tickets_list", "orderable": false, "searchable": false },
                            { "data": "customer_name", "name": "user.name" },
                            { "data": "developer_name", "name": "developer_name" },
                            { "data": "total_amount", "name": "total_amount" },
                            { "data": "actions", "name": "actions", "orderable": false, "searchable": false }
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

                    // Optional: Make the refresh button actually reload the datatable
                    $('#refresh-table').on('click', function() {
                        table.ajax.reload(null, false);
                    });
                }
            });
        </script>
    @endpush
</x-master-layout>
