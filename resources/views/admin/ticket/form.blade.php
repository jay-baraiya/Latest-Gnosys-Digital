<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : (isset($ticket) ? 'Edit' : 'Create') }}">
        <form id="taskForm"
            action="{{ isset($ticket) ? route('admin.tickets.update', encrypt($ticket->id)) : route('admin.tickets.store') }}"
            method="post" enctype="multipart/form-data">
            @csrf
            @if (isset($ticket))
                @method('PUT')
            @endif

            <div class="row">

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="user_id">Client <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="user_id" id="user_id">
                            <option value="">Select Client...</option>
                            @if (isset($users) && count($users) > 0)
                                @foreach ($users as $userItem)
                                    <option value="{{ $userItem->id }}" data-email="{{ $userItem->email }}"
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

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="name">Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Name"
                            value="{{ old('name', $ticket->name ?? '') }}">
                        @error('name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="email">Email Address <span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="email" class="form-control" name="email" id="email"
                                placeholder="Email Address" value="{{ old('email', $ticket->email ?? '') }}">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="checkbox" name="send_email" id="send_email"
                                    value="1" {{ old('send_email', $ticket->send_email ?? 1) ? 'checked' : '' }}>
                                <label class="form-check-label ms-2" for="send_email">Send Email</label>
                            </div>
                        </div>
                        @error('email')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="cc_recipients">CC Recipients</label>
                        <select class="select2 form-control select2-multiple" name="cc_recipients[]" data-toggle="select2"
                            multiple="multiple" id="cc_recipients"
                            data-placeholder="Start Typing to Add or Select Recipient ...">
                            @if ($cc_recipients->isNotEmpty())

                                @php
                                    $ccJdecode = !empty($ticket->cc_recipients) ? json_decode($ticket->cc_recipients, true) : [];
                                @endphp

                                @foreach ($cc_recipients as $cc_recipient)
                                    @if (in_array($cc_recipient->email, $ccJdecode))
                                        <option value="{{ $cc_recipient->email }}" selected>{{ $cc_recipient->email }}</option>
                                    @else
                                        <option value="{{ $cc_recipient->email }}">{{ $cc_recipient->email }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                        @error('cc_recipients')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="subject">Subject <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject"
                            value="{{ old('subject', $ticket->subject ?? '') }}">
                        @error('subject')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="department_id">Department <span class="text-danger">*</span></label>
                        <select class="form-select" name="department_id" id="department_id">
                            <option value="">Select Department...</option>
                            @if ($departments->isNotEmpty())
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id', $ticket->department_id ?? '') == $department->id ? 'selected' : '' }}>
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
                        <label class="form-label" for="priority">Priority <span class="text-danger">*</span></label>
                        <select class="form-select" name="priority" id="priority">
                            <option value="High"
                                {{ old('priority', $ticket->priority ?? '') == 'High' ? 'selected' : '' }}>High</option>
                            <option value="Medium"
                                {{ old('priority', $ticket->priority ?? '') == 'Medium' ? 'selected' : '' }}>Medium
                            </option>
                            <option value="Low"
                                {{ old('priority', $ticket->priority ?? 'Low') == 'Low' ? 'selected' : '' }}>Low</option>
                        </select>
                        @error('priority')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label" for="assign_id">Assign <span class="text-danger">*</span></label>
                        <select class="form-select" name="assign_id" id="assign_id">
                            <option value="">Select User...</option>
                            @if ($developers->isNotEmpty())
                                @foreach ($developers as $developer)
                                    <option value="{{ $developer->id }}" {{ old('assign_id', $ticket->assign_id ?? '') == $developer->id ? 'selected' : '' }}>
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

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="description">Full Description <span
                                class="text-danger">*</span></label>

                        <input type="hidden" name="description" id="description"
                            value="{{ old('description', $ticket->description ?? '') }}">

                        <div id="quill-editor" style="height: 200px;">{!! old('description', $ticket->description ?? '') !!}</div>

                        @error('description')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Note</label>
                        <textarea name="note" id="note" rows="3" class="form-control">{{ old('note', $ticket->note ?? '') }}</textarea>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Attachments</label>

                        <div class="dropzone border-dashed" id="ticketDropzone" style="border: 2px dashed #ced4da; border-radius: 5px; background: #f8f9fa;">
                            <div class="dz-message needsclick">
                                <i class="bx bx-cloud-upload fs-1 text-muted"></i>
                                <h5 class="mt-2">Drop files here or click to upload.</h5>
                            </div>
                        </div>

                        <input type="file" name="attachments[]" id="hiddenFileInput" multiple style="display: none;">

                        <div id="existing-files-container">
                            @if(isset($ticket) && !empty($ticket->attachments))
                                @php
                                    $existingFiles = json_decode($ticket->attachments, true);
                                @endphp
                                @if(is_array($existingFiles))
                                    @foreach($existingFiles as $filePath)
                                        <input type="hidden" name="existing_attachments[]" value="{{ $filePath }}">
                                    @endforeach
                                @endif
                            @endif
                        </div>

                    </div>
                </div>

            </div>

            <hr>

            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="text-primary mb-0">Task List</h5>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addItemBtn">
                    <i class="ti ti-plus me-1"></i> Add More
                </button>
            </div>

            {{-- Table header --}}
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="orderItemsTable">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width:280px;">Product/Service <span class="text-danger">*</span></th>
                            <th style="min-width:200px;">Variant</th>
                            <th style="min-width:200px;">Due Date</th>
                            <th style="min-width:110px;">Qty <span class="text-danger">*</span></th>
                            <th style="min-width:130px;">Price <span class="text-danger">*</span></th>
                            <th style="min-width:130px;">Total Amount</th>
                            <th style="min-width:60px;" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="orderItemsBody">

                        {{-- Edit mode: pre-fill existing items from DB --}}
                        @if (isset($tasks) && $tasks->count() > 0)
                        @foreach ($tasks as $item)
                        @php
                        $isProduct = $item->product_type === 'product' || empty($item->product_type);
                        $rowTotal = ($item->product_qty ?? 1) * ($item->product_price ?? 0);
                        @endphp
                        <tr class="order-item-row">
                            <td>
                                <input type="hidden" name="task_id[]" value="{{ $item->id }}">
                                <input type="hidden" name="product_type[]" class="item-type-hidden" value="{{ $isProduct ? 'product' : 'service' }}">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge {{ $isProduct ? 'bg-primary' : 'bg-success' }} me-2 item-type-badge pointer" style="cursor:pointer;" title="Click to toggle type">
                                        {{ $isProduct ? 'Product' : 'Service' }}
                                    </span>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size:10px;">Click to switch</small>
                                </div>
                                <select class="form-select product-select" name="product_id[]" required>
                                    <option value="">-- Select {{ $isProduct ? 'Product' : 'Service' }} --</option>
                                    @if ($isProduct)
                                    @foreach ($products ?? [] as $product)
                                    <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }} data-price="{{ $product->price }}">
                                        {{ $product->name }}
                                    </option>
                                    @endforeach
                                    @else
                                    @foreach ($services ?? [] as $service)
                                    <option value="{{ $service->id }}" {{ $item->product_id == $service->id ? 'selected' : '' }} data-price="{{ $service->price }}">
                                        {{ $service->name }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </td>
                            <td>
                                @php
                                $variants = [];
                                $hasVariants = false;
                                if (!$isProduct) {
                                $selectedService = $services->firstWhere('id', $item->product_id);
                                if ($selectedService) {
                                $variants = $selectedService->variants;
                                $hasVariants = $variants->count() > 0;
                                }
                                }
                                @endphp
                                <select class="form-select variant-select {{ $hasVariants ? 'variant-select-required' : '' }}" name="variant_id[]" {{ $hasVariants ? '' : 'readonly' }}>
                                    <option value="">{{ $hasVariants ? '-- Select Variant --' : '-- No Variant --' }}</option>
                                    @foreach ($variants as $variant)
                                    <option value="{{ $variant->id }}" {{ $item->variant_id == $variant->id ? 'selected' : '' }} data-price="{{ $variant->price }}">
                                        {{ $variant->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="date"
                                class="form-control due-date"
                                name="due_date[]"
                                value="{{ !empty($item->due_date) ? \Carbon\Carbon::parse($item->due_date)->format('Y-m-d') : now()->format('Y-m-d') }}"
                                required>
                            </td>
                            <td>
                                <input type="number" class="form-control item-qty" name="quantity[]"
                                    min="1" value="{{ $item->quantity ?? 1 }}" placeholder="1" required>
                            </td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control item-price" name="price[]"
                                        min="0" value="{{ $item->price ?? '' }}" placeholder="0.00" required>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control item-total" readonly value="{{ number_format($rowTotal, 2, '.', '') }}" placeholder="0.00">
                                </div>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn" title="Remove row">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        {{-- Default empty row for create mode --}}
                        <tr class="order-item-row">
                            <td>
                                <input type="hidden" name="task_id[]" value="">
                                <input type="hidden" name="product_type[]" class="item-type-hidden" value="product">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-primary me-2 item-type-badge pointer" style="cursor:pointer;" title="Click to toggle type">Product</span>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size:10px;">Click to switch</small>
                                </div>
                                <select class="form-select product-select" name="product_id[]" required>
                                    <option value="">-- Select Product --</option>
                                    @foreach ($products ?? [] as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select class="form-select variant-select" name="variant_id[]" readonly>
                                    <option value="">-- No Variant --</option>
                                </select>
                            </td>
                            <td>
                                <input type="date" class="form-control due-date" name="due_date[]" required>
                            </td>
                            <td>
                                <input type="number" class="form-control item-qty" name="quantity[]"
                                    min="1" value="1" placeholder="1" required>
                            </td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control item-price" name="price[]"
                                        min="0" value="" placeholder="0.00" required>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control item-total" readonly value="" placeholder="0.00">
                                </div>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn" title="Remove row">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endif

                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Grand Total:</td>
                            <td colspan="2" class="fw-bold text-primary" style="font-size: 16px;">
                                <span id="grandTotalDisplay">$0.00</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="text-end mt-3">
                <a href="{{ route($moduleUrl ?? 'admin.tasks.index') }}" class="btn btn-soft-light">Cancel</a>
                <button type="submit" class="btn btn-primary">Generate Ticket</button>
            </div>
        </form>
    </x-form-wrapper>

    @include('admin.ticket.ticket-script')

    @push('scripts')
        <script>
            Dropzone.autoDiscover = false;
            $(document).ready(function() {

                $('#user_id').select2({
                    placeholder: 'Select a user',
                    allowClear: true,
                });

                $('#priority').select2({
                    placeholder: 'Select a priority',
                    allowClear: true,
                });

                $('#department_id').select2({
                    placeholder: 'Select a priority',
                    allowClear: true,
                });

                $('#assign_id').select2({
                    placeholder: 'Select a user',
                    allowClear: true,
                });

                $(document).on('change', '#user_id', function() {
                    var text = $(this).find('option:selected').text();
                    var email = $(this).find('option:selected').data('email');

                    if (text && email) {
                        $('#name').val(text.trim());
                        $('#email').val(email.trim());
                    }
                });

                var myDropzone = new Dropzone("#ticketDropzone", {
                    url: "#",
                    autoProcessQueue: false,
                    uploadMultiple: true,
                    maxFiles: 10,
                    maxFilesize: 10,
                    acceptedFiles: "image/jpeg,image/png,image/jpg,.pdf,.doc,.docx",
                    addRemoveLinks: true,
                    init: function() {
                        var dz = this;

                        var existingFilesData = {!! isset($ticket) && !empty($ticket->attachments) ? $ticket->attachments : '[]' !!};

                        if (typeof existingFilesData === 'string') {
                            existingFilesData = JSON.parse(existingFilesData);
                        }

                        existingFilesData.forEach(function(filePath) {
                            var fileName = filePath.split('/').pop();

                            var mockFile = {
                                name: fileName,
                                size: 1024,
                                status: Dropzone.ADDED,
                                accepted: true,
                                serverPath: filePath
                            };

                            dz.emit("addedfile", mockFile);

                            if (filePath.match(/\.(jpeg|jpg|png|gif|webp)$/i)) {
                                dz.emit("thumbnail", mockFile, filePath);
                            }

                            dz.emit("complete", mockFile);
                            dz.files.push(mockFile);
                        });

                        function syncFilesToInput() {
                            var dataTransfer = new DataTransfer();
                            dz.files.forEach(function(file) {
                                if (!file.serverPath && file.status !== Dropzone.CANCELED && file.status !== Dropzone.ERROR) {
                                    dataTransfer.items.add(file);
                                }
                            });
                            document.getElementById('hiddenFileInput').files = dataTransfer.files;
                        }

                        this.on("addedfile", function(file) {
                            if(!file.serverPath) {
                                syncFilesToInput();
                            }
                        });

                        this.on("removedfile", function(file) {
                            if (file.serverPath) {
                                $('input[name="existing_attachments[]"][value="' + file.serverPath + '"]').remove();
                            } else {
                                syncFilesToInput();
                            }
                        });
                    }
                });

                var validator = $('#taskForm').validate({
                    ignore: ":hidden:not(.select2-hidden-accessible, #description)",
                    rules: {
                        user_id: { required: true },
                        email: { required: true, email: true },
                        subject: { required: true, maxlength: 255 },
                        department_id: { required: true },
                        assign_id: { required: true },
                        priority: { required: true },
                        description: { required: true }
                    },
                    messages: {
                        user_id: { required: "Please select a client." },
                        email: {
                            required: "Please enter an email address.",
                            email: "Please enter a valid email address."
                        },
                        subject: { required: "Please enter a subject." },
                        department_id: { required: "Please select a department." },
                        assign_id: { required: "Please select a assign." },
                        priority: { required: "Please select a priority." },
                        description: { required: "Please enter the ticket description." }
                    },
                    errorClass: 'text-danger small mt-1',
                    errorElement: 'span',
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
                        } else if (element.closest('.input-group').length) {
                            error.insertAfter(element.closest('.input-group'));
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    submitHandler: function(form) {
                        var quillHtml = document.querySelector('#quill-editor .ql-editor').innerHTML;
                        if (quill.getText().trim().length === 0) {
                            quillHtml = '';
                        }
                        $('#description').val(quillHtml);

                        form.submit();
                    }
                });

                Quill.register("modules/htmlEditButton", htmlEditButton);

                var toolbarOptions = [
                    [{ 'font': [] }],
                    [{ 'size': ['small', false, 'large', 'huge'] }],
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'script': 'sub' }, { 'script': 'super' }],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'list': 'check' }],
                    [{ 'indent': '-1' }, { 'indent': '+1' }],
                    [{ 'align': [] }],
                    [{ 'direction': 'rtl' }],
                    ['link', 'image', 'video', 'formula'],
                    ['blockquote', 'code-block'],
                    ['clean']
                ];

                var quill = new Quill('#quill-editor', {
                    theme: 'snow',
                    placeholder: 'Detailed service description...',
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
