@foreach(auth()->user()->unreadNotifications as $notification)
    @php
        $title = $notification->data['title'] ?? 'Notification';
        $message = $notification->data['message'] ?? 'You have a new notification';
        $hasDownload = isset($notification->data['download_url']);
        $downloadUrl = $hasDownload ? route('admin.notifications.read', $notification->id) : 'javascript:void(0);';
    @endphp
    <div class="dropdown-item notification-item py-3 text-wrap border-bottom unread-notification"
        id="notification-{{ $notification->id }}">
        <div class="d-flex align-items-start">
            <a href="{{ $hasDownload ? $downloadUrl : 'javascript:void(0);' }}" class="d-flex w-100 text-decoration-none text-dark download-btn" data-id="{{ $notification->id }}">
                <div class="me-2 position-relative flex-shrink-0">
                    <div class="avatar-sm">
                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-16">
                            <i class="ti ti-bell"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1">{{ $title }}</h6>
                    <div class="fs-13 text-muted">
                        <p class="mb-1">{{ $message }}</p>
                    </div>
                    <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                        <span><i class="ti ti-clock me-1"></i> {{ $notification->created_at->diffForHumans() }}</span>
                    </p>
                </div>
            </a>
            <div class="notification-action ms-2 d-flex gap-1">
                @if($hasDownload)
                <a href="{{ $downloadUrl }}" class="btn btn-sm btn-icon btn-primary rounded-circle download-btn" 
                    data-id="{{ $notification->id }}" data-bs-toggle="tooltip" title="Download">
                    <i class="ti ti-download text-white"></i>
                </a>
                @endif
                <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-light mark-as-read-btn rounded-circle"
                    data-id="{{ $notification->id }}" data-bs-toggle="tooltip" title="Mark as Read">
                    <i class="ti ti-check text-success"></i>
                </a>
            </div>
        </div>
    </div>
@endforeach
