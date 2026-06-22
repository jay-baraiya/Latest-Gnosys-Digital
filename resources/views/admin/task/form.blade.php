<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : (isset($task) ? 'Edit' : 'Create') }}">
        <form id="taskForm"
            action="{{ isset($task) ? route('admin.tasks.update', encrypt($ticket->id)) : route('admin.tasks.store') }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @if (isset($task))
                @method('PUT')
            @endif

            @php
                if (empty($ticket_number)) {
                    $ticket_number = $ticket->ticket_number;
                }
            @endphp

            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label" for="ticket_number">Ticket Number <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input type="text" class="form-control" name="ticket_number" id="ticket_number" placeholder="Ticket Number"
                                value="{{ !empty($ticket_number) ? $ticket_number : ''  }}" readonly>
                        </div>
                        @error('ticket_number')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-9" id="dy-1">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label mb-0" id="main_label">Products <span class="text-danger">*</span></label>
                            <a href="javascript:void(0);" id="toggle_type" class="text-primary text-decoration-none small">Switch to Services</a>
                        </div>

                        <div id="product_section">
                            <div class="input-group mb-1">
                                <select class="form-select" name="product_id" id="product_id">
                                    <option value="">Select Product...</option>
                                    @if (!empty($products))
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" {{ old('product_id', $ticket->order_item_id ?? '') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            @error('product_id')
                                <span class="text-danger small product_error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div id="service_section" style="display: none;">
                            <div class="input-group mb-1">
                                <select class="form-select" name="service_id" id="service_id">
                                    <option value="">Select Service...</option>
                                    @if (!empty($services))
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}" {{ old('service_id', $ticket->order_item_id ?? '') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            @error('service_id')
                                <span class="text-danger small service_error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div id="service_variant_section" style="display: none; margin-top: 10px;">
                            <input type="hidden" name="is_variant" value="0">
                            <div class="input-group mb-1">
                                <select class="form-select" name="service_variant_id" id="service_variant_id">
                                    <option value="">Select Variant...</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="user_id">User <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="user_id" id="user_id">
                            <option value="">Select User</option>
                            @if (isset($users) && count($users) > 0)
                                @foreach ($users as $userItem)
                                    <option value="{{ $userItem->id }}"
                                        {{ old('user_id', $ticket->user_id ?? '') == $userItem->id ? 'selected' : '' }}>
                                        {{ $userItem->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('user_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="developer_id">Developer <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="developer_id" id="developer_id">
                            <option value="">Select Developer</option>
                            @if (isset($developers) && count($developers) > 0)
                                @foreach ($developers as $developer)
                                    <option value="{{ $developer->id }}"
                                        {{ old('developer_id', $ticket->developer_id ?? '') == $developer->id ? 'selected' : '' }}>
                                        {{ $developer->name . ' ( '. $developer?->designation?->name .' ) ' }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('developer_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
                        @php $currentStatus = old('status', $ticket->status ?? ''); @endphp
                        <select class="form-select select2" name="status" id="status">
                            <option value="">Select Status</option>
                            <option value="pending" {{ $currentStatus == 'pending' ? 'selected' : '' }}>Pending</option>
                            {{-- <option value="assign_requested" {{ $currentStatus == 'assign_requested' ? 'selected' : '' }}>Assign Requested</option> --}}
                            <option value="assigned" {{ $currentStatus == 'assigned' ? 'selected' : '' }}>Assigned</option>
                            {{-- <option value="assign_not_accepted" {{ $currentStatus == 'assign_not_accepted' ? 'selected' : '' }}>Assign Not Accepted</option> --}}
                            <option value="in_progress" {{ $currentStatus == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $currentStatus == 'completed' ? 'selected' : '' }}>Completed</option>
                            {{-- <option value="cancel_requested" {{ $currentStatus == 'cancel_requested' ? 'selected' : '' }}>Cancel Requested</option> --}}
                            <option value="cancelled" {{ $currentStatus == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="refund" {{ $currentStatus == 'refund' ? 'selected' : '' }}>Refund</option>
                        </select>
                        @error('status')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12" id="cancel_reason_section" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label" for="cancel_reason">Cancel Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="cancel_reason" id="cancel_reason" rows="3" placeholder="Cancel Reason">{{ old('cancel_reason', $ticket->cancel_reason ?? '') }}</textarea>
                        @error('cancel_reason')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-end mt-3">
                <a href="{{ route($moduleUrl ?? 'admin.tasks.index') }}" class="btn btn-soft-light">Cancel</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </x-form-wrapper>

    @push('scripts')
        <script>
            $(document).ready(function() {

                let currentType = "{{ old('service_id', $ticket->service_id ?? '') ? 'service' : 'product' }}";

                function applyTypeSelection() {
                    if (currentType === 'service') {
                        $('#main_label').html('Services <span class="text-danger">*</span>');
                        $('#toggle_type').text('Switch to Products');
                        $('#product_section').hide();
                        $('.product_error').hide();
                        $('#service_section').show();
                    } else {
                        $('#main_label').html('Products <span class="text-danger">*</span>');
                        $('#toggle_type').text('Switch to Services');
                        $('#service_section').hide();
                        $('.service_error').hide();
                        $('#product_section').show();
                    }
                }

                applyTypeSelection();

                $('#toggle_type').click(function() {
                    currentType = (currentType === 'product') ? 'service' : 'product';
                    applyTypeSelection();

                    if(currentType === 'product') {
                        $('#product_id').val('').trigger('change');
                        $('#service_id').val('').trigger('change');
                        $('input[name="is_variant"]').val(0);
                    } else {
                        $('#product_id').val('').trigger('change');
                        $('#service_id').val('').trigger('change');
                    }

                    $('#service_variant_section').hide();
                    $('#service_variant_id').html('<option value="">Select Variant...</option>');
                });

                function toggleCancelReason() {
                    let status = $('#status').val();
                    if (status === 'cancelled' || status === 'cancel_requested') {
                        $('#cancel_reason_section').slideDown();
                    } else {
                        $('#cancel_reason_section').slideUp();
                        // $('#cancel_reason').val('');
                    }
                }

                toggleCancelReason();

                $('#developer_id').select2({ placeholder: 'Select a developer', allowClear: true });
                $('#user_id').select2({ placeholder: 'Select a user', allowClear: true });
                $('#status').select2({ placeholder: 'Select a status', allowClear: true });

                $('#product_id').select2({ placeholder: 'Select a product', allowClear: true });
                $('#service_id').select2({ placeholder: 'Select a service', allowClear: true });

                $('#status').on('change', function() {
                    toggleCancelReason();
                    $(this).valid();
                });

                $('#user_id, #developer_id').on('change', function() {
                    $(this).valid();
                });

                $('#service_id').on('change', function() {
                    let serviceId = $(this).val();
                    let variantSelect = $('#service_variant_id');
                    let variantSection = $('#service_variant_section');

                    // Reset the variant dropdown
                    variantSelect.html('<option value="">Select Variant...</option>');

                    if (serviceId) {
                        // Show loading text while fetching
                        variantSelect.html('<option value="">Loading variants...</option>');
                        variantSection.show();

                        $.ajax({
                            // Ensure this script is inside a blade file for the route() helper to work
                            url: "{{ route('admin.tasks.get.service.variant') }}",
                            type: "POST",
                            data: {
                                service_id: serviceId
                            },
                            success: function(response) {
                                if (response.success === 1 && response.variants.length > 0) {
                                    $('input[name="is_variant"]').val(1);
                                    let options = '<option value="">Select Variant...</option>';

                                    // Loop through returned variants and append to options string
                                    $.each(response.variants, function(index, variant) {
                                        // Assuming your variants table has 'id' and 'name' columns.
                                        // Adjust 'variant.name' if your column is called something else (e.g., 'title')
                                        options += `<option value="${variant.id}">${variant.name}</option>`;
                                    });

                                    variantSelect.html(options);
                                } else {
                                    $('input[name="is_variant"]').val(0);
                                    variantSelect.html('<option value="">No variants found</option>');
                                }
                            },
                            error: function(xhr) {
                                console.error("An error occurred while fetching variants.");
                                variantSelect.html('<option value="">Error loading variants</option>');
                            }
                        });
                    } else {
                        variantSection.hide();
                    }
                });

                $('#taskForm').validate({
                    rules: {
                        ticket_number: {
                            required: true,
                            maxlength: 255
                        },
                        product_id: {
                            required: function(element) {
                                return currentType === 'product';
                            }
                        },
                        service_id: {
                            required: function(element) {
                                return currentType === 'service';
                            }
                        },
                        service_variant_id: {
                            required: function(element) {
                                return $('input[name="is_variant"]').val() ? true : false;
                            }
                        },
                        user_id: {
                            required: true
                        },
                        developer_id: {
                            required: true
                        },
                        status: {
                            required: true
                        },
                        cancel_reason: {
                            required: function(element) {
                                let st = $('#status').val();
                                return (st === 'cancelled' || st === 'cancel_requested');
                            }
                        }
                    },
                    messages: {
                        ticket_number: { required: "Please enter a ticket number." },
                        product_id: { required: "Please select a product." },
                        service_id: { required: "Please select a service." },
                        user_id: { required: "Please select a user." },
                        developer_id: { required: "Please select a developer." },
                        status: { required: "Please select a status." },
                        cancel_reason: { required: "Please provide a reason for cancellation." }
                    },
                    errorClass: 'text-danger small mt-1',
                    errorElement: 'span',
                    ignore: ":hidden:not(.select2-hidden-accessible)", // Select2 ને વેલિડેટ કરવા માટે
                    highlight: function(element) {
                        $(element).addClass('is-invalid');
                        if ($(element).hasClass("select2-hidden-accessible")) {
                            $(element).next('.select2-container').find('.select2-selection').addClass('border-danger');
                        }
                    },
                    unhighlight: function(element) {
                        $(element).removeClass('is-invalid');
                        if ($(element).hasClass("select2-hidden-accessible")) {
                            $(element).next('.select2-container').find('.select2-selection').removeClass('border-danger');
                        }
                    },
                    errorPlacement: function(error, element) {
                        if (element.hasClass('select2-hidden-accessible')) {
                            error.insertAfter(element.next('.select2-container'));
                        } else if (element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });

            });
        </script>
    @endpush

</x-master-layout>
