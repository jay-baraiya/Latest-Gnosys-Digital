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
                @can('create.'.strtolower($moduleName))
                <a href="#" class="btn btn-primary addBalanceInWallet">
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

    <div id="addBalanceModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addBalance-modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="walletAddBalanceForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="addBalance-modalLabel">add Balance</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12 mb-2">
                                <label for="buyer_id" class="form-label">Buyers <span class="text-danger">*</span> </label>
                                <select name="buyer_id" id="buyer_id" class="form-control">
                                    <option value="">Select Buyer</option>
                                    @if (isset($buyers))
                                    @foreach ($buyers as $key => $buyer)
                                    <option value="{{ $key }}">{{ $buyer }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-lg-12">
                                <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                                <input type="text" name="amount" class="form-control" id="amount" placeholder="$0.00">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Standard modal content -->
    <div id="walletModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="wallet-modalLabel" aria-hidden="true">
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

    @push('scripts')
    <script>
        $(document).ready(function() {

            $(document).on('click', '.addBalanceInWallet', function() {
                let form = $('#walletAddBalanceForm');
                form[0].reset();
                form.validate().resetForm();
                form.find('.is-invalid').removeClass('is-invalid');

                $('#addBalanceModal').modal('show');
            });

            $(document).on('click', '.showWalletHistory', function(e) {
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

                        beforeSend: function() {
                            $('.historyData').html('Loading...');
                        },

                        success: function(response) {

                            $('.historyData').html('');
                            $('.historyData').html(response.html);

                            modal.modal('show');
                        },

                        error: function(xhr) {
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

                $('#dataTable-search').on('keyup', function() {
                    clearTimeout(timeout);
                    let value = this.value;

                    timeout = setTimeout(function() {
                        table.search(value).draw();
                    }, 500);
                });
            }

            $('#walletAddBalanceForm').validate({
                rules: {
                    buyer_id: {
                        required: true
                    },
                    amount: {
                        required: true,
                        number: true,
                        min: 1,
                        max: 50000
                    }
                },
                messages: {
                    buyer_id: "Please select a buyer.",
                    amount: {
                        required: "Please enter wallet amount.",
                        number: "Amount must be a valid number.",
                        min: "Minimum amount is $1.",
                        max: "Maximum amount is $50,000."
                    }
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback text-danger');
                    element.closest('.col-lg-12').append(error);
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form, event) {
                    event.preventDefault(); // Stop default form submit

                    let formData = $(form).serialize();
                    let submitBtn = $(form).find('button[type="submit"]');

                    $.ajax({
                        url: "{{ route('admin.wallets.store') }}", // Ensure this route is correct
                        type: "POST",
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Laravel CSRF token
                        },
                        beforeSend: function() {
                            submitBtn.prop('disabled', true).text('Processing...');
                        },
                        success: function(response) {
                            submitBtn.prop('disabled', false).text('Save changes');

                            if (response.success) {
                                $('#addBalanceModal').modal('hide');
                                showToast(response.message, 'success');
                                reloadDataTabale();
                            }
                        },
                        error: function(xhr) {
                            submitBtn.prop('disabled', false).text('Save changes');

                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                let errorMessage = '';
                                $.each(errors, function(key, value) {
                                    errorMessage += value[0] + '\n';
                                    showToast(errorMessage, 'error');
                                });
                                reloadDataTabale();
                                // alert('Validation Error:\n' + errorMessage);
                            } else {
                                // Handle 500 Server Errors
                                showToast('Something went wrong! Please check server logs.', 'error');
                                // alert('Something went wrong! Please check server logs.');
                            }
                        }
                    });
                }
            });
        });
    </script>
    @endpush
</x-master-layout>