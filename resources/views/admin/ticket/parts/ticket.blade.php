<form id="ticketForm" action="{{ $url }}" method="post" enctype="multipart/form-data">
    @csrf
    @if (isset($ticket))
        @method('PUT')
    @endif

    <input type="hidden" name="tab" value="ticket-form">

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
                <label class="form-label" for="email">Email Address <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="email" class="form-control" name="email" id="email" placeholder="Email Address"
                        value="{{ old('email', $ticket->email ?? '') }}">
                    <div class="input-group-text">
                        <input class="form-check-input mt-0" type="checkbox" name="send_email" id="send_email"
                            value="1"
                            {{ old('send_email', $ticket->send_email ?? 1) ? 'checked' : '' }}>
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
                            $ccJdecode = !empty($ticket->cc_recipients)
                                ? json_decode($ticket->cc_recipients, true)
                                : [];
                        @endphp

                        @foreach ($cc_recipients as $cc_recipient)
                            @if (in_array($cc_recipient->email, $ccJdecode))
                                <option value="{{ $cc_recipient->email }}" selected>
                                    {{ $cc_recipient->email }}</option>
                            @else
                                <option value="{{ $cc_recipient->email }}">{{ $cc_recipient->email }}
                                </option>
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
                            <option value="{{ $department->id }}"
                                {{ old('department_id', $ticket->department_id ?? '') == $department->id ? 'selected' : '' }}>
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
                        {{ old('priority', $ticket->priority ?? '') == 'High' ? 'selected' : '' }}>
                        High
                    </option>
                    <option value="Medium"
                        {{ old('priority', $ticket->priority ?? '') == 'Medium' ? 'selected' : '' }}>
                        Medium
                    </option>
                    <option value="Low"
                        {{ old('priority', $ticket->priority ?? 'Low') == 'Low' ? 'selected' : '' }}>
                        Low
                    </option>
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
                            <option value="{{ $developer->id }}"
                                {{ old('assign_id', $ticket->assign_id ?? '') == $developer->id ? 'selected' : '' }}>
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

                <div class="dropzone border-dashed" id="ticketDropzone"
                    style="border: 2px dashed #ced4da; border-radius: 5px; background: #f8f9fa;">
                    <div class="dz-message needsclick">
                        <i class="bx bx-cloud-upload fs-1 text-muted"></i>
                        <h5 class="mt-2">Drop files here or click to upload.</h5>
                    </div>
                </div>

                <input type="file" name="attachments[]" id="hiddenFileInput" multiple style="display: none;">

                <div id="existing-files-container">
                    @if (isset($ticket) && !empty($ticket->attachments))
                        @php
                            $existingFiles = json_decode($ticket->attachments, true);
                        @endphp
                        @if (is_array($existingFiles))
                            @foreach ($existingFiles as $filePath)
                                <input type="hidden" name="existing_attachments[]" value="{{ $filePath }}">
                            @endforeach
                        @endif
                    @endif
                </div>

            </div>
        </div>

    </div>

    <div class="text-end mt-3">
        <a href="{{ route($moduleUrl ?? 'admin.tasks.index') }}" class="btn btn-soft-light">Cancel</a>
        <button type="submit" class="btn btn-primary">Generate Ticket</button>
    </div>
</form>
