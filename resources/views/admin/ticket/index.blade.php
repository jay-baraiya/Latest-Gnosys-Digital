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
            <div class="card border-0 mb-3">
                <div class="card-body pb-0 pt-0 px-2">
                    <ul class="nav nav-tabs nav-bordered nav-bordered-primary">
                        <li class="nav-item me-3">
                            <a href="#" data-type="priority" data-value="Low" class="nav-link filter-tab p-2 {{ $priority == 'Low' ? 'active' : '' }}">
                                Low
                            </a>
                        </li>
                        <li class="nav-item me-3">
                            <a href="#" data-type="priority" data-value="Medium" class="nav-link filter-tab p-2 {{ $priority == 'Medium' ? 'active' : '' }}">
                                Medium
                            </a>
                        </li>
                        <li class="nav-item me-3">
                            <a href="#" data-type="priority" data-value="High" class="nav-link filter-tab p-2 {{ $priority == 'High' ? 'active' : '' }}">
                                High
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card border-0">
                <div class="card-body pb-0 pt-0 px-2">
                    <ul class="nav nav-tabs nav-bordered nav-bordered-primary">
                        <li class="nav-item me-3">
                            <a href="#" data-type="status" data-value="pending" class="nav-link filter-tab p-2 {{ $status == 'pending' ? 'active' : '' }}">
                                Pending
                            </a>
                        </li>
                        <li class="nav-item me-3">
                            <a href="#" data-type="status" data-value="assigned" class="nav-link filter-tab p-2 {{ $status == 'assigned' ? 'active' : '' }}">
                                Assigned
                            </a>
                        </li>
                        <li class="nav-item me-3">
                            <a href="#" data-type="status" data-value="in_progress" class="nav-link filter-tab p-2 {{ $status == 'in_progress' ? 'active' : '' }}">
                                In Progress
                            </a>
                        </li>
                        <li class="nav-item me-3">
                            <a href="#" data-type="status" data-value="completed" class="nav-link filter-tab p-2 {{ $status == 'completed' ? 'active' : '' }}">
                                Completed
                            </a>
                        </li>
                        <li class="nav-item me-3">
                            <a href="#" data-type="status" data-value="cancelled" class="nav-link filter-tab p-2 {{ $status == 'cancelled' ? 'active' : '' }}">
                                Cancelled
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <x-slot:header>
                <div class="d-flex align-items-center flex-wrap gap-2 w-100">

                    <div class="input-icon input-icon-start position-relative">
                        <span class="input-icon-addon text-dark">
                            <i class="ti ti-search"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Search" id="dataTable-search">
                    </div>

                    <div style="min-width: 180px;">
                        <select name="filter_ticket_number" id="filter-ticket-number" class="form-control filter_ticket_number">
                            <option value="">Select Ticket Number</option>
                        </select>
                    </div>

                    <button type="button" class="btn btn-outline-secondary" id="clear-filters">
                        <i class="ti ti-refresh me-1"></i>Clear Filters
                    </button>

                    {{-- @can('create.'.$moduleName) --}}
                        <a href="{{ route('admin.tickets.create') }}" class="btn btn-primary ms-auto">
                            <i class="ti ti-square-rounded-plus-filled me-1"></i>Add {{ rtrim($moduleName, 's') }}
                        </a>
                    {{-- @endcan --}}
                </div>
            </x-slot:header>

            <div class="table-responsive custom-table">
                <table class="table table-nowrap dataTableReload align-middle" id="manage-module-list">
                    <thead class="table-light">
                        <tr>
                            <th class="no-sort" style="width: 50px;">#</th>
                            <th>Ticket Number</th>
                            <th>Date</th>
                            <th>Client Info</th>
                            <th>Subject</th>
                            <th>Department</th>
                            <th>Priority</th>
                            <th>Status</th>
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

                var currentStatus = '{{ $status }}';
                var currentPriority = '{{ $priority }}';

                $(document).on('click', '.filter-tab', function(e) {
                    e.preventDefault();

                    var filterType = $(this).data('type');
                    var filterValue = $(this).data('value');

                    var url = new URL(window.location.href);

                    if (filterType === 'priority') {
                        currentPriority = filterValue;
                        url.searchParams.set('priority', currentPriority);
                    } else if (filterType === 'status') {
                        currentStatus = filterValue;
                        url.searchParams.set('status', currentStatus);
                    }

                    window.history.replaceState({}, '', url);

                    $(this).closest('ul').find('.filter-tab').removeClass('active');
                    $(this).addClass('active');

                    $('.dataTableReload').DataTable().ajax.reload(null, false);
                });

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
                        url: '{{ route('admin.tickets.dev.user') }}',
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
                            url: '{{ route('admin.tickets.assign.dev.user') }}',
                            method: 'POST',
                            data: {
                                ticket_id: ticketId,
                                developer_id: devId,
                            },
                            success: function(response) {
                                if (response.success) {
                                    $parent.html(`<span class="assign-dev fw-medium text-dark cursor-pointer" data-ticket-id="${ticketId}">${devName}</span>`);
                                } else {
                                    alert('Error: ' + response.message);
                                    $select.prop('disabled', false);
                                }
                                $('.dataTableReload').DataTable().ajax.reload(null, false);
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
                        url: "{{ route('admin.tickets.order.ticket.listing') }}",
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
                            "url": "{{ route('admin.tickets.getData') }}",
                            "type": "POST",
                            data: function(d) {
                                d.is_deleted = $('#is_deleted').val();
                                d.ticket_number = $('#filter-ticket-number').val();
                                d.status = currentStatus;
                                d.priority = currentPriority;
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
                            $('.record-count').text(api.ajax.json().total_tasks ?? 0);
                        },
                        "columns": [
                            { "data": "DT_RowIndex", "name": "DT_RowIndex", "orderable": false, "searchable": false },

                            { "data": "ticket_number", "name": "ticket_number", "orderable": true, "searchable": true },

                            { "data": "date", "name": "datetime", "orderable": true, "searchable": false },

                            { "data": "client_info", "name": "name", "orderable": true, "searchable": true },

                            { "data": "subject", "name": "subject", "orderable": true, "searchable": true },

                            { "data": "department", "name": "department_id", "orderable": true, "searchable": false },

                            { "data": "priority", "name": "priority", "orderable": true, "searchable": false },

                            { "data": "status", "name": "status", "orderable": true, "searchable": false },

                            { "data": "actions", "name": "actions", "orderable": false, "searchable": false }
                        ]
                    });

                    // Custom Search Debounce Logic
                    let timeout;
                    $('#dataTable-search').on('keyup', function() {
                        clearTimeout(timeout);
                        let value = this.value;

                        timeout = setTimeout(function() {
                            table.search(value).draw();
                        }, 500);
                    });
                }

                var developers = @json($developers);

                $(document).on('click', '.change-status-btn', function(e) {
                    e.preventDefault();

                    let $btn = $(this);
                    let ticketId = $btn.data('id');
                    let newStatus = $btn.data('status');
                    let statusLabel = $btn.text().trim();
                    let url = '{{ route('admin.tickets.update.status') }}';

                    if (!ticketId || !newStatus) return;

                    let swalConfig = {
                        title: "Are you sure?",
                        icon: "warning",
                        showCancelButton: true,
                        customClass: {
                            confirmButton: "btn btn-primary me-2 mt-2",
                            cancelButton: "btn btn-danger mt-2",
                        },
                        buttonsStyling: false,
                        showCloseButton: true,
                    };

                    if (newStatus === 'assigned') {
                        swalConfig.title = "Assign Ticket";
                        swalConfig.confirmButtonText = "Assign & Update Status";

                        let options = '<option value="">-- Select Employee/User --</option>';

                        developers.forEach(function(developer) {
                            options += `<option value="${developer.id}">${developer.name}</option>`;
                        });

                        swalConfig.html = `
                            <div class="mt-3 text-start">
                                <label for="swal-assign-id" class="form-label">
                                    Select Assignee <span class="text-danger">*</span>
                                </label>
                                <select id="swal-assign-id" class="form-select form-control">
                                    ${options}
                                </select>
                            </div>
                        `;

                        swalConfig.preConfirm = () => {
                            const assignId = document.getElementById('swal-assign-id').value;
                            if (!assignId) {
                                Swal.showValidationMessage('Please select a user to assign the ticket!');
                                return false;
                            }
                            return { assign_id: assignId };
                        };
                    } else {
                        swalConfig.text = "You want to update status to " + statusLabel + "?";
                        swalConfig.confirmButtonText = "Yes, update it!";
                    }

                    Swal.fire(swalConfig).then(function(result) {
                        if (result.isConfirmed) {

                            let assignId = result.value ? result.value.assign_id : null;

                            let requestData = {
                                ticket_id: ticketId,
                                status: newStatus,
                            };

                            if (assignId) {
                                requestData.assign_id = assignId;
                            }

                            $.ajax({
                                url: url,
                                type: 'POST',
                                data: requestData,
                                success: function(response) {
                                    if (response.success) {
                                        showToast(response.message, 'success');
                                    } else {
                                        showToast(response.message, 'error');
                                    }
                                    reloadDataTabale();
                                },
                                error: function(xhr) {
                                    let errorMessage = 'Something went wrong!';
                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        errorMessage = xhr.responseJSON.message;
                                    }
                                    showToast(errorMessage, 'error');
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
</x-master-layout>
