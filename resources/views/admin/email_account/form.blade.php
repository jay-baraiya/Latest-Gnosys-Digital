<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : 'Create' }}">
        <form id="emailAccountForm"
            action="{{ isset($email_account) ? route('admin.email_accounts.update', encrypt($email_account->id)) : route('admin.email_accounts.store') }}" method="POST">
            @csrf
            @if (isset($email_account))
                @method('PUT')
            @endif

            <input type="hidden" name="id" id="id" value="{{ isset($email_account->id) ? encrypt($email_account->id) : '' }}">

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="name">Account Name <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input type="text" class="form-control" name="name" id="name" placeholder="E.g., Support Mail"
                                value="{{ old('name', $email_account->name ?? '') }}">
                        </div>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="email">Email Address <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input type="email" class="form-control" name="email" id="email" placeholder="example@domain.com"
                                value="{{ old('email', $email_account->email ?? '') }}">
                        </div>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="username">Username <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input type="text" class="form-control" name="username" id="username" placeholder="Username (usually email)"
                                value="{{ old('username', $email_account->username ?? '') }}">
                        </div>
                        @error('username')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="password">Password @if(!isset($email_account))<span class="text-danger">*</span>@endif</label>
                        <div class="input-group mb-1">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password"
                                value="">
                        </div>
                        @if(isset($email_account))
                        <small class="text-muted">Leave blank if you do not want to change the password.</small>
                        @endif
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="host">Host <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input type="text" class="form-control" name="host" id="host" placeholder="E.g., imap.gmail.com"
                                value="{{ old('host', $email_account->host ?? '') }}">
                        </div>
                        @error('host')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="port">Port <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input type="text" class="form-control" name="port" id="port" placeholder="E.g., 993"
                                value="{{ old('port', $email_account->port ?? '') }}">
                        </div>
                        @error('port')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="encryption">Encryption</label>
                        <select class="form-select" name="encryption" id="encryption">
                            <option value="">None</option>
                            <option value="ssl" @if(old('encryption', $email_account->encryption ?? '') == 'ssl') selected @endif>SSL</option>
                            <option value="tls" @if(old('encryption', $email_account->encryption ?? '') == 'tls') selected @endif>TLS</option>
                        </select>
                        @error('encryption')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Protocol <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3 mb-1 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="protocol" id="protocol-imap" value="imap"
                                    @if (old('protocol', $email_account->protocol ?? 'imap') == 'imap') checked @endif>
                                <label class="form-check-label" for="protocol-imap">IMAP</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="protocol" id="protocol-pop3"
                                    value="pop3" @if (old('protocol', $email_account->protocol ?? 'imap') == 'pop3') checked @endif>
                                <label class="form-check-label" for="protocol-pop3">POP3</label>
                            </div>
                        </div>
                        @error('protocol')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3 mb-1 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status-active" value="1"
                                    @if (old('status', isset($email_account) ? $email_account->status : 1) == 1) checked @endif>
                                <label class="form-check-label" for="status-active">Active</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status-inactive"
                                    value="0" @if (old('status', isset($email_account) ? $email_account->status : 1) == 0) checked @endif>
                                <label class="form-check-label" for="status-inactive">Inactive</label>
                            </div>
                        </div>
                        @error('status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <hr>

            <div class="text-end mt-3">
                <a href="{{ route($moduleUrl) }}" class="btn btn-soft-light">Cancel</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </x-form-wrapper>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#emailAccountForm').validate({
                    rules: {
                        name: {
                            required: true,
                            minlength: 2,
                            maxlength: 255,
                            remote: {
                                url: "{{ route('admin.validate.email_accounts') }}",
                                type: "post",
                                data: {
                                    id: function() {
                                        return $("#id").val();
                                    },
                                    name: function() {
                                        return $("#name").val();
                                    }
                                }
                            }
                        },
                        email: { required: true, email: true },
                        username: { required: true },
                        @if(!isset($email_account))
                        password: { required: true },
                        @endif
                        host: { required: true },
                        port: { required: true },
                        protocol: { required: true },
                        status: { required: true }
                    },
                    messages: {
                        name: {
                            required: "Please enter a name.",
                            remote: "Name already exists."
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
                        } else if (element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else if (element.prop('type') === 'radio') {
                            error.insertAfter(element.closest('.d-flex'));
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });
            });
        </script>
    @endpush

</x-master-layout>
