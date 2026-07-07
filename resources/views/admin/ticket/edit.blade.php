<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : (isset($ticket) ? 'Edit' : 'Create') }}">

        @php
        $url = isset($ticket) ? route('admin.tickets.update', encrypt($ticket->id)) : route('admin.tickets.store');
        @endphp

        @if (isset($ticket))
        <div class="card border-0">
            <div class="card-body pb-0 pt-0 px-2">
                <ul class="nav nav-tabs nav-bordered nav-bordered-primary">
                    <li class="nav-item me-3">
                        <a href="{{ $url }}?tab=ticket-post-raplay" data-tab="ticket-post-raplay"
                            class="nav-link custom-nav-link p-2 {{ $tab == 'ticket-post-raplay' ? 'active' : '' }}">
                            <i class="ti ti-ticket  me-2"></i>Ticket
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a href="{{ $url }}?tab=ticket-create" data-tab="ticket-create"
                            class="nav-link custom-nav-link p-2 {{ $tab == 'ticket-create' ? 'active' : '' }}">
                            <i class="ti ti-ticket  me-2"></i>Edit
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a href="{{ $url }}?tab=task-form" data-tab="task-form"
                            class="nav-link custom-nav-link p-2 {{ $tab == 'task-form' ? 'active' : '' }}">
                            <i class="ti ti-list me-2"></i>Tasks
                        </a>
                    </li>
                    @if (!empty($ticket->id) && !empty($ticket->user_id))
                    <li class="nav-item me-3">
                        <a href="{{ $url }}?tab=chats-form" data-tab="chats-form"
                            class="nav-link custom-nav-link p-2 {{ $tab == 'chats-form' ? 'active' : '' }}">
                            <i class="ti ti-messages me-2"></i>Chats
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        @endif

        <div class="row tabHide" id="ticket-create" style="display: {{ $tab != 'ticket-create' ? 'none' : '' }}">
            @include('admin.ticket.parts.ticket', [
            'url' => $url,
            'departments' => $departments,
            'ticket' => $ticket,
            'users' => $users,
            'developers' => $developers
            ])
        </div>

        @if (isset($ticket))
        <div class="row tabHide" id="ticket-post-raplay">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <h5 class="mb-0 fs-16 fw-bold d-inline-flex items-center">
                    <span class="line-title d-block me-2"></span>
                    Ticket :- &nbsp; <span class="text-primary"> #{{ $ticket->ticket_number }} </span>
                </h5>
                {{-- <ul class="nav nav-tabs nav-solid-danger border rounded gap-2 p-1" role="tablist">
                        <li class="nav-item" role="presentation"><a class="nav-link py-1 px-2 rounded active" href="#wekly" data-bs-toggle="tab" aria-selected="true" role="tab">Weekly</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link py-1 px-2 rounded" href="#monthly" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Monthly</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link py-1 px-2 rounded" href="#yearly" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">Yearly</a></li>
                    </ul> --}}
            </div>

            <hr>

            <div class="ticket-detaili" id="ticket-detaili">
                <div class="row g-3 mb-4">
                    <!-- Ticket Meta Column -->
                    <div class="col-md-6">
                        <div class="card shadow-none border h-100 mb-0">
                            <div class="card-body p-3">
                                <h6 class="fs-14 fw-bold mb-3 text-dark border-bottom pb-2">
                                    <i class="ti ti-ticket me-1 text-primary"></i> Ticket Information
                                </h6>
                                <div class="row mb-2 align-items-center">
                                    <div class="col-4 fw-medium text-secondary">Status:</div>
                                    <div class="col-8">
                                        @php
                                            $statusClass = match(strtolower($ticket->ticket_status ?? $ticket->status ?? '')) {
                                                'resolved' => 'bg-info-subtle text-info border-info-subtle',
                                                'closed' => 'bg-secondary-subtle text-secondary border-secondary-subtle',
                                                default => 'bg-success-subtle text-success border-success-subtle',
                                            };
                                        @endphp
                                        <span class="badge border px-2 py-1 text-capitalize {{ $statusClass }}">
                                            {{ $ticket->ticket_status ?? $ticket->status ?? 'Open' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row mb-2 align-items-center">
                                    <div class="col-4 fw-medium text-secondary">Priority:</div>
                                    <div class="col-8">
                                        @php
                                            $priorityClass = match(strtolower($ticket->priority ?? '')) {
                                                'high' => 'bg-danger-subtle text-danger border-danger-subtle',
                                                'medium' => 'bg-warning-subtle text-warning border-warning-subtle',
                                                'low' => 'bg-success-subtle text-success border-success-subtle',
                                                default => 'bg-light text-dark border-light-subtle',
                                            };
                                        @endphp
                                        <span class="badge border px-2 py-1 text-capitalize {{ $priorityClass }}">
                                            {{ $ticket->priority ?? 'None' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 fw-medium text-secondary">Department:</div>
                                    <div class="col-8 text-dark fw-semibold">{{ $ticket?->department?->name ?? 'None' }}</div>
                                </div>
                                <div class="row mb-0">
                                    <div class="col-4 fw-medium text-secondary">Create Date:</div>
                                    <div class="col-8 text-dark">{{ \Carbon\Carbon::parse($ticket->created_at)->format('d M Y, h:i A') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Client Info Column -->
                    <div class="col-md-6">
                        <div class="card shadow-none border h-100 mb-0">
                            <div class="card-body p-3">
                                <h6 class="fs-14 fw-bold mb-3 text-dark border-bottom pb-2">
                                    <i class="ti ti-user me-1 text-primary"></i> Client Information
                                </h6>
                                <div class="row mb-2">
                                    <div class="col-4 fw-medium text-secondary">User:</div>
                                    <div class="col-8 text-dark fw-semibold">
                                        <i class="ti ti-user-circle me-1"></i>{{ $ticket?->user?->name ?? 'Guest' }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 fw-medium text-secondary">Email:</div>
                                    <div class="col-8 text-dark">
                                        <a href="mailto:{{ $ticket?->user?->email }}" class="text-primary">{{ $ticket?->user?->email ?? '-' }}</a>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4 fw-medium text-secondary">CC:</div>
                                    <div class="col-8 text-dark">
                                        @php
                                            $ccEmails = is_string($ticket->cc_recipients)
                                                ? json_decode($ticket->cc_recipients, true)
                                                : $ticket->cc_recipients;
                                        @endphp
                                        @if(is_array($ccEmails) && count($ccEmails) > 0)
                                            @foreach($ccEmails as $ccEmail)
                                                <span class="badge bg-light text-secondary border me-1 mb-1">{{ $ccEmail }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-0">
                                    <div class="col-4 fw-medium text-secondary">Source:</div>
                                    <div class="col-8 text-dark text-capitalize">{{ $ticket->ticket_source ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-none border mb-4">
                    <div class="card-body p-3">
                        <h6 class="fs-14 fw-bold mb-3 text-dark border-bottom pb-2">
                            <i class="ti ti-user-check me-1 text-primary"></i> Assignment & Updates
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="row mb-0">
                                    <div class="col-4 fw-medium text-secondary">Assigned To:</div>
                                    <div class="col-8 text-dark fw-semibold">
                                        @if($ticket?->assign)
                                            {{ $ticket->assign->name }} 
                                            <span class="badge bg-light text-secondary border ms-1">{{ $ticket->assign->role?->name ?? 'Agent' }}</span>
                                        @else
                                            <span class="text-muted">Unassigned</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-0">
                                    <div class="col-4 fw-medium text-secondary">Last Updated:</div>
                                    <div class="col-8 text-dark">{{ \Carbon\Carbon::parse($ticket->updated_at)->format('d M Y, h:i A') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($ticket->description || $ticket->internal_note)
                    <div class="row g-3 mb-4">
                        @if($ticket->description)
                            <div class="col-12">
                                <div class="card shadow-none border mb-0" style="background-color: #fafafa;">
                                    <div class="card-body p-3">
                                        <h6 class="fs-13 fw-bold mb-2 text-dark">
                                            <i class="ti ti-file-text me-1 text-secondary"></i> Description:
                                        </h6>
                                        <div class="text-secondary fs-13 overflow-auto">
                                            {!! $ticket->description !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        @if($ticket->internal_note)
                            <div class="col-12">
                                <div class="card shadow-none border mb-0" style="background-color: #fffbeb; border-color: #fef08a !important;">
                                    <div class="card-body p-3">
                                        <h6 class="fs-13 fw-bold mb-2 text-warning-emphasis">
                                            <i class="ti ti-note me-1 text-warning"></i> Internal Note:
                                        </h6>
                                        <div class="text-warning-emphasis fs-13 overflow-auto">
                                            {!! $ticket->internal_note !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="desc-note-section">
                @if(isset($ticket) && isset($ticket->notes) && $ticket->notes->isNotEmpty())
                    <div class="position-relative ps-5">
                        <!-- Dotted vertical timeline line -->
                        <div class="position-absolute start-0 top-0 bottom-0 ms-3 border-start border-2 border-dashed border-secondary-subtle" style="left: 0.25rem;"></div>

                        @foreach($ticket->notes as $note)
                            @php
                                $isInternal = $note->ref_type === 'internal_note';
                                $headerBg = $isInternal ? '#fefce8' : '#fff7ed';
                                $borderColor = $isInternal ? '#fef08a' : '#ffedd5';
                            @endphp
                            <div class="position-relative mb-4">
                                <!-- Avatar icon on left of card -->
                                <span class="position-absolute rounded-circle bg-light border border-2 d-flex align-items-center justify-content-center shadow-sm" 
                                      style="left: -3.2rem; top: 0.5rem; width: 2.5rem; height: 2.5rem; border-color: #dee2e6 !important;">
                                    <i class="ti ti-user fs-16 text-muted"></i>
                                </span>

                                <div class="card border mb-0 shadow-sm" style="border-color: {{ $borderColor }} !important;">
                                    <div class="card-header d-flex justify-content-between align-items-center py-2 px-3 border-bottom" 
                                         style="background-color: {{ $headerBg }}; border-color: {{ $borderColor }} !important;">
                                        <div class="d-flex align-items-center flex-wrap gap-1">
                                            <span class="fw-bold text-dark me-1">{{ $note->user?->name ?? 'System' }}</span>
                                            <span class="text-muted me-1">posted</span>
                                            <span class="text-secondary-emphasis fw-medium">{{ \Carbon\Carbon::parse($note->datetime)->format('m/d/y g:i A') }}</span>
                                            @if($isInternal)
                                                <span class="text-warning-emphasis bg-warning-subtle border border-warning-subtle rounded px-2 py-0.5 ms-2" style="font-size: 11px;">internal</span>
                                            @endif
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-icon btn-link text-muted p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ti ti-chevron-down fs-15"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#">View Details</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body p-3 bg-white">
                                        <div class="note-text text-dark fs-14">
                                            {!! $note->text !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center py-3">No description or notes posted yet.</p>
                @endif
            </div>

            <ul class="nav nav-tabs nav-bordered nav-bordered-primary">
                <li class="nav-item me-3">
                    <a href="{{ $url }}?tab=ticket-post-raplay" data-tab="ticket-post-raplay"
                        class="nav-link custom-nav-link-edit p-2 active">
                        <i class="ti ti-align-box-left-stretch"></i>
                        Post Replay
                    </a>
                </li>
                <li class="nav-item me-3">
                    <a href="{{ $url }}?tab=ticket-post-internal-note" data-tab="ticket-post-internal-note"
                        class="nav-link custom-nav-link-edit p-2">
                        <i class="ti ti-align-box-left-stretch"></i>
                        Post Internal Note
                    </a>
                </li>
            </ul>

            <hr>

            <form class="ticket-post-raplay tabCustomHide" id="replyForm"
                action="{{ route('admin.tickets.storeReply', encrypt($ticket->id)) }}"
                method="POST">
                @csrf

                <div class="row mb-3 align-items-center">
                    <label for="from_email" class="col-md-2 col-form-label fw-bold text-dark">From:</label>
                    <div class="col-md-5">
                        <select class="form-select" name="from_email" id="from_email">
                            <option value="connect">Gnosys Digital Customer Support &lt;connect@gnosysdig...&gt;</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-2 col-form-label fw-bold text-dark pt-0">Recipients:</label>
                    <div class="col-md-10">
                        <a href="#" class="text-primary">
                            <div class="mb-1">"jay" &lt;jay.baraiya@gnosysdigital.com&gt;</div>
                        </a>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label for="reply_to" class="col-md-2 col-form-label fw-bold text-dark">Reply To:</label>
                    <div class="col-md-5 d-flex align-items-center gap-2">
                        <select class="form-select" name="reply_to" id="reply_to">
                            <option value="all">All Active Recipients</option>
                        </select>
                        <i class="bi bi-question-circle text-muted" title="Help information"></i>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label for="reply_canned_response" class="col-md-2 col-form-label fw-bold text-dark">Response:</label>
                    <div class="col-md-5">
                        <select class="form-select" name="canned_response" id="reply_canned_response">
                            <option value="">Select a canned response</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label" for="reply-description-input">Description <span class="text-danger">*</span></label>

                            <input type="hidden" name="description" id="reply-description-input"
                                value="{{ old('description', $ticket->description ?? '') }}">

                            <div class="reply-description-editor" id="reply-description-editor" style="height: 200px;">{!! old('description', $ticket->description ?? '') !!}</div>

                            @error('description')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-2 align-items-center">
                    <label class="col-md-2 col-form-label fw-bold text-dark">Signature:</label>
                    <div class="col-md-10">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="signature_type" id="replySigNone" value="none" checked>
                            <label class="form-check-label" for="replySigNone">None</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="signature_type" id="replySigDept" value="department">
                            <label class="form-check-label" for="replySigDept">Department Signature (Support)</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-4 align-items-center">
                    <label for="reply_ticket_status" class="col-md-2 col-form-label fw-bold text-dark">Ticket Status:</label>
                    <div class="col-md-3">
                        <select class="form-select" name="ticket_status" id="reply_ticket_status">
                            <option value="open">Open (current)</option>
                            <option value="closed">Closed</option>
                            <option value="resolved">Resolved</option>
                        </select>
                    </div>
                </div>

                <hr>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>

            <form style="display: none;" class="ticket-post-internal-note tabCustomHide" id="replyInternalForm" action="{{ route('admin.tickets.storeInternalNote', encrypt($ticket->id)) }}" method="POST">
                @csrf
                <div class="row mb-3 align-items-center">
                    <label for="from_email" class="col-md-2 col-form-label fw-bold text-dark">Internal Note:</label>
                    <div class="col-md-5">
                        <label>Note title - summary of the note (optional)</label>
                        <input type="text" class="form-control" name="internal_note_title" value="">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <input type="hidden" name="internal_note" id="reply-internal-note-input"
                                    value="{{ old('internal_note', $ticket->internal_note ?? '') }}">

                                <div class="reply-internal-note-editor" id="reply-internal-note-editor" style="height: 200px;">{!! old('internal_note', $ticket->internal_note ?? '') !!}
                                </div>

                                @error('description')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4 align-items-center">
                    <label for="reply_internal_ticket_status" class="col-md-2 col-form-label fw-bold text-dark">Ticket Status:</label>
                    <div class="col-md-3">
                        <select class="form-select" name="ticket_status" id="reply_internal_ticket_status">
                            <option value="open">Open (current)</option>
                            <option value="closed">Closed</option>
                            <option value="resolved">Resolved</option>
                        </select>
                    </div>
                </div>

                <hr>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        @endif
    </x-form-wrapper>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.custom-nav-link', function(e) {
                e.preventDefault();
                var tab = $(this).data('tab');
                var url = new URL(window.location.href);
                url.searchParams.set('tab', tab);
                window.history.replaceState({}, '', url);
                $('.custom-nav-link').removeClass('active');
                $(this).addClass('active');
                $('.tabHide').hide();
                $(`#${tab}`).show();
            });

            $(document).on('click', '.custom-nav-link-edit', function(e) {
                e.preventDefault();
                var tab = $(this).data('tab');
                $('.custom-nav-link-edit').removeClass('active');
                $(this).addClass('active');
                $('.tabCustomHide').hide();
                $(`.${tab}`).show();
            });

            var validator = $('#ticketForm').validate({
                ignore: ":hidden:not(.select2-hidden-accessible, #ticket-description-input, #ticket-internal-note-input)",
                rules: {
                    user_id: {
                        required: true
                    },
                    "cc_recipients[]": {
                        required: true
                    },
                    ticket_source: {
                        required: true
                    },
                    help_topic: {
                        required: true
                    },
                    description: {
                        required: true
                    }
                },
                messages: {
                    user_id: {
                        required: "Please select a user."
                    },
                    "cc_recipients[]": {
                        required: "Please select at least one CC recipient."
                    },
                    ticket_source: {
                        required: "Please select a ticket source."
                    },
                    help_topic: {
                        required: "Please select a help topic."
                    },
                    description: {
                        required: "Please enter the ticket description."
                    }
                },
                errorClass: 'text-danger small mt-1',
                errorElement: 'span',
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                },
                errorPlacement: function(error, element) {
                    if (element.hasClass('select2-hidden-accessible')) {
                        error.insertAfter(element.next('.select2-container'));
                    } else if (element.attr('id') === 'ticket-description-input') {
                        error.insertAfter('.ticket-description-editor');
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });

            Quill.register("modules/htmlEditButton", htmlEditButton);
            var toolbarOptions = [
                [{
                    'font': []
                }],
                ['bold', 'italic', 'underline'],
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                ['link', 'image']
            ];

            var quillTicket = new Quill('#ticket-description-editor', {
                theme: 'snow',
                readOnly: {{ !empty($disabled) ? 'true' : 'false' }},
                modules: {
                    toolbar: toolbarOptions
                }
            });

            quillTicket.on('text-change', function() {
                var html = quillTicket.root.innerHTML;
                $('#ticket-description-input').val(html);
                validator.element('#ticket-description-input');
            });

            var quillTicketEdit = new Quill('#reply-description-editor', {
                theme: 'snow',
                readOnly: {{ !empty($disabled) ? 'true' : 'false' }},
                modules: {
                    toolbar: toolbarOptions
                }
            });

            quillTicketEdit.on('text-change', function() {
                var html = quillTicketEdit.root.innerHTML;
                $('#reply-description-input').val(html);
            });

            var quillInternalNote = new Quill('#ticket-internal-note-editor', {
                theme: 'snow',
                readOnly: {{ !empty($disabled) ? 'true' : 'false' }},
                modules: {
                    toolbar: toolbarOptions
                }
            });

            quillInternalNote.on('text-change', function() {
                var html = quillInternalNote.root.innerHTML;
                $('#ticket-internal-note-input').val(html);
                validator.element('#ticket-internal-note-input');
            });

            var quillInternalNoteEdit = new Quill('#reply-internal-note-editor', {
                theme: 'snow',
                readOnly: {{ !empty($disabled) ? 'true' : 'false' }},
                modules: {
                    toolbar: toolbarOptions
                }
            });

            quillInternalNoteEdit.on('text-change', function() {
                var html = quillInternalNoteEdit.root.innerHTML;
                $('#reply-internal-note-input').val(html);
            });

            // Post Reply Form Validation
            $('#replyForm').validate({
                ignore: ":hidden:not(#reply-description-input)",
                rules: {
                    description: { required: true }
                },
                messages: {
                    description: { required: "Please enter your reply." }
                },
                errorClass: 'text-danger small mt-1',
                errorElement: 'span',
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                },
                errorPlacement: function(error, element) {
                    error.insertAfter(element.closest('.mb-3'));
                },
                submitHandler: function(form) {
                    var html = quillTicketEdit.root.innerHTML;
                    if (quillTicketEdit.getText().trim().length === 0) {
                        html = '';
                    }
                    $('#reply-description-input').val(html);
                    form.submit();
                }
            });

            // Post Internal Note Form Validation
            $('#replyInternalForm').validate({
                ignore: ":hidden:not(#reply-internal-note-input)",
                rules: {
                    internal_note: { required: true }
                },
                messages: {
                    internal_note: { required: "Please enter your internal note." }
                },
                errorClass: 'text-danger small mt-1',
                errorElement: 'span',
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                },
                errorPlacement: function(error, element) {
                    error.insertAfter(element.closest('.mb-3'));
                },
                submitHandler: function(form) {
                    var html = quillInternalNoteEdit.root.innerHTML;
                    if (quillInternalNoteEdit.getText().trim().length === 0) {
                        html = '';
                    }
                    $('#reply-internal-note-input').val(html);
                    form.submit();
                }
            });
        });
    </script>

    {{-- @include('admin.ticket.ticket-script') --}}

    {{-- @include('admin.ticket.task-script') --}}
    @endpush

</x-master-layout>