<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : 'View' }}">

            <input type="hidden" name="id" id="id" value="{{ isset($email_account->id) ? encrypt($email_account->id) : '' }}">

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="name">Account Name</label>
                        <div class="input-group mb-1">
                            <input disabled type="text" class="form-control" name="name" id="name" placeholder="Name"
                                value="{{ old('name', $email_account->name ?? '') }}">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="email">Email Address</label>
                        <div class="input-group mb-1">
                            <input disabled type="email" class="form-control" name="email" id="email" placeholder="Email"
                                value="{{ old('email', $email_account->email ?? '') }}">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="username">Username</label>
                        <div class="input-group mb-1">
                            <input disabled type="text" class="form-control" name="username" id="username" placeholder="Username"
                                value="{{ old('username', $email_account->username ?? '') }}">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="host">Host</label>
                        <div class="input-group mb-1">
                            <input disabled type="text" class="form-control" name="host" id="host" placeholder="Host"
                                value="{{ old('host', $email_account->host ?? '') }}">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="port">Port</label>
                        <div class="input-group mb-1">
                            <input disabled type="text" class="form-control" name="port" id="port" placeholder="Port"
                                value="{{ old('port', $email_account->port ?? '') }}">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="encryption">Encryption</label>
                        <select disabled class="form-select" name="encryption" id="encryption">
                            <option value="">None</option>
                            <option value="ssl" @if(old('encryption', $email_account->encryption ?? '') == 'ssl') selected @endif>SSL</option>
                            <option value="tls" @if(old('encryption', $email_account->encryption ?? '') == 'tls') selected @endif>TLS</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Protocol</label>
                        <div class="d-flex gap-3 mb-1 mt-2">
                            <div class="form-check">
                                <input disabled class="form-check-input" type="radio" name="protocol" id="protocol-imap" value="imap"
                                    @if (old('protocol', $email_account->protocol ?? 'imap') == 'imap') checked @endif>
                                <label class="form-check-label" for="protocol-imap">IMAP</label>
                            </div>
                            <div class="form-check">
                                <input disabled class="form-check-input" type="radio" name="protocol" id="protocol-pop3"
                                    value="pop3" @if (old('protocol', $email_account->protocol ?? 'imap') == 'pop3') checked @endif>
                                <label class="form-check-label" for="protocol-pop3">POP3</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <div class="d-flex gap-3 mb-1 mt-2">
                            <div class="form-check">
                                <input disabled class="form-check-input" type="radio" name="status" id="status-active" value="1"
                                    @if (old('status', isset($email_account) ? $email_account->status : 1) == 1) checked @endif>
                                <label class="form-check-label" for="status-active">Active</label>
                            </div>
                            <div class="form-check">
                                <input disabled class="form-check-input" type="radio" name="status" id="status-inactive"
                                    value="0" @if (old('status', isset($email_account) ? $email_account->status : 1) == 0) checked @endif>
                                <label class="form-check-label" for="status-inactive">Inactive</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="text-end mt-3">
                <a href="{{ route($moduleUrl) }}" class="btn btn-soft-light">Cancel</a>
            </div>
    </x-form-wrapper>
</x-master-layout>
