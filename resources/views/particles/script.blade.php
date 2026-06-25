<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>

<!-- Bootstrap Core JS -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

<!-- Simplebar JS -->
<script src="{{ asset('assets/plugins/simplebar/simplebar.min.js') }}"></script>

<!-- Datatable JS -->
<script src="{{ asset('assets/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/js/dataTables.bootstrap5.min.js') }}"></script>

<!-- Daterangepicker JS -->
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>

@if (request()->route()->getName() == 'dashboard')
    <!-- Apexchart JS -->
    <script src="{{ asset('assets/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexchart/chart-data.js') }}"></script>

    <!-- Custom Json Js -->
    <script src="{{ asset('assets/json/dashboard.js') }}"></script>
@endif

<!-- Sweet Alerts js -->
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<!-- Toastify JS -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<!-- Select2 JS -->
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

<!-- Quill Editor JS -->
<script src="{{ asset('assets/plugins/quill/quill.min.js') }}"></script>

<!-- Choices Js -->
<script src="{{ asset('assets/plugins/choices.js/public/assets/scripts/choices.min.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('assets/js/script.js') }}"></script>
<script src="{{ asset('assets/js/actions.js') }}"></script>

<script>
    function showToast(message, type = 'success') {
        let bgColor = {
            success: "var(--bs-success)",
            error: "var(--bs-danger)",
            warning: "var(--bs-warning)",
            info: "var(--bs-info)"
        };

        Toastify({
            text: message,
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            stopOnFocus: true,
            style: {
                background: bgColor[type] || bgColor.success,
            }
        }).showToast();
    }

    @if (Session::has('success'))
        showToast("{{ Session::get('success') }}", 'success');
    @endif

    @if (Session::has('error'))
        showToast("{{ Session::get('error') }}", 'error');
    @endif

    @if (Session::has('warning'))
        showToast("{{ Session::get('warning') }}", 'warning');
    @endif

    @if (Session::has('info'))
        showToast("{{ Session::get('info') }}", 'info');
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            showToast("{{ $error }}", 'error');
        @endforeach
    @endif

    function reloadDataTabale() {
        if ($('.dataTableReload').length > 0) {
            $('.dataTableReload').DataTable().ajax.reload(null, false);
        }
    }

    $(document).ready(function() {
        $(document).on('click', '#statusUpdate', function(e) {
            e.preventDefault();

            let url = $(this).attr('href');

            if (!url) return;

            Swal.fire({
                title: "Are you sure?",
                text: "You want to update status!",
                icon: "warning",
                showCancelButton: true,
                customClass: {
                    confirmButton: "btn btn-primary me-2 mt-2",
                    cancelButton: "btn btn-danger mt-2",
                },
                confirmButtonText: "Yes, update it!",
                buttonsStyling: false,
                showCloseButton: true,
            }).then(function(result) {

                if (result.isConfirmed) {

                    $.ajax({
                        url: url,
                        type: 'POST',
                        success: function(response) {

                            if (response.success) {
                                showToast(response.message, 'success');
                            } else {
                                showToast(response.message, 'error');
                            }

                            reloadDataTabale();
                        },
                        error: function(xhr) {
                            showToast('Something went wrong!', 'error');
                        }
                    });

                }
            });
        });

        $(document).on('click', '#delete_action', function(e) {
            e.preventDefault();

            let url = $(this).attr('href');

            if (!url) return;

            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete this record!",
                icon: "warning",
                showCancelButton: true,
                customClass: {
                    confirmButton: "btn btn-primary me-2 mt-2",
                    cancelButton: "btn btn-danger mt-2",
                },
                confirmButtonText: "Yes, delete it!",
                buttonsStyling: false,
                showCloseButton: true,
            }).then(function(result) {

                if (result.isConfirmed) {

                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        success: function(response) {

                            if (response.success) {
                                showToast(response.message, 'success');
                            } else {
                                showToast(response.message, 'error');
                            }

                            reloadDataTabale();
                        },
                        error: function(xhr) {
                            showToast('Something went wrong!', 'error');
                        }
                    });

                }
            });
        });

        $(document).on('click', '#restore_action', function(e) {
            e.preventDefault();

            let url = $(this).attr('href');

            if (!url) return;

            Swal.fire({
                title: "Are you sure?",
                text: "You want to restore this record!",
                icon: "warning",
                showCancelButton: true,
                customClass: {
                    confirmButton: "btn btn-primary me-2 mt-2",
                    cancelButton: "btn btn-danger mt-2",
                },
                confirmButtonText: "Yes, restore it!",
                buttonsStyling: false,
                showCloseButton: true,
            }).then(function(result) {

                if (result.isConfirmed) {

                    $.ajax({
                        url: url,
                        type: 'POST',
                        success: function(response) {

                            if (response.success) {
                                showToast(response.message, 'success');
                            } else {
                                showToast(response.message, 'error');
                            }

                            reloadDataTabale();
                        },
                        error: function(xhr) {
                            showToast('Something went wrong!', 'error');
                        }
                    });

                }
            });
        });

        $(document).on('click', '.wallet-action-btn', function(e) {
            e.preventDefault();

            let url = $(this).attr('href');
            let action = $(this).data('action');

            if (!url) return;

            let swalConfig = {
                title: "Are you sure?",
                icon: "question",
                showCancelButton: true,
                customClass: {
                    confirmButton: "btn btn-success me-2 mt-2",
                    cancelButton: "btn btn-secondary mt-2",
                },
                buttonsStyling: false,
                showCloseButton: true,
            };

            if (action === 'approve') {
                swalConfig.text = "You want to approve this wallet proof!";
                swalConfig.confirmButtonText = "Yes, approve it!";
            } else if (action === 'reject') {
                swalConfig.text = "Please provide a reason for rejecting this wallet proof:";
                swalConfig.confirmButtonText = "Yes, reject it!";
                swalConfig.input = 'textarea';
                swalConfig.inputPlaceholder = "Type your reason here...";
                swalConfig.inputValidator = (value) => {
                    if (!value) return 'You need to provide a reason!';
                };
            } else if (action === 'reapprove') {
                swalConfig.text = "Please provide a remark for reapproving this wallet proof:";
                swalConfig.confirmButtonText = "Yes, reapprove it!";
                swalConfig.input = 'textarea';
                swalConfig.inputPlaceholder = "Type your remarks here...";
                swalConfig.inputValidator = (value) => {
                    if (!value) return 'You need to provide a remark!';
                };
            }

            Swal.fire(swalConfig).then(function(result) {
                if (result.isConfirmed) {

                    let requestData = {};
                    if (action === 'reject' || action === 'reapprove') {
                        requestData.reason = result.value;
                    }

                    $.ajax({
                        url: url,
                        type: 'GET',
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
                            showToast('Something went wrong!', 'error');
                        }
                    });
                }
            });
        });

        $('a[data-bs-original-title="Refresh"]').on('click', function (e) {
            e.preventDefault();
            var value = $(this).attr('data-value');
            $('#is_deleted').val(value);
            $('.buyerRecode').attr('data-buyer-value', 0);
            reloadDataTabale();
        });

        $(document).on('click', '.deletedRecode', function(e) {
            e.preventDefault();
            var value = $(this).attr('data-value');
            $('#is_deleted').val(value);

            reloadDataTabale();
        });

        $(document).on('click', '.buyerRecode', function(e) {
            e.preventDefault();
            $(this).attr('data-buyer-value', 1);
            $('#is_deleted').val(0);
            reloadDataTabale();
        });

        $(document).on('show.bs.dropdown', '.dropdown', function() {
            if ($(this).closest('.modal').length) {
                return;
            }
            $(this).find('.dropdown-menu').appendTo('body');
        });

        $(document).on('hide.bs.dropdown', '.dropdown', function() {
            if ($(this).closest('.modal').length) {
                return;
            }
            var $menu = $('body > .dropdown-menu');
            $(this).append($menu);
        });

        $('#country_id').select2({
            placeholder: 'Select a country',
            allowClear: true,
            ajax: {
                url: "{{ route('admin.common.getCountries') }}",
                dataType: 'json',
                delay: 250,
                method: 'POST',
                data: function(params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function(data) {
                    $('#state_id').val(null).trigger('change');
                    $('#city_id').val(null).trigger('change');
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $('#state_id').select2({
            placeholder: 'Select a state',
            allowClear: true,
            ajax: {
                url: "{{ route('admin.common.getStates') }}",
                dataType: 'json',
                delay: 250,
                method: 'POST',
                data: function(params) {
                    return {
                        q: params.term,
                        country_id: $('#country_id').val()
                    };
                },
                processResults: function(data) {
                    $('#city_id').val(null).trigger('change');
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $('#city_id').select2({
            placeholder: 'Select a city',
            allowClear: true,
            ajax: {
                url: "{{ route('admin.common.getCities') }}",
                dataType: 'json',
                delay: 250,
                method: 'POST',
                data: function(params) {
                    return {
                        q: params.term,
                        state_id: $('#state_id').val()
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    });

    function setSelect2Value(selector, id, text) {
        return new Promise((resolve) => {
            if (!id || !text) {
                resolve();
                return;
            }

            let option = new Option(text, id, true, true);
            $(selector).append(option).trigger('change');

            resolve();
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Request desktop notification permission on load (so it works when they change tabs)
        if ("Notification" in window && Notification.permission !== "granted" && Notification.permission !==
            "denied") {
            Notification.requestPermission();
        }

        const initEcho = () => {
            if (window.Echo) {
                let userId = {{ auth()->user()->id ?? 'null' }};
                if (userId) {
                    window.Echo.private(`App.Models.User.${userId}`)
                        .notification((notification) => {
                            console.log('New Notification:', notification.message);

                            if (document.hidden && "Notification" in window && Notification
                                .permission === "granted") {
                                // If tab is hidden, show a Native Browser/Desktop Notification
                                // new Notification("New Notification", {
                                //     body: notification.message,
                                // });
                            } else {
                                // If they are currently on this tab, show the non-blocking Toast message
                                if (typeof showToast === 'function') {
                                    // showToast(notification.message, 'info');
                                } else {
                                    // alert(notification.message);
                                }
                            }
                        });
                }
            } else {
                setTimeout(initEcho, 100);
            }
        };
        // initEcho();
    });

    $(document).ready(function(){
        if ($('#category_id').length > 0) {
            $('#category_id').select2({
                placeholder: 'Select a category',
                allowClear: true,
                ajax: {
                    url: "{{ route('admin.ajax.categories') }}",
                    type: "POST",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    id: item.id,
                                    text: item.name
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
        }

        $(document).on('click', '.clearFilter', function() {
            if ($('#category_id').length > 0) {
                $('#category_id').val(null).trigger('change');
            }

            reloadDataTabale();
        });

        $(document).on('change', '#category_id', function() {
            reloadDataTabale();
        });

    });
</script>
