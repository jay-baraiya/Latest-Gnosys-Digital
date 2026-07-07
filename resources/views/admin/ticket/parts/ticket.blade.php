<form action="{{ $url }}" method="POST" enctype="multipart/form-data" id="ticketForm">

    @csrf
    @if (isset($ticket))
    @method('PUT')
    @endif

    @php
    $disabled = request()->route()->getName() == 'admin.tickets.show' ? 'disabled' : '';
    @endphp

    <input type="hidden" name="tab" value="ticket-form">

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 bg-light p-2 border-bottom">
        <h5 class="mb-0 fs-16 fw-bold d-inline-flex items-center">
            <span class="line-title d-block me-2"></span>
            User and Collaborators:
        </h5>
    </div>

    <div class="row mb-3 align-items-center">
        <label class="col-md-2 col-form-label">User: <span class="text-danger">*</span> </label>
        <div class="col-md-6 d-flex align-items-center gap-2">
            <select name="user_id" id="buyer-select" class="user-id select2 form-select"
                data-placeholder="-- Select user --">
                <option value=""></option>
                @if (isset($users))
                @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ $user->id == $ticket?->user_id ? 'selected' : '' }}>{{ $user->name . ' - ' . $user->email }}</option>
                @endforeach
                @endif
            </select>
            <button type="button" class="btn btn-light border text-nowrap" id="addNewUserBtn">
                <strong>+</strong> Add New
            </button>
        </div>
    </div>

    <div class="row mb-3 align-items-center">
        <label class="col-md-2 col-form-label">Cc: <span class="text-danger">*</span> </label>
        <div class="col-md-6 d-flex align-items-center gap-2">
            <select class="select2 form-control select2-multiple" name="cc_recipients[]" data-toggle="select2"
                multiple="multiple" id="cc_recipients" data-placeholder="Start Typing to Add or Select Recipient ...">
                @if ($cc_recipients->isNotEmpty())

                @php
                $ccJdecode = !empty($ticket?->cc_recipients) ? json_decode($ticket?->cc_recipients, true) : [];
                @endphp

                @foreach ($cc_recipients as $cc_recipient)
                @if (in_array($cc_recipient->email, $ccJdecode))
                <option value="{{ $cc_recipient->email }}" selected>
                    {{ $cc_recipient->email }}
                </option>
                @else
                <option value="{{ $cc_recipient->email }}">{{ $cc_recipient->email }}
                </option>
                @endif
                @endforeach
                @endif
            </select>

        </div>
    </div>

    <div class="row mb-4 align-items-center">
        <label class="col-md-2 col-form-label">Ticket Notice:</label>
        <div class="col-md-4">
            <select class="form-select ticket-notice addSelect2 w-auto" name="ticket_notice" id="ticket_notice">
                <option value="alert_all" {{ $ticket?->ticket_notice == 'alert_all' ? 'selected' : '' }}>Alert All</option>
                <option value="alert_to_user" {{ $ticket?->ticket_notice == 'alert_to_user' ? 'selected' : '' }}>Alert To User</option>
                <option value="do_not_send_alert" {{ $ticket?->ticket_notice == 'do_not_send_alert' ? 'selected' : '' }}> -- Do Not Send Alert -- </option>
            </select>
        </div>
    </div>

    <div
        class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 bg-light p-2 border-bottom border-top">
        <h5 class="mb-0 fs-16 fw-bold d-inline-flex items-center">
            <span class="line-title d-block me-2"></span>
            Ticket Information and Options:
        </h5>
    </div>

    <div class="row mb-3 align-items-center">
        <label class="col-md-2 col-form-label">Ticket Source: <span class="text-danger">*</span> </label>
        <div class="col-md-4 d-flex align-items-center gap-2">
            <select class="form-select addSelect2 w-auto" name="ticket_source" id="ticket-source">
                <option value="phone" {{ $ticket?->ticket_source == 'phone' ? 'selected' : '' }}>Phone</option>
                <option value="email" {{ $ticket?->ticket_source == 'email' ? 'selected' : '' }}>Email</option>
                <option value="other" {{ $ticket?->ticket_source == 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>
    </div>

    <div class="row mb-3 align-items-center">
        <label class="col-md-2 col-form-label">Help Topic: <span class="text-danger">*</span> </label>
        <div class="col-md-4 d-flex align-items-center gap-2">
            <select class="form-select addSelect2" name="help_topic">
                <option value="">— Select Help Topic —</option>
                @php
                $help_topic_array = [
                'account-billing',
                'custom-app-support',
                'custom-app-support-bug-report',
                'custom-app-support-feature-request',
                'custom-app-support-performance',
                'erp-support',
                'feedback',
                'general-inquiry',
                'general-technical-support',
                'report-a-problem',
                'report-a-problem-access-issue',
                'wordpress-support',
                'wordpress-support-malware-hacked-site',
                'wordpress-support-plugin-conflict-error',
                'wordpress-support-site-down-critical-error',
                'wordpress-support-theme-issue'
                ];
                @endphp

                @foreach ($help_topic_array as $help_topic)
                <option value="{{ $help_topic }}" {{ $ticket?->help_topic == $help_topic ? 'selected' : '' }}>{{ $help_topic }}</option>
                @endforeach

            </select>
        </div>
    </div>

    <div class="row mb-3 align-items-center">
        <label class="col-md-2 col-form-label">Department:</label>
        <div class="col-md-4">
            <select class="form-select addSelect2 w-auto" name="department_id"
                data-placeholder="-- Select Department --">
                <option value="">— Select Department —</option>
                @if ($departments->isNotEmpty())
                @foreach ($departments as $department)
                <option value="{{ $department->id }}" {{ $ticket?->department_id == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="row mb-3 align-items-center">
        <label class="col-md-2 col-form-label">SLA Plan:</label>
        <div class="col-md-4">
            <select class="form-select addSelect2 w-auto" name="sla_plan" data-placeholder="-- System Default --">
                <option value="" selected>— System Default —</option>
                @php
                $sla_plan_array = [
                'critical-1-hours-active',
                'high-priority-4-hours-active',
                'low-priority-48-hours-active',
                'standard-24-hours-active'
                ];
                @endphp

                @foreach ($sla_plan_array as $sla_plan)
                <option value="{{ $sla_plan }}" {{ $ticket?->sla_plan == $sla_plan ? 'selected' : '' }}>{{ $sla_plan }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row mb-3 align-items-center">
        <label class="col-md-2 col-form-label">Due Date:</label>
        <div class="col-md-10 d-flex align-items-center gap-2 flex-wrap">
            <input name="due_date" type="date" class="form-control w-auto" value="{{ !empty($ticket?->due_date) ? \Carbon\Carbon::parse($ticket?->due_date)->format('Y-m-d') : now()->format('Y-m-d') }}">
        </div>
    </div>

    <div class="row mb-3 align-items-center">
        <label class="col-md-2 col-form-label">Assign To:</label>
        <div class="col-md-4">
            <select name="assign_id" id="assign-to-select" class="form-select w-auto">
                <option value="">— Select an Agent OR a Team —</option>
                @if (isset($developers))
                @foreach ($developers as $developer)
                <option value="{{ $developer->id }}" {{ $developer->id == $ticket?->assign_id ? 'selected' : '' }}>{{ $developer->name . ' - ' . $developer?->role?->name }}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="row mb-3 align-items-center">
        <label class="col-md-2 col-form-label" for="priority">Priority <span class="text-danger">*</span></label>
        <div class="col-md-4">
            <select class="form-select" name="priority" id="priority">
                <option value="High"
                    {{ old('priority', $ticket?->priority ?? '') == 'High' ? 'selected' : '' }}>
                    High
                </option>
                <option value="Medium"
                    {{ old('priority', $ticket?->priority ?? '') == 'Medium' ? 'selected' : '' }}>
                    Medium
                </option>
                <option value="Low"
                    {{ old('priority', $ticket?->priority ?? 'Low') == 'Low' ? 'selected' : '' }}>
                    Low
                </option>
            </select>
            @error('priority')
            <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div
        class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 bg-light p-2 border-bottom border-top">
        <h5 class="mb-0 fs-16 fw-bold d-inline-flex items-center">
            <span class="line-title d-block me-2"></span>
            Response: <span class="fw-normal ms-1">Optional response to the above issue</span>
        </h5>
    </div>

    <div class="row mb-3 align-items-center">
        <label class="col-md-2 col-form-label fw-bold">Canned Response:</label>
        <div class="col-md-6 d-flex align-items-center gap-3">
            <select class="form-select addSelect2 w-auto" data-placeholder="-- Select a canned response --"
                name="canned_response">
                <option value="">-- Select a canned response --</option>
                <option value="sample" {{ $ticket?->canned_response == 'sample' ? 'selected' : '' }}>Sample (with variables)</option>
                <option value="os_ticket" {{ $ticket?->canned_response == 'os_ticket' ? 'selected' : '' }}>What is osTicket (sample)?</option>
            </select>
            <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" id="appendCheckbox" checked>
                <label class="form-check-label fw-bold" for="appendCheckbox">
                    Append
                </label>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label" for="ticket-description-input">Description <span class="text-danger">*</span></label>

                <input type="hidden" name="description" id="ticket-description-input"
                    value="{{ old('description', $ticket?->description ?? '') }}">

                <div class="ticket-description-editor" id="ticket-description-editor" style="height: 200px;">{!! old('description', $ticket?->description ?? '') !!}</div>

                @error('description')
                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    {{-- <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="p-3 text-start border rounded bg-light" style="border-style: dashed !important; border-color: #ccc !important;">
                            <span class="text-muted">
                                &#8593; Drop files here or <a href="#" class="text-primary text-decoration-none">choose them</a>
                            </span>
                        </div>
                    </div>
                </div> --}}

    <div class="row mb-3 align-items-center">
        <label class="col-md-2 col-form-label">Ticket Status:</label>
        <div class="col-md-4">
            <select class="form-select addSelect2 w-auto" data-placeholder="-- Select a status -- "
                name="ticket_status" id="ticket-status">
                <option value=""></option>
                <option value="open" {{ $ticket?->ticket_status == 'open' ? 'selected' : '' }}>Open</option>
                <option value="resolved" {{ $ticket?->ticket_status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="closed" {{ $ticket?->ticket_status == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>
    </div>

    <div class="row mb-4 align-items-center">
        <label class="col-md-2 col-form-label">Signature:</label>
        <div class="col-md-10 d-flex align-items-center gap-3">
            <div class="form-check mb-0">
                <input class="form-check-input" type="radio" name="signature_option" id="sigNone" checked>
                <label class="form-check-label" for="sigNone">None</label>
            </div>
            <div class="form-check mb-0">
                <input class="form-check-input" type="radio" name="signature_option" id="sigDept">
                <label class="form-check-label" for="sigDept">Department Signature (if set)</label>
            </div>
        </div>
    </div>

    <div
        class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 bg-light p-2 border-bottom border-top">
        <h5 class="mb-0 fs-16 fw-bold d-inline-flex items-center">
            <span class="line-title d-block me-2"></span>
            Internal Note:
        </h5>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="col-md-12">
                <div class="mb-3">
                    <input type="hidden" name="internal_note" id="ticket-internal-note-input"
                        value="{{ old('internal_note', $ticket?->internal_note ?? '') }}">

                    <div class="ticket-internal-note-editor" id="ticket-internal-note-editor" style="height: 200px;">{!! old('internal_note', $ticket?->internal_note ?? '') !!}
                    </div>

                    @error('description')
                    <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mt-3">
        <a href="{{ route($moduleUrl ?? 'admin.tasks.index') }}" class="btn btn-soft-light">Cancel</a>
        <button type="submit" class="btn btn-primary">Generate Ticket</button>
    </div>
</form>

<!-- Add User Modal -->
<div id="addUserModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="ajaxAddUserForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addUserModalLabel">Add New User</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label for="modal_user_name" class="form-label">User Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" id="modal_user_name" placeholder="Enter Name" required>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="modal_user_email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" id="modal_user_email" placeholder="Enter Email" required>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="modal_user_role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role_id" id="modal_user_role" class="form-select" required style="width: 100%;">
                                <option value="">-- Select Role --</option>
                                @if(isset($roles))
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="modal_user_department" class="form-label">Department</label>
                            <select name="department_id" id="modal_user_department" class="form-select" style="width: 100%;">
                                <option value="">-- Select Department --</option>
                                @if(isset($departments))
                                @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save User</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#modal_user_role').select2({
            dropdownParent: $('#addUserModal'),
            placeholder: 'Select Role',
            allowClear: true
        });

        $('#modal_user_department').select2({
            dropdownParent: $('#addUserModal'),
            placeholder: 'Select Department',
            allowClear: true
        });

        $(document).on('click', '#addNewUserBtn', function(e) {
            e.preventDefault();
            let form = $('#ajaxAddUserForm');
            form[0].reset();
            if (form.data('validator')) {
                form.validate().resetForm();
            }
            form.find('.is-invalid').removeClass('is-invalid');
            $('#modal_user_role').val('').trigger('change');
            $('#modal_user_department').val('').trigger('change');
            $('#addUserModal').modal('show');
        });

        $('#ajaxAddUserForm').validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 255
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 255,
                    remote: {
                        url: "{{ route('admin.users.check.email') }}",
                        type: "post",
                        data: {
                            email: function() {
                                return $("#modal_user_email").val();
                            },
                            _token: '{{ csrf_token() }}'
                        }
                    }
                },
                role_id: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "Please enter a name."
                },
                email: {
                    required: "Please enter a valid email.",
                    email: "Enter a valid email structure.",
                    remote: "This email is already registered."
                },
                role_id: {
                    required: "Please select a role."
                }
            },
            errorClass: 'text-danger small mt-1',
            errorElement: 'span',
            ignore: ":hidden:not(.select2-hidden-accessible)",
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            },
            errorPlacement: function(error, element) {
                if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form, event) {
                event.preventDefault();
                let formData = $(form).serialize();
                let submitBtn = $(form).find('button[type="submit"]');

                $.ajax({
                    url: "{{ route('admin.tickets.storeUser') }}",
                    type: "POST",
                    data: formData,
                    beforeSend: function() {
                        submitBtn.prop('disabled', true).text('Saving...');
                    },
                    success: function(response) {
                        submitBtn.prop('disabled', false).text('Save User');
                        if (response.success) {
                            $('#addUserModal').modal('hide');
                            showToast(response.message, 'success');

                            let optionText = response.user.name + ' - ' + response.user.email;
                            let newOption = new Option(optionText, response.user.id, true, true);
                            // $('#buyer-select').append(newOption).trigger('change');
                        }
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).text('Save User');
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                showToast(value[0], 'error');
                            });
                        } else {
                            let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Something went wrong!';
                            showToast(msg, 'error');
                        }
                    }
                });
            }
        });
    });
</script>
@endpush