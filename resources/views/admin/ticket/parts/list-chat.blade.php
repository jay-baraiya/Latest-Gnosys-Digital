<div class="simplebar-mask">
    <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
        <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: auto; overflow: hidden scroll;" id="chat-scroll-container">
            <div class="simplebar-content" style="padding: 24px;">

                @if(isset($chats) && $chats->count() > 0)
                    @foreach($chats as $chat)
                        @php
                            // Check extension to display Image or Document button
                            $isImage = false;
                            if($chat->attachment) {
                                $ext = strtolower(pathinfo($chat->attachment, PATHINFO_EXTENSION));
                                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                            }
                        @endphp

                        @if($chat->user_id == auth()->id())
                            <div class="chat-list ms-auto mb-3">
                                <div class="d-flex align-items-start justify-content-end">
                                    <div>
                                        <div class="d-flex align-items-center justify-content-end mb-1">
                                            <p class="mb-0 d-inline-flex align-items-center">
                                                {{ $chat->created_at->format('h:i A') }}
                                                <i class="ti ti-point-filled mx-2"></i>
                                            </p>
                                            <h6 class="fs-14 fw-semibold mb-0">You</h6>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <a href="javascript:void(0);" data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </a>
                                                <ul class="dropdown-menu p-2">
                                                    <li>
                                                        <a class="dropdown-item btn-reply"
                                                        href="javascript:void(0);"
                                                        data-message="{{ strip_tags($chat->text) }}"
                                                        data-attachment="{{ $chat->attachment ? asset('storage/' . $chat->attachment) : '' }}"
                                                        data-is-image="{{ isset($isImage) && $isImage ? 'true' : 'false' }}">
                                                            <i class="ti ti-arrow-back-up me-1"></i>Reply
                                                        </a>
                                                    </li>

                                                    @if(!$chat->attachment)
                                                        <li>
                                                            <a class="dropdown-item btn-copy" href="javascript:void(0);" data-message="{{ strip_tags($chat->text) }}">
                                                                <i class="ti ti-file-export me-1"></i>Copy
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item btn-inline-edit text-primary" href="javascript:void(0);" data-id="{{ $chat->id }}">
                                                                <i class="ti ti-pencil me-1"></i>Edit
                                                            </a>
                                                        </li>
                                                    @endif

                                                    <li>
                                                        <a class="dropdown-item btn-delete text-danger" href="javascript:void(0);" data-id="{{ $chat->id }}">
                                                            <i class="ti ti-trash me-1"></i>Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="message-box sent-message p-3">

                                                <div id="view-mode-{{ $chat->id }}">
                                                    <div class="mb-0 fs-14">{!! $chat->text !!}</div>

                                                    @if($chat->attachment)
                                                        <div class="mt-2 text-end">
                                                            @if($isImage)
                                                                <a href="{{ asset('storage/' . $chat->attachment) }}" target="_blank">
                                                                    <img src="{{ asset('storage/' . $chat->attachment) }}" class="rounded" style="max-width: 150px; max-height: 150px;" alt="attachment">
                                                                </a>
                                                            @else
                                                                <a href="{{ asset('storage/' . $chat->attachment) }}" target="_blank" class="btn btn-sm btn-light border">
                                                                    <i class="ti ti-file-download me-1"></i> View File
                                                                </a>
                                                            @endif
                                                        </div>
                                                    @endif

                                                    @if($chat->is_edited)
                                                        <small class="text-white-50 d-block mt-1" style="font-size: 10px;">(edited)</small>
                                                    @endif
                                                </div>

                                                <div id="edit-mode-{{ $chat->id }}" class="d-none mt-2">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" id="edit-input-{{ $chat->id }}" value="{{ strip_tags($chat->text) }}">
                                                        <button class="btn btn-success btn-save-edit" data-id="{{ $chat->id }}" title="Save">
                                                            <i class="ti ti-check"></i>
                                                        </button>
                                                        <button class="btn btn-light btn-cancel-edit text-dark" data-id="{{ $chat->id }}" title="Cancel">
                                                            <i class="ti ti-x"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <span class="avatar ms-2 flex-shrink-0">
                                        <img src="{{ asset('assets/img/profiles/default.jpg') }}" class="rounded-circle" alt="user">
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="chat-list mb-3">
                                <div class="d-flex align-items-start">
                                    <span class="avatar me-2 flex-shrink-0">
                                        <img src="{{ asset('assets/img/profiles/default.jpg') }}" class="rounded-circle" alt="user">
                                    </span>
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            <h6 class="fs-14 mb-0">{{ $chat->user->name ?? 'User' }}</h6>
                                            <p class="mb-0 d-inline-flex align-items-center">
                                                <i class="ti ti-point-filled mx-2"></i>
                                                {{ $chat->created_at->format('h:i A') }}
                                            </p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="message-box receive-message p-3">
                                                <div class="mb-2 fs-14">{!! $chat->text !!}</div>

                                                @if($chat->attachment)
                                                    <div class="mt-2">
                                                        @if($isImage)
                                                            <a href="{{ asset('storage/' . $chat->attachment) }}" target="_blank">
                                                                <img src="{{ asset('storage/' . $chat->attachment) }}" class="rounded" style="max-width: 150px; max-height: 150px;" alt="attachment">
                                                            </a>
                                                        @else
                                                            <a href="{{ asset('storage/' . $chat->attachment) }}" target="_blank" class="btn btn-sm btn-light border">
                                                                <i class="ti ti-file-download me-1"></i> View File
                                                            </a>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ms-2">
                                                <a href="javascript:void(0);" data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </a>
                                                <ul class="dropdown-menu p-2">
                                                    <li>
                                                        <a class="dropdown-item btn-reply"
                                                        href="javascript:void(0);"
                                                        data-message="{{ strip_tags($chat->text) }}"
                                                        data-attachment="{{ $chat->attachment ? asset('storage/' . $chat->attachment) : '' }}"
                                                        data-is-image="{{ isset($isImage) && $isImage ? 'true' : 'false' }}">
                                                            <i class="ti ti-arrow-back-up me-1"></i>Reply
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item btn-copy" href="javascript:void(0);" data-message="{{ strip_tags($chat->text) }}">
                                                            <i class="ti ti-file-export me-1"></i>Copy
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="text-center text-muted mt-5">No messages yet. Start the conversation!</div>
                @endif

            </div>
        </div>
    </div>
</div>
