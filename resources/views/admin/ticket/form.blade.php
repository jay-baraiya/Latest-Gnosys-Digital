<x-master-layout>
    <x-form-wrapper action="{{ $action ?? 'Create' }}">

        @php
        $url = route('admin.tickets.store');
        @endphp

        <div class="row" id="ticket-create">
            @include('admin.ticket.parts.ticket', [
                'url' => $url,
                'departments' => $departments,
                'ticket' => null,
                'users' => $users,
                'developers' => $developers
            ])
        </div>

    </x-form-wrapper>

    @push('scripts')
    <script>
        $(document).ready(function() {

            var validator = $('#ticketForm').validate({
                ignore: ":hidden:not(.select2-hidden-accessible, #ticket-description-input, #ticket-internal-note-input)",
                rules: {
                    user_id: {
                        required: true
                    },
                    "cc_recipients[]": {
                        required: false
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
                    } else if (element.attr('id') === 'ticket-description-input') {
                        error.insertAfter('.ticket-description-editor');
                    } else if (element.closest('.input-group').length) {
                        error.insertAfter(element.closest('.input-group'));
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
                [{
                    'size': ['small', false, 'large', 'huge']
                }],
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],
                ['bold', 'italic', 'underline', 'strike'],
                [{
                    'color': []
                }, {
                    'background': []
                }],
                [{
                    'script': 'sub'
                }, {
                    'script': 'super'
                }],
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }, {
                    'list': 'check'
                }],
                [{
                    'indent': '-1'
                }, {
                    'indent': '+1'
                }],
                [{
                    'align': []
                }],
                [{
                    'direction': 'rtl'
                }],
                ['link', 'image', 'video', 'formula'],
                ['blockquote', 'code-block'],
                ['clean']
            ];

            var quillTicket = new Quill('#ticket-description-editor', {
                theme: 'snow',
                readOnly: false,
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

                $('#ticket-description-input').val(html);

                validator.element('#ticket-description-input');
            });

            var quillInternalNote = new Quill('#ticket-internal-note-editor', {
                theme: 'snow',
                readOnly: false,
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

            quillInternalNote.on('text-change', function() {
                var html = quillInternalNote.root.innerHTML;
                if (quillInternalNote.getText().trim().length === 0) {
                    html = '';
                }
                $('#ticket-internal-note-input').val(html);

                validator.element('#ticket-internal-note-input');
            });

            var quillBody = new Quill('#ticket-description-editor-body', {
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

            quillBody.on('text-change', function() {
                var html = quillBody.root.innerHTML;
                if (quillBody.getText().trim().length === 0) {
                    html = '';
                }
                $('#ticket-description-input-body-hidden').val(html);

                // validator.element('#description');
            });

        });
    </script>
    @endpush

</x-master-layout>
