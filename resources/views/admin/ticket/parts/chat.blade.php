<div class="flex-fill chat-messages">
    <div class="card border-0 mb-0">

        <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3 p-3">
            <div class="d-flex align-items-center">
                <span class="avatar me-2 flex-shrink-0"><img src="{{ asset('assets/img/profiles/default.jpg') }}"
                        alt="user" class="rounded-circle"></span>
                <div>
                    <h6 class="fs-14 fw-semibold mb-1">{{ $ticket->name }}</h6>
                    <p class="mb-0 d-inline-flex align-items-center custom-dot">{{ $ticket->email }}</p>
                </div>
            </div>
            <div class="gap-2 d-flex align-items-center flex-wrap">
                <a href="#" class="btn btn-icon btn-light refresh-chat-list" data-bs-toggle="tooltip"
                    data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh Chat">
                    <i class="ti ti-refresh"></i>
                </a>
                <a href="#" class="btn btn-icon btn-light jump-to-bottom" data-bs-toggle="tooltip"
                    data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Jump To Bottom">
                    <i class="ti ti-arrow-narrow-down-dashed"></i>
                </a>
                <a href="javascript:void(0);" class="btn btn-icon btn-light jump-to-top" data-bs-toggle="tooltip"
                    data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Jump To Top">
                    <i class="ti ti-arrow-narrow-up-dashed"></i>
                </a>
                <a href="javascript:void(0);" class="btn btn-icon btn-light close-chat d-md-none"><i
                        class="ti ti-x"></i></a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="message-body p-4 simplebar-scrollable-y" data-simplebar="init">
                <div class="simplebar-wrapper" style="margin: -24px;">
                    <div class="simplebar-height-auto-observer-wrapper">
                        <div class="simplebar-height-auto-observer"></div>
                    </div>

                    <div class="message-listing">
                    </div>

                    <div class="simplebar-placeholder" style="width: 1092px; height: 804px;"></div>
                </div>
                <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                    <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                </div>
                <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                    <div class="simplebar-scrollbar"
                        style="height: 25px; transform: translate3d(0px, 0px, 0px); display: block;"></div>
                </div>
            </div>
            <form id="chat-form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="ticket_id" value="{{ $ticket->id ?? '' }}">
                <input type="hidden" name="task_id" value="">
                <input type="hidden" name="text" id="chat-text">

                <input type="file" name="attachment" id="chat-attachment" class="d-none" accept="image/*, .pdf, .doc, .docx, .zip">

                <div id="attachment-preview-container" class="px-3 pt-2" style="display: none;">
                    <span class="badge bg-light text-dark border p-2 fs-12">
                        <i class="ti ti-paperclip me-1"></i> <span id="attachment-name"></span>
                        <i class="ti ti-x ms-2 text-danger" id="remove-attachment" style="cursor: pointer;" title="Remove file"></i>
                    </span>
                </div>

                <div class="message-footer d-flex align-items-center border-top p-3">
                    <div class="flex-fill">
                        <div id="chat-quill-editor" style="height: 100px;"></div>
                    </div>

                    <div class="d-flex align-items-center gap-2 ms-2">
                        <a href="javascript:void(0);" class="btn btn-icon btn-light" id="btn-trigger-file">
                            <i class="ti ti-photo-plus"></i>
                        </a>

                        <button type="submit" class="btn btn-icon btn-primary" id="send-chat-btn">
                            <i class="ti ti-send"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {

            // ==========================================
            // 1. Functions: Chat Load & Scroll
            // ==========================================

            function loadChats() {
                let ticketId = $('input[name="ticket_id"]').val();

                if(!ticketId) return;

                $.ajax({
                    url: "{{ route('admin.tickets.get.chats') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        ticket_id: ticketId
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            $('.message-listing').html(response.html);
                            scrollToBottom();
                        }
                    },
                    error: function() {
                        console.log("Failed to load chats.");
                    }
                });
            }

            function scrollToBottom() {
                var scrollContainer = $('#chat-scroll-container');

                if (scrollContainer.length) {
                    scrollContainer.animate({
                        scrollTop: scrollContainer[0].scrollHeight
                    }, 300);
                }
            }

            function scrollToTop() {
                var scrollContainer = $('#chat-scroll-container');

                if (scrollContainer.length) {
                    scrollContainer.animate({
                        scrollTop: 0
                    }, 300);
                }
            }

            loadChats();

            $(document).on('click', '.refresh-chat-list', function() {
                loadChats();
            });

            $(document).on('click', '.jump-to-top', function() {
                scrollToTop();
            });

            $(document).on('click', '.jump-to-bottom', function() {
                scrollToBottom();
            });

            // ==========================================
            // 2. Quill Editor Initialization
            // ==========================================

            var quill = new Quill('#chat-quill-editor', {
                theme: 'snow',
                placeholder: 'Type your message here...',
                modules: {
                    toolbar: [
                        [{ header: [1, 2, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        [{ color: [] }, { background: [] }],
                        [{ align: [] }],
                        ['link', 'image'],
                        ['clean']
                    ]
                }
            });

            // ==========================================
            // 3. Form Submit Handle
            // ==========================================

            // 1. Trigger File Input on Icon Click
            $('#btn-trigger-file').on('click', function() {
                $('#chat-attachment').click();
            });

            // 2. Show File Preview & Handle Remove
            $('#chat-attachment').on('change', function() {
                if (this.files && this.files[0]) {
                    $('#attachment-name').text(this.files[0].name);
                    $('#attachment-preview-container').show();
                }
            });

            $('#remove-attachment').on('click', function() {
                $('#chat-attachment').val(''); // Clear file input
                $('#attachment-preview-container').hide(); // Hide preview
            });

            // 3. Form Submit Logic
            $('#chat-form').on('submit', function(e) {
                e.preventDefault();

                var chatContent = quill.root.innerHTML;
                var plainText = quill.getText().trim();
                var fileInput = $('#chat-attachment')[0].files.length; // Check if file is selected

                // Validation: Text and File banne khali hoy to error
                if ((chatContent === '<p><br></p>' || plainText.length === 0) && fileInput === 0) {
                    showToast('Please enter a message or attach a file.', 'error');
                    return false;
                }

                // Jo sirf file mokli hoy ane text khali hoy, to text field ne properly null set kariye
                if (plainText.length === 0) {
                    $('#chat-text').val('');
                } else {
                    $('#chat-text').val(chatContent);
                }

                var formData = new FormData(this); // FormData aapne aap file capture kari lese
                var submitBtn = $('#send-chat-btn');

                submitBtn.prop('disabled', true);

                $.ajax({
                    url: "{{ route('admin.tickets.store.chat') }}",
                    type: "POST",
                    data: formData,
                    processData: false, // Required for file upload
                    contentType: false, // Required for file upload
                    success: function(response) {
                        if (response.status === 'success') {
                            showToast(response.message, 'success');

                            // Reset Everything
                            quill.root.innerHTML = '';
                            $('#chat-attachment').val('');
                            $('#attachment-preview-container').hide();

                            if (typeof loadChats === "function") {
                                loadChats();
                            }
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            showToast(Object.values(errors)[0][0], 'error');
                        } else {
                            showToast('Something went wrong. Please try again.', 'error');
                        }
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false);
                    }
                });
            });

            $(document).on('click', '.btn-copy', function(e) {
                e.preventDefault();

                let messageText = $(this).attr('data-message');

                navigator.clipboard.writeText(messageText).then(function() {
                    showToast('Message copied to clipboard!', 'success');
                }).catch(function(err) {
                    console.error('Could not copy text: ', err);
                    showToast('Failed to copy text.', 'error');
                });
            });

            $(document).on('click', '.btn-reply', function(e) {
                e.preventDefault();

                let messageText = $(this).attr('data-message');
                let attachment = $(this).attr('data-attachment');
                let isImage = $(this).attr('data-is-image') === 'true';

                let replyHtml = `<blockquote><strong>Replying to:</strong><br>`;

                if (messageText && messageText.trim() !== '') {
                    replyHtml += `"${messageText}"<br>`;
                }

                if (attachment && attachment !== '') {
                    if (isImage) {
                        replyHtml += `<a href="${attachment}" target="_blank" style="display: inline-block; margin-top: 5px;">
                                        <img src="${attachment}" style="max-width: 100px; max-height: 100px; border-radius: 4px;" alt="Replied Image">
                                    </a>`;
                    } else {
                        replyHtml += `<br><a href="${attachment}" target="_blank" style="color: #007bff; text-decoration: underline;">
                                        <em>📎 [Click to View/Download File]</em>
                                    </a>`;
                    }
                }

                replyHtml += `</blockquote><p><br></p>`;

                let currentHtml = quill.root.innerHTML;
                if (currentHtml === '<p><br></p>') {
                    quill.root.innerHTML = replyHtml;
                } else {
                    quill.root.innerHTML = currentHtml + replyHtml;
                }

                quill.focus();
            });

            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();

                let chatId = $(this).attr('data-id');

                if (confirm("Are you sure you want to delete this message?")) {
                    $.ajax({
                        url: "{{ route('admin.tickets.delete.chat') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            chat_id: chatId
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                showToast(response.message, 'success');

                                loadChats();
                            } else {
                                showToast(response.message, 'error');
                            }
                        },
                        error: function() {
                            showToast('Something went wrong.', 'error');
                        }
                    });
                }
            });

            // ==========================================
            // 1. CLICK EDIT (Show Input Box)
            // ==========================================
            $(document).on('click', '.btn-inline-edit', function(e) {
                e.preventDefault();
                let chatId = $(this).attr('data-id');

                // View ne hide karo, Input ne show karo
                $('#view-mode-' + chatId).addClass('d-none');
                $('#edit-mode-' + chatId).removeClass('d-none');

                // Input par focus muko
                $('#edit-input-' + chatId).focus();
            });

            // ==========================================
            // 2. CLICK CANCEL (Hide Input Box)
            // ==========================================
            $(document).on('click', '.btn-cancel-edit', function(e) {
                e.preventDefault();
                let chatId = $(this).attr('data-id');

                // Input ne hide karo, View ne pachu show karo
                $('#edit-mode-' + chatId).addClass('d-none');
                $('#view-mode-' + chatId).removeClass('d-none');
            });

            // ==========================================
            // 3. CLICK SAVE (AJAX Update)
            // ==========================================
            $(document).on('click', '.btn-save-edit', function(e) {
                e.preventDefault();
                let chatId = $(this).attr('data-id');
                let newText = $('#edit-input-' + chatId).val();
                let saveBtn = $(this);

                // Validation
                if (newText.trim() === '') {
                    showToast('Message cannot be empty.', 'error');
                    return false;
                }

                // Button ne disable karo jethi varam-var click na thay
                saveBtn.prop('disabled', true);

                $.ajax({
                    url: "{{ route('admin.tickets.update.chat.message') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        chat_id: chatId,
                        text: newText
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            showToast(response.message, 'success');

                            // Chat window farithi load karo jethi nava changes (and "edited" tag) dekhay
                            if (typeof loadChats === "function") {
                                loadChats();
                            }
                        } else {
                            showToast(response.message, 'error');
                            saveBtn.prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        showToast('Something went wrong.', 'error');
                        saveBtn.prop('disabled', false);
                    }
                });
            });

        });
    </script>
@endpush
