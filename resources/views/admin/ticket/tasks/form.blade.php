<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : (isset($task) ? 'Edit' : 'Create') }}">
        <div class="mb-3">
            <h5 class="mb-0 fs-16 fw-bold">Ticket <span class="text-primary">#{{ $ticket->ticket_number }}</span> - {{ isset($action) ? $action : (isset($task) ? 'Edit' : 'Create') }} Task</h5>
        </div>
        <form id="taskForm"
            action="{{ !empty($task) ? route('admin.tickets.tasks.update', ['id' => encrypt($ticket->id), 'taskId' => encrypt($task->id)]) : route('admin.tickets.tasks.store', encrypt($ticket->id)) }}" method="post"
            enctype="multipart/form-data">
            @csrf

            @php
                if (empty($task_number) && isset($task_number)) {
                    $task_number = $task->task_number;
                }

                $product_type = 'product';
                if (!empty($task->product_type) && $task->product_type == 'product') {
                    $product_type = 'product';
                } else if (!empty($task->product_type) && $task->product_type == 'service') {
                    $product_type = 'service';
                }
            @endphp

            <input type="hidden" name="product_type" value="{{ $product_type }}">

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input type="text" class="form-control" name="title" id="title" placeholder="Title"
                                value="{{ old('title', $task->title ?? '') }}">
                        </div>
                        @error('title')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6" id="dy-1">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label mb-0" id="main_label">Products <span class="text-danger">*</span></label>
                            <a href="javascript:void(0);" id="toggle_type" data-type="{{ $product_type }}" class="text-primary text-decoration-none small">Switch to Services</a>
                        </div>

                        <div id="product_section">
                            <div class="input-group mb-1">
                                <select class="form-select" name="product_id" id="product_id">
                                    <option value="">Select Product...</option>
                                    @if (!empty($products))
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" {{ old('product_id', $task->product_id ?? '') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
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
                                            <option value="{{ $service->id }}" {{ old('service_id', $task->product_id ?? '') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
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
                        <label class="form-label" for="task_number">Task Number <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input type="text" class="form-control" name="task_number" id="task_number" placeholder="Task Number"
                                value="{{ !empty($task_number) ? $task_number : ($task->task_number ?? '')  }}" readonly>
                        </div>
                        @error('task_number')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="user_id">Ticket No.</label>
                        <div class="input-group mb-1">
                            <input type="text" class="form-control bg-light" value="{{ $ticket->ticket_number }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Due Date:</label>
                        <div class="input-group mb-1">
                            <input name="due_date" type="date" class="form-control w-auto" value="{{ !empty($task?->due_date) ? \Carbon\Carbon::parse($task?->due_date)->format('Y-m-d') : now()->format('Y-m-d') }}">
                        </div>
                        @error('due_date')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="assign_id">Assign <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="assign_id" id="assign_id">
                            <option value="">Select User</option>
                            @if (isset($developers) && count($developers) > 0)
                                @foreach ($developers as $developer)
                                    <option value="{{ $developer->id }}"
                                        {{ old('assign_id', $task->assign_id ?? '') == $developer->id ? 'selected' : '' }}>
                                        {{ $developer->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('assign_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="department_id">Department <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="department_id" id="department_id">
                            <option value="">Select Department</option>
                            @if (isset($departments) && count($departments) > 0)
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ old('department_id', $task->department_id ?? '') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('department_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
                        @php $currentStatus = old('status', $task->status ?? ''); @endphp
                        <select class="form-select select2" name="status" id="status">
                            <option value="">Select Status</option>
                            <option value="pending" {{ $currentStatus == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="assigned" {{ $currentStatus == 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="in_progress" {{ $currentStatus == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $currentStatus == 'completed' ? 'selected' : '' }}>Completed</option>
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
                        <textarea class="form-control" name="cancel_reason" id="cancel_reason" rows="3" placeholder="Cancel Reason">{{ old('cancel_reason', $task->cancel_reason ?? '') }}</textarea>
                        @error('cancel_reason')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="description">Description <span class="text-danger">*</span></label>
                        <input type="hidden" name="description" id="description" value="{{ old('description', $task->description ?? '') }}">
                        <div id="quill-editor" style="height: 200px;">{!! old('description', $task->description ?? '') !!}</div>
                        @error('description')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>

            <hr>

            <div class="text-end mt-3">
                <a href="{{ route('admin.tickets.edit', encrypt($ticket->id) . '?tab=task-form') }}" class="btn btn-soft-light">Cancel</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </x-form-wrapper>

    @push('scripts')
        <script>
            $(document).ready(function() {

                let currentType = "{{ $product_type == 'service' ? 'service' : 'product' }}";
                let variantId = "{{ !empty($task->variant_id) ? $task->variant_id : '' }}";

                function applyTypeSelection() {
                    var t = $('#toggle_type').data('type');
                    console.log('t => ', t);

                    if (currentType === 'service') {
                        $('#main_label').html('Services <span class="text-danger">*</span>');
                        $('#toggle_type').text('Switch to Products');
                        $('#product_section').hide();
                        $('.product_error').hide();
                        $('#service_section').show();
                        $('#service_variant_section').show();
                        $('#service_variant_id').html('<option value="">Select Variant...</option>');
                    } else {
                        $('#main_label').html('Products <span class="text-danger">*</span>');
                        $('#toggle_type').text('Switch to Services');
                        $('#service_section').hide();
                        $('.service_error').hide();
                        $('#product_section').show();
                        $('#service_variant_section').hide();
                        $('#service_variant_id').html('<option value="">Select Variant...</option>');
                    }
                }

                applyTypeSelection();

                $('#toggle_type').click(function() {
                    var Type = $(this).data('type');

                    if (Type == 'service') {
                        $(this).data('type', 'product');
                        $('#main_label').html('Products <span class="text-danger">*</span>');
                        $('#toggle_type').text('Switch to Services');
                        $('#service_section').hide();
                        $('.service_error').hide();
                        $('#product_section').show();
                        $('#service_variant_section').show();
                        $('#service_variant_id').html('<option value="">Select Variant...</option>');
                        $('#service_id').val('').trigger('change');
                        $('#product_id').val('').trigger('change');
                    } else if (Type == 'product') {
                        $(this).data('type', 'service');
                        $('#main_label').html('Services <span class="text-danger">*</span>');
                        $('#toggle_type').text('Switch to Products');
                        $('#product_section').hide();
                        $('.product_error').hide();
                        $('#service_section').show();
                        $('#service_variant_section').hide();
                        $('#service_variant_id').html('<option value="">Select Variant...</option>');
                        $('#product_id').val('').trigger('change');
                        $('#service_id').val('').trigger('change');
                    }

                    $('input[name="product_type"]').val($(this).data('type'));
                });

                function toggleCancelReason() {
                    let status = $('#status').val();
                    if (status === 'cancelled' || status === 'cancel_requested') {
                        $('#cancel_reason_section').slideDown();
                    } else {
                        $('#cancel_reason_section').slideUp();
                    }
                }

                toggleCancelReason();

                $('#developer_id').select2({ placeholder: 'Select a developer', allowClear: true });
                $('#user_id').select2({ placeholder: 'Select a user', allowClear: true });
                $('#status').select2({ placeholder: 'Select a status', allowClear: true });
                $('#assign_id').select2({ placeholder: 'Select a user', allowClear: true });
                $('#department_id').select2({ placeholder: 'Select a department', allowClear: true });
                $('#product_id').select2({ placeholder: 'Select a product', allowClear: true });
                $('#service_id').select2({ placeholder: 'Select a service', allowClear: true });

                $('#status').on('change', function() {
                    toggleCancelReason();
                    $(this).valid();
                });

                $('#service_id').on('change', function() {
                    let serviceId = $(this).val();
                    let variantSelect = $('#service_variant_id');
                    let variantSection = $('#service_variant_section');

                    variantSelect.html('<option value="">Select Variant...</option>');

                    if (serviceId) {
                        variantSelect.html('<option value="">Loading variants...</option>');
                        variantSection.show();

                        $.ajax({
                            url: "{{ route('admin.tickets.get.service.variant') }}",
                            type: "POST",
                            data: {
                                service_id: serviceId,
                            },
                            success: function(response) {
                                if (response.success === 1 && response.variants.length > 0) {
                                    $('input[name="is_variant"]').val(1);
                                    let options = '<option value="">Select Variant...</option>';

                                    $.each(response.variants, function(index, variant) {
                                        var selected = variantId == variant.id ? 'selected' : '';
                                        options += `<option value="${variant.id}" ${selected} >${variant.name}</option>`;
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

                if (variantId) {
                    $('#service_id').trigger('change');
                } else {
                    $('input[name="is_variant"]').val(0);
                }

                var validator = $('#taskForm').validate({
                    rules: {
                        title: { required: true },
                        task_number: { required: true, maxlength: 255 },
                        product_id: {
                            required: function(element) {
                                return $('input[name="product_type"]').val() === 'product';
                            }
                        },
                        service_id: {
                            required: function(element) {
                                return $('input[name="product_type"]').val() === 'service';
                            }
                        },
                        service_variant_id: {
                            required: function(element) {
                                return $('input[name="is_variant"]').val() == 1 ? true : false;
                            }
                        },
                        assign_id: { required: true },
                        department_id: { required: true },
                        status: { required: true },
                        cancel_reason: {
                            required: function(element) {
                                let st = $('#status').val();
                                return (st === 'cancelled' || st === 'cancel_requested');
                            }
                        },
                        description: { required: true },
                    },
                    messages: {
                        title: { required: "Please enter a title." },
                        task_number: { required: "Please enter a ticket number." },
                        product_id: { required: "Please select a product." },
                        service_id: { required: "Please select a service." },
                        assign_id: { required: "Please select a user." },
                        department_id: { required: "Please select a department." },
                        status: { required: "Please select a status." },
                        cancel_reason: { required: "Please provide a reason for cancellation." },
                        description: { required: "Please provide a detailed description." },
                    },
                    errorClass: 'text-danger small mt-1',
                    errorElement: 'span',
                    ignore: ":hidden:not(.select2-hidden-accessible, #description)",
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
                        } else if (element.attr('id') === 'description') {
                            error.insertAfter('#quill-editor');
                        } else if (element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else if (element.prop('type') === 'radio') {
                            error.insertAfter(element.closest('.d-flex'));
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });

                Quill.register("modules/htmlEditButton", htmlEditButton);

                var toolbarOptions = [
                    [{ 'font': [] }],
                    [{ 'size': ['small', false, 'large', 'huge'] }],
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],

                    [{ 'script': 'sub'}, { 'script': 'super' }],

                    [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'align': [] }],
                    [{ 'direction': 'rtl' }],

                    ['link', 'image', 'video', 'formula'],
                    ['blockquote', 'code-block'],

                    ['clean']
                ];

                var quill = new Quill('#quill-editor', {
                    theme: 'snow',
                    placeholder: 'Detailed description...',
                    modules: {
                        toolbar: toolbarOptions,

                        htmlEditButton: {
                            debug: false,
                            msg: "Edit the HTML below. Clicking 'Save' will update the editor.",
                            okText: "Save",
                            cancelText: "Cancel",
                            buttonHTML: "&lt;&gt;",
                            buttonTitle: "Show HTML source",
                            syntax: false
                        }
                    }
                });

                quill.on('text-change', function() {
                    var html = quill.root.innerHTML;
                    if (quill.getText().trim().length === 0) {
                        html = '';
                    }
                    $('#description').val(html);

                    validator.element('#description');
                });

            });
        </script>
    @endpush

</x-master-layout>
