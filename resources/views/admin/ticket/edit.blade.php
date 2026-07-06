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
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="row mb-1">
                                <div class="col-4 fw-bold text-dark">Status:</div>
                                <div class="col-8  text-capitalize">{{ $ticket->status }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-4 fw-bold text-dark">Priority:</div>
                                <div class="col-8 ">{{ $ticket->priority ?? '-' }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-4 fw-bold text-dark">Department:</div>
                                <div class="col-8 ">{{ $ticket?->department?->name ?? '-' }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-4 fw-bold text-dark">Create Date:</div>
                                <div class="col-8">{{ $ticket->created_at }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row mb-1">
                                <div class="col-4 fw-bold text-dark">User:</div>
                                <div class="col-8 ">
                                    <i class="bi bi-person-fill"></i> {{ $ticket?->user?->name }} ( {{ $ticket?->user?->email }} )
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-4 fw-bold text-dark">Email:</div>
                                <div class="col-8 ">{{ $ticket?->user?->email }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-4 fw-bold text-dark">CC:</div>
                                <div class="col-8 ">
                                    {{-- Safely decodes the JSON array of emails into a comma-separated string --}}
                                    @php
                                        $ccEmails = is_string($ticket->cc_recipients)
                                            ? json_decode($ticket->cc_recipients, true)
                                            : $ticket->cc_recipients;
                                    @endphp
                                    {{ is_array($ccEmails) ? implode(', ', $ccEmails) : 'N/A' }}
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-4 fw-bold text-dark">Source:</div>
                                <div class="col-8 ">{{ ucfirst($ticket->ticket_source) ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-secondary opacity-25 my-3">

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="row mb-1">
                                <div class="col-4 fw-bold text-dark">Assigned To:</div>
                                <div class="col-8 ">{{ $ticket?->assign?->name . ' ( '. $ticket?->assign?->role?->name .' ) ' ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row mb-1">
                                <div class="col-4 fw-bold text-dark">Last Updated:</div>
                                <div class="col-8">{{ $ticket->updated_at }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="row mb-1">
                                <div class="col-2 fw-bold text-dark">Description:</div>
                                <div class="col-10 text-muted">{!! $ticket->description !!}</div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row mb-1">
                                <div class="col-2 fw-bold text-dark">Note:</div>
                                <div class="col-10">{!! $ticket->internal_note !!}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>



                <hr>

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
                    action="{{ isset($role) ? route('admin.roles.update', encrypt($role->id)) : route('admin.roles.store') }}"
                    method="POST">
                    @csrf
                    @if (isset($role))
                        @method('PUT')
                    @endif
                    <input type="hidden" name="id" id="id" value="{{ isset($role->id) ? encrypt($role->id) : '' }}">

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

                    <div class="row mb-4 align-items-center">
                        <label for="ticket_status" class="col-md-2 col-form-label fw-bold text-dark">Ticket Status:</label>
                        <div class="col-md-3">
                            <select class="form-select" name="ticket_status" id="ticket_status">
                                <option value="open">Open (current)</option>
                                <option value="closed">Closed</option>
                                <option value="resolved">Resolved</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <div class="text-end mt-3">
                        <a href="{{ route($moduleUrl ?? 'admin.tasks.index') }}" class="btn btn-soft-light">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                </form>

                <form style="display: none;" class="ticket-post-internal-note tabCustomHide" id="replyInternalForm"
                    action="{{ isset($role) ? route('admin.roles.update', encrypt($role->id)) : route('admin.roles.store') }}"
                    method="POST">
                    @csrf
                    @if (isset($role))
                        @method('PUT')
                    @endif
                    <input type="hidden" name="id" id="id" value="{{ isset($role->id) ? encrypt($role->id) : '' }}">

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
                                    <input type="hidden" name="internal_note" id="internal_note"
                                        value="{{ old('internal_note', $ticket->internal_note ?? '') }}">

                                    <div class="ticket-create-internal-note-quill-edit" id="ticket-create-internal-note-quill-edit" style="height: 200px;">{!! old('internal_note', $ticket->internal_note ?? '') !!}
                                    </div>

                                    @error('description')
                                        <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4 align-items-center">
                        <label for="ticket_status" class="col-md-2 col-form-label fw-bold text-dark">Ticket Status:</label>
                        <div class="col-md-3">
                            <select class="form-select" name="ticket_status" id="ticket_status">
                                <option value="open">Open (current)</option>
                                <option value="closed">Closed</option>
                                <option value="resolved">Resolved</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <div class="text-end mt-3">
                        <a href="{{ route($moduleUrl ?? 'admin.tasks.index') }}" class="btn btn-soft-light">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                </form>
            </div>
        @endif

        {{-- <div class="card border-0">
            <div class="card-body pb-0 pt-0 px-2">
                <ul class="nav nav-tabs nav-bordered nav-bordered-primary">
                    <li class="nav-item me-3">
                        <a href="{{ $url }}?tab=ticket-form" data-tab="ticket-form"
                            class="nav-link p-2 {{ $tab == 'ticket-form' ? 'active' : '' }}">
                            <i class="ti ti-ticket  me-2"></i>Ticket
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a href="{{ $url }}?tab=task-form" data-tab="task-form"
                            class="nav-link p-2 {{ $tab == 'task-form' ? 'active' : '' }}">
                            <i class="ti ti-list me-2"></i>Tasks
                        </a>
                    </li>
                    @if (!empty($ticket->id) && !empty($ticket->user_id))
                        <li class="nav-item me-3">
                            <a href="{{ $url }}?tab=chats-form" data-tab="chats-form"
                                class="nav-link p-2 {{ $tab == 'chats-form' ? 'active' : '' }}">
                                <i class="ti ti-messages me-2"></i>Chats
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div> --}}

        {{-- <div class="row tabHide" id="ticket-form" style="display: {{ $tab != 'ticket-form' ? 'none' : '' }};">
            @include('admin.ticket.parts.ticket')
        </div> --}}

        {{-- <div class="row tabHide" id="task-form" style="display: {{ $tab != 'task-form' ? 'none' : '' }};">
            @include('admin.ticket.parts.task')
        </div> --}}

        @if (!empty($ticket->id))
            {{-- <div class="row tabHide" id="chats-form" style="display: {{ $tab != 'chats-form' ? 'none' : '' }};">
                @include('admin.ticket.parts.chat')
            </div> --}}
        @endif

    </x-form-wrapper>

    @push('scripts')
    <script>
        // Dropzone.autoDiscover = false;
        $(document).ready(function () {

            $(document).on('click', '.custom-nav-link', function (e) {
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

            $(document).on('click', '.custom-nav-link-edit', function (e) {
                e.preventDefault();

                var tab = $(this).data('tab');

                var url = new URL(window.location.href);
                url.searchParams.set('tab', tab);

                // window.history.replaceState({}, '', url);


                $('.custom-nav-link-edit').removeClass('active');
                $(this).addClass('active');

                $('.tabCustomHide').hide();
                $(`.${tab}`).show();
            });

            var validator = $('#ticketForm').validate({
                ignore: ":hidden:not(.select2-hidden-accessible, #description)",
                rules: {
                    user_id: { required: true },
                    "cc_recipients[]": { required: true }, // Array નામ હોય ત્યારે ડબલ કોટ્સ જરૂરી છે
                    ticket_source: { required: true },
                    help_topic: { required: true },
                    description: { required: true }
                    // જો તમારે department_id કે assign_id ફરજીયાત જોઈતું હોય તો નીચે મુજબ ઉમેરી શકો છો:
                    // department_id: { required: true },
                    // assign_id: { required: true }
                },
                messages: {
                    user_id: { required: "Please select a user." },
                    "cc_recipients[]": { required: "Please select at least one CC recipient." },
                    ticket_source: { required: "Please select a ticket source." },
                    help_topic: { required: "Please select a help topic." },
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
                        error.insertAfter('.ticket-create-quill-editor');
                    } else if (element.closest('.input-group').length) {
                        error.insertAfter(element.closest('.input-group'));
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form) {
                    // var quillHtml = document.querySelector('#ticket-create-quill-editor .ql-editor').innerHTML;

                    // if (quillHtml === '<p><br></p>') {
                    //     quillHtml = '';
                    // } else if (typeof quill !== 'undefined' && quill.getText().trim().length === 0) {
                    //     quillHtml = '';
                    // }

                    // $('#description').val(quillHtml);

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

            var quillTicket = new Quill('#ticket-create-quill-editor', {
                theme: 'snow',
                readOnly: '{{ !empty($disabled) ? 'true' : '' }}',
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

            quillTicket.on('text-change', function() {
                var html = quillTicket.root.innerHTML;
                if (quillTicket.getText().trim().length === 0) {
                    html = '';
                }

                $('#description').val(html);

                validator.element('#description');
            });

            var quillTicketEdit = new Quill('.ticket-create-quill-editor-edit', {
                theme: 'snow',
                readOnly: '{{ !empty($disabled) ? 'true' : '' }}',
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

            quillTicketEdit.on('text-change', function() {
                var html = quillTicketEdit.root.innerHTML;
                if (quillTicketEdit.getText().trim().length === 0) {
                    html = '';
                }

                $('#description').val(html);

                validator.element('#description');
            });

            var quill = new Quill('.ticket-create-internal-note-quill-editor', {
                theme: 'snow',
                readOnly: '{{ !empty($disabled) ? 'true' : '' }}',
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
                $('#internal_note').val(html);

                validator.element('#internal_note');
            });

            var quillInternalNoteEdit = new Quill('.ticket-create-internal-note-quill-edit', {
                theme: 'snow',
                readOnly: '{{ !empty($disabled) ? 'true' : '' }}',
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

            quillInternalNoteEdit.on('text-change', function() {
                var html = quillInternalNoteEdit.root.innerHTML;
                if (quillInternalNoteEdit.getText().trim().length === 0) {
                    html = '';
                }
                $('#internal_note').val(html);

                validator.element('#internal_note');
            });

        });
    </script>

    {{-- @include('admin.ticket.ticket-script') --}}

    {{-- @include('admin.ticket.task-script') --}}
    @endpush

</x-master-layout>
