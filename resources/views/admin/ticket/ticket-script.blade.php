<script>
    $(document).ready(function () {
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

        var validator = $('#ticketForm').validate({
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
