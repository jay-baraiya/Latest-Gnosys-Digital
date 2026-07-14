<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : (isset($task) ? 'Edit' : 'Create') }}">

        @php
            $url = isset($task) ? route('admin.tasks.update', encrypt($task->id)) : route('admin.tickets.store');
        @endphp

        <div class="card border-0">
            <div class="card-body pb-0 pt-0 px-2">
                <ul class="nav nav-tabs nav-bordered nav-bordered-primary">
                    <li class="nav-item me-3">
                        <a href="{{ $url }}?tab=task-detail" data-tab="task-detail"
                            class="nav-link custom-nav-link p-2 {{ $tab == 'task-detail' ? 'active' : '' }}">
                            <i class="ti ti-ticket  me-2"></i>Task
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a href="{{ $url }}?tab=task-edit" data-tab="task-edit"
                            class="nav-link custom-nav-link p-2 {{ $tab == 'task-edit' ? 'active' : '' }}">
                            <i class="ti ti-ticket  me-2"></i>Edit
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a href="{{ $url }}?tab=task-custom-field" data-tab="task-custom-field"
                            class="nav-link custom-nav-link p-2 {{ $tab == 'task-custom-field' ? 'active' : '' }}">
                            <i class="ti ti-list me-2"></i>Custom Field
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="task-detail tabHide" id="task-detail" style="display: {{ $tab != 'task-detail' ? 'none' : '' }}">
            <div class="row g-3 mb-4">
                <!-- Task & Ticket Meta Column -->
                <div class="col-md-6">
                    <div class="card shadow-none border h-100 mb-0">
                        <div class="card-body p-3">
                            <h6 class="fs-14 fw-bold mb-3 text-dark border-bottom pb-2">
                                <i class="ti ti-ticket me-1 text-primary"></i> Task Information
                            </h6>

                            <div class="row mb-2">
                                <div class="col-4 fw-medium text-secondary">Task No:</div>
                                <div class="col-8 text-dark fw-semibold">#{{ $task->task_number ?? 'N/A' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-4 fw-medium text-secondary">Ticket No:</div>
                                <div class="col-8 text-dark">
                                    @if (!empty($task->ticket->ticket_number))
                                        <a target="_blank"
                                            href="{{ route('admin.tickets.edit', ['ticket' => encrypt($task->ticket_id)]) }}">
                                            #{{ $task?->ticket?->ticket_number ?? 'N/A' }}
                                        </a>
                                    @else
                                        {{ '-' }}
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-2 align-items-center">
                                <div class="col-4 fw-medium text-secondary">Status:</div>
                                <div class="col-8">
                                    @php
                                        // Updated status with your Enum list
                                        $statusClass = match (strtolower($task->status ?? '')) {
                                            'pending' => 'bg-warning-subtle text-warning border-warning-subtle',
                                            'assigned' => 'bg-primary-subtle text-primary border-primary-subtle',
                                            'in_progress' => 'bg-info-subtle text-info border-info-subtle',
                                            'on_hold' => 'bg-secondary-subtle text-secondary border-secondary-subtle',
                                            'completed' => 'bg-success-subtle text-success border-success-subtle',
                                            'cancelled' => 'bg-danger-subtle text-danger border-danger-subtle',
                                            'refunded' => 'bg-dark-subtle text-dark border-dark-subtle',
                                            default => 'bg-light text-dark border-light-subtle',
                                        };
                                    @endphp
                                    <span class="badge border px-2 py-1 text-capitalize {{ $statusClass }}">
                                        <!-- str_replace થી અંડરસ્કોર(_) કાઢીને સ્પેસ મુકાશે, દા.ત. in_progress -> In Progress -->
                                        {{ ucwords(str_replace('_', ' ', $task->status ?? 'Open')) }}
                                    </span>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-4 fw-medium text-secondary">Department:</div>
                                <div class="col-8 text-dark">{{ $task?->department?->name ?? 'None' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-4 fw-medium text-secondary">Assigned To:</div>
                                <div class="col-8 text-dark">{{ $task?->assign?->name ?? 'Unassigned' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-4 fw-medium text-secondary">Create Date:</div>
                                <div class="col-8 text-dark">
                                    {{ \Carbon\Carbon::parse($task->created_at)->format('d M Y, h:i A') }}
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-4 fw-medium text-secondary">Due Date:</div>
                                <div class="col-8 text-dark">
                                    {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Product/Service Details Column -->
                <div class="col-md-6">
                    <div class="card shadow-none border h-100 mb-0">
                        <div class="card-body p-3">
                            @php
                                // Dynamic Title and Icon based on product_type
                                $isService = strtolower($task->product_type ?? '') === 'service';
                                $boxTitle = $isService ? 'Service Details' : 'Product Details';
                                $boxIcon = $isService ? 'ti-briefcase' : 'ti-box';
                            @endphp
                            <h6 class="fs-14 fw-bold mb-3 text-dark border-bottom pb-2">
                                <i class="ti {{ $boxIcon }} me-1 text-primary"></i> {{ $boxTitle }}
                            </h6>

                            <div class="row mb-2">
                                <div class="col-4 fw-medium text-secondary">Type:</div>
                                <div class="col-8 text-dark text-capitalize">{{ $task->product_type ?? 'N/A' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-4 fw-medium text-secondary">Name:</div>
                                <div class="col-8 text-dark fw-semibold">{{ $task->product_name ?? 'N/A' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-4 fw-medium text-secondary">Variant:</div>
                                <div class="col-8 text-dark">{{ $task->variant_name ?? 'N/A' }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-4 fw-medium text-secondary">Quantity:</div>
                                <div class="col-8 text-dark">{{ number_format($task->quantity ?? 0, 2) }}</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-4 fw-medium text-secondary">Price:</div>
                                <div class="col-8 text-dark fw-semibold">${{ number_format($task->price ?? 0, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card shadow-none border h-100 mb-0">
                        <div class="card-body p-3">
                            @php
                                // Dynamic Title and Icon based on product_type
                                $isService = strtolower($task->product_type ?? '') === 'service';
                                $boxTitle = $isService ? 'Service Details' : 'Product Details';
                                $boxIcon = $isService ? 'ti-briefcase' : 'ti-box';
                            @endphp
                            <h6 class="fs-14 fw-bold mb-3 text-dark border-bottom pb-2">
                                <i class="ti ti-clipboard-text me-1 text-primary"></i> Description
                            </h6>

                            <div class="col-12 text-dark bg-light p-2 rounded border"
                                style="min-height: 52px; font-size: 13px;">
                                {!! $task->description ?? 'No description provided.' !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="desc-note-section">
                    @if(isset($task) && isset($task->notes) && $task->notes->isNotEmpty())
                        <div class="position-relative ps-5">
                            <!-- Dotted vertical timeline line -->
                            <div class="position-absolute start-0 top-0 bottom-0 ms-3 border-start border-2 border-dashed border-secondary-subtle"
                                style="left: 0.25rem;"></div>

                            @foreach($task->notes as $note)
                                @php
                                    $isInternal = $note->ref_type === 'internal_note';
                                    $headerBg = $isInternal ? '#fefce8' : '#fff7ed';
                                    $borderColor = $isInternal ? '#fef08a' : '#ffedd5';
                                @endphp
                                <div class="position-relative mb-4">
                                    <!-- Avatar icon on left of card -->
                                    <span
                                        class="position-absolute rounded-circle bg-light border border-2 d-flex align-items-center justify-content-center shadow-sm"
                                        style="left: -3.2rem; top: 0.5rem; width: 2.5rem; height: 2.5rem; border-color: #dee2e6 !important;">
                                        <i class="ti ti-user fs-16 text-muted"></i>
                                    </span>

                                    <div class="card border mb-0 shadow-sm"
                                        style="border-color: {{ $borderColor }} !important;">
                                        <div class="card-header d-flex justify-content-between align-items-center py-2 px-3 border-bottom"
                                            style="background-color: {{ $headerBg }}; border-color: {{ $borderColor }} !important;">
                                            <div class="d-flex align-items-center flex-wrap gap-1">
                                                <span class="fw-bold text-dark me-1">{{ $note->user?->name ?? 'System' }}</span>
                                                <span class="text-muted me-1">posted</span>
                                                <span
                                                    class="text-secondary-emphasis fw-medium">{{ \Carbon\Carbon::parse($note->datetime)->format('m/d/y g:i A') }}</span>
                                                @if($isInternal)
                                                    <span
                                                        class="text-warning-emphasis bg-warning-subtle border border-warning-subtle rounded px-2 py-0.5 ms-2"
                                                        style="font-size: 11px;">internal</span>
                                                    @if(!empty($note->title))
                                                        <span class="ms-2 fw-semibold text-dark">{{ $note->title }}</span>
                                                    @endif
                                                @endif
                                            </div>
                                            {{-- <div class="dropdown">
                                                <button class="btn btn-sm btn-icon btn-link text-muted p-0" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="ti ti-chevron-down fs-15"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#">View Details</a></li>
                                                </ul>
                                            </div> --}}
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
                    action="{{ route('admin.tasks.storeReply', encrypt($task->id)) }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="reply-description-input">Response Description <span
                                        class="text-danger">*</span></label>

                                <input type="hidden" name="description" id="reply-description-input" value="">

                                <div class="reply-description-editor" id="reply-description-editor"
                                    style="height: 200px;"></div>

                                @error('description')
                                    <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4 align-items-center">
                        <label for="reply_ticket_status" class="col-md-2 col-form-label fw-bold text-dark">Task
                            Status:</label>
                        <div class="col-md-3">
                            <select class="form-select addSelect2" name="ticket_status" id="reply_ticket_status">
                                <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="assigned" {{ $task->status == 'assigned' ? 'selected' : '' }}>Assigned
                                </option>
                                <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In
                                    Progress</option>
                                <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed
                                </option>
                                <option value="cancelled" {{ $task->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                                </option>
                                <option value="refund" {{ $task->status == 'refund' ? 'selected' : '' }}>Refund</option>
                                <option value="on_hold" {{ $task->status == 'on_hold' ? 'selected' : '' }}>On Hold
                                </option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>

                <form style="display: none;" class="ticket-post-internal-note tabCustomHide" id="replyInternalForm"
                    action="{{ route('admin.tasks.storeInternalNote', encrypt($task->id)) }}" method="POST">
                    @csrf
                    <div class="row mb-3 align-items-center mt-3">
                        <label for="from_email" class="col-md-2 col-form-label fw-bold text-dark">Internal Note:</label>
                        <div class="col-md-5">
                            <label>Note title - summary of the note (optional)</label>
                            <input type="text" class="form-control" name="internal_note_title" value="">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <input type="hidden" name="internal_note" id="reply-internal-note-input" value="">
                                <div class="reply-internal-note-editor" id="reply-internal-note-editor"
                                    style="height: 200px;"></div>
                                @error('internal_note')
                                    <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4 align-items-center">
                        <label for="reply_internal_ticket_status" class="col-md-2 col-form-label fw-bold text-dark">Task
                            Status:</label>
                        <div class="col-md-3">
                            <select class="form-select addSelect2" name="ticket_status"
                                id="reply_internal_ticket_status">
                                <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="assigned" {{ $task->status == 'assigned' ? 'selected' : '' }}>Assigned
                                </option>
                                <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In
                                    Progress</option>
                                <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed
                                </option>
                                <option value="cancelled" {{ $task->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                                </option>
                                <option value="refund" {{ $task->status == 'refund' ? 'selected' : '' }}>Refund</option>
                                <option value="on_hold" {{ $task->status == 'on_hold' ? 'selected' : '' }}>On Hold
                                </option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="task-edit tabHide" id="task-edit" style="display: {{ $tab != 'task-edit' ? 'none' : '' }}">
            <form id="taskForm"
                action="{{ !empty($task) ? route('admin.tasks.update', encrypt($task->id)) : route('admin.tasks.store') }}"
                method="post" enctype="multipart/form-data">
                @csrf
                @if (!empty($task))
                    @method('PUT')
                @endif

                @php
                    if (empty($task_number) && !isset($task_number)) {
                        $task_number = $task->task_number;
                    }

                    $product_type = 'product';
                    if (!empty($task->product_type) && $task->product_type == 'product') {
                        $product_type = 'product';
                    } else if (!empty($task->product_type) && $task->product_type == 'service') {
                        $product_type = 'service';
                    }
                @endphp

                <input type="hidden" name="product_type" value="{{ $product_type }}">

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
                            <div class="input-group mb-1">
                                <input type="text" class="form-control" name="title" id="title" placeholder="Title"
                                    value="{{ old('title', $task->title ?? '') }}">
                            </div>
                            @error('title')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6" id="dy-1">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label mb-0" id="main_label">Products <span
                                        class="text-danger">*</span></label>
                                <a href="javascript:void(0);" id="toggle_type" data-type="{{ $product_type }}"
                                    class="text-primary text-decoration-none small">Switch to Services</a>
                            </div>

                            <div id="product_section">
                                <div class="input-group mb-1">
                                    <select class="form-select" name="product_id" id="product_id">
                                        <option value="">Select Product...</option>
                                        @if (!empty($products))
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" {{ old('product_id', $task->product_id ?? '') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                @error('product_id')
                                    <span class="text-danger small product_error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div id="service_section" style="display: none;">
                                <div class="input-group mb-1">
                                    <select class="form-select" name="service_id" id="service_id">
                                        <option value="">Select Service...</option>
                                        @if (!empty($services))
                                            @foreach ($services as $service)
                                                <option value="{{ $service->id }}" {{ old('service_id', $task->product_id ?? '') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                @error('service_id')
                                    <span class="text-danger small service_error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div id="service_variant_section" style="display: none; margin-top: 10px;">
                                <input type="hidden" name="is_variant" value="0">
                                <div class="input-group mb-1">
                                    <select class="form-select" name="service_variant_id" id="service_variant_id">
                                        <option value="">Select Variant...</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label" for="task_number">Task Number <span
                                    class="text-danger">*</span></label>
                            <div class="input-group mb-1">
                                <input type="text" class="form-control" name="task_number" id="task_number"
                                    placeholder="Task Number" value="{{ !empty($task_number) ? $task_number : ''  }}"
                                    readonly>
                            </div>
                            @error('task_number')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label" for="ticket_id">Ticket No.</label>
                            <select class="form-select addSelect2 select2" name="ticket_id" id="ticket_id">
                                <option value="">Select Ticket</option>
                                @if (isset($tickets) && count($tickets) > 0)
                                    @foreach ($tickets as $t)
                                        <option value="{{ $t->id }}" {{ old('ticket_id', $task->ticket_id ?? '') == $t->id ? 'selected' : '' }}>
                                            {{ $t->ticket_number }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('ticket_id')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Due Date:</label>
                            <div class="input-group mb-1">
                                <input name="due_date" type="date" class="form-control w-auto"
                                    value="{{ !empty($task?->due_date) ? \Carbon\Carbon::parse($task?->due_date)->format('Y-m-d') : now()->format('Y-m-d') }}">
                            </div>
                            @error('due_date')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label" for="assign_id">Assign <span class="text-danger">*</span></label>
                            <select class="form-select select2" name="assign_id" id="assign_id">
                                <option value="">Select User</option>
                                @if (isset($developers) && count($developers) > 0)
                                    @foreach ($developers as $developer)
                                        <option value="{{ $developer->id }}" {{ old('assign_id', $task->assign_id ?? '') == $developer->id ? 'selected' : '' }}>
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

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label" for="department_id">Department <span
                                    class="text-danger">*</span></label>
                            <select class="form-select select2 addSelect2" name="department_id" id="department_id">
                                <option value="">Select Department</option>
                                @if (isset($departments) && count($departments) > 0)
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id', $task->department_id ?? '') == $department->id ? 'selected' : '' }}>
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
                            <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
                            @php $currentStatus = old('status', $task->status ?? ''); @endphp
                            <select class="form-select select2" name="status" id="status">
                                <option value="">Select Status</option>
                                <option value="pending" {{ $currentStatus == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                {{-- <option value="assign_requested" {{ $currentStatus=='assign_requested' ? 'selected'
                                    : '' }}>Assign Requested</option> --}}
                                <option value="assigned" {{ $currentStatus == 'assigned' ? 'selected' : '' }}>Assigned
                                </option>
                                {{-- <option value="assign_not_accepted" {{ $currentStatus=='assign_not_accepted'
                                    ? 'selected' : '' }}>Assign Not Accepted</option> --}}
                                <option value="in_progress" {{ $currentStatus == 'in_progress' ? 'selected' : '' }}>In
                                    Progress</option>
                                <option value="completed" {{ $currentStatus == 'completed' ? 'selected' : '' }}>Completed
                                </option>
                                {{-- <option value="cancel_requested" {{ $currentStatus=='cancel_requested' ? 'selected'
                                    : '' }}>Cancel Requested</option> --}}
                                <option value="cancelled" {{ $currentStatus == 'cancelled' ? 'selected' : '' }}>Cancelled
                                </option>
                                <option value="refund" {{ $currentStatus == 'refund' ? 'selected' : '' }}>Refund</option>
                            </select>
                            @error('status')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12" id="cancel_reason_section" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label" for="cancel_reason">Cancel Reason <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" name="cancel_reason" id="cancel_reason" rows="3"
                                placeholder="Cancel Reason">{{ old('cancel_reason', $task->cancel_reason ?? '') }}</textarea>
                            @error('cancel_reason')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label" for="description">Description <span
                                    class="text-danger">*</span></label>

                            <input type="hidden" name="description" id="description"
                                value="{{ old('description', $task->description ?? '') }}">

                            <div id="quill-editor" style="height: 200px;">
                                {!! old('description', $task->description ?? '') !!}
                            </div>

                            @error('description')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                </div>

                <hr>

                <div class="text-end mt-3">
                    <a href="{{ route($moduleUrl ?? 'admin.tasks.index') }}" class="btn btn-soft-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>

        <div class="task-custom-field tabHide" id="task-custom-field"
            style="display: {{ $tab != 'task-custom-field' ? 'none' : '' }}">
            <div class="row">
                @if(isset($customfields) && count($customfields) > 0)
                    @foreach($customfields as $field)
                        @php
                            $params = is_string($field->params) ? json_decode($field->params, true) : $field->params;
                            $inputType = $field->fieldType->key ?? 'text';
                        @endphp
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ $field->name }}</label>
                                @if($inputType == 'textarea')
                                    <textarea class="form-control" placeholder="{{ $params['placeholder'] ?? '' }}"
                                        disabled>{{ $params['default_value'] ?? '' }}</textarea>
                                @else
                                    <input type="{{ $inputType == 'number' ? 'number' : 'text' }}" class="form-control"
                                        placeholder="{{ $params['placeholder'] ?? '' }}"
                                        value="{{ $params['default_value'] ?? '' }}" disabled>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-12">
                        <p class="text-muted mb-0">No custom fields found.</p>
                    </div>
                @endif
            </div>
        </div>

    </x-form-wrapper>

    @push('scripts')
        <script>
            $(document).ready(function () {

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

                var quillTicketEdit = new Quill('#reply-description-editor', {
                    theme: 'snow',
                    readOnly: {{ !empty($disabled) ? 'true' : 'false' }},
                    modules: {
                        toolbar: toolbarOptions
                    }
                });

                quillTicketEdit.on('text-change', function () {
                    var html = quillTicketEdit.root.innerHTML;
                    $('#reply-description-input').val(html);
                });

                var quillInternalNoteEdit = new Quill('#reply-internal-note-editor', {
                    theme: 'snow',
                    readOnly: {{ !empty($disabled) ? 'true' : 'false' }},
                    modules: {
                        toolbar: toolbarOptions
                    }
                });

                quillInternalNoteEdit.on('text-change', function () {
                    var html = quillInternalNoteEdit.root.innerHTML;
                    $('#reply-internal-note-input').val(html);
                });

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
                    $('.custom-nav-link-edit').removeClass('active');
                    $(this).addClass('active');
                    $('.tabCustomHide').hide();
                    $(`.${tab}`).show();
                });

                let currentType = "{{ $product_type == 'service' ? 'service' : 'product' }}";
                let variantId = "{{ !empty($task->variant_id) ? $task->variant_id : '' }}";

                function applyTypeSelection() {
                    var t = $('#toggle_type').data('type');

                    if (currentType === 'service') {
                        $('#main_label').html('Services <span class="text-danger">*</span>');
                        $('#toggle_type').text('Switch to Products');
                        $('#product_section').hide();
                        $('.product_error').hide();
                        $('#service_section').show();
                        $('#service_variant_section').show();
                        $('#service_variant_id').html('<option value="">Select Variant...</option>');
                    } else {
                        $('#main_label').html('Products <span class="text-danger">*</span>');
                        $('#toggle_type').text('Switch to Services');
                        $('#service_section').hide();
                        $('.service_error').hide();
                        $('#product_section').show();
                        $('#service_variant_section').hide();
                        $('#service_variant_id').html('<option value="">Select Variant...</option>');
                    }
                }

                applyTypeSelection();

                $('#toggle_type').click(function () {
                    var Type = $(this).data('type');

                    if (Type == 'service') {
                        $(this).data('type', 'product');
                        $('#main_label').html('Products <span class="text-danger">*</span>');
                        $('#toggle_type').text('Switch to Services');
                        $('#service_section').hide();
                        $('.service_error').hide();
                        $('#product_section').show();
                        $('#service_variant_section').show();
                        $('#service_variant_id').html('<option value="">Select Variant...</option>');
                        $('#service_id').val('').trigger('change');
                        $('#product_id').val('').trigger('change');
                    } else if (Type == 'product') {
                        $(this).data('type', 'service');
                        $('#main_label').html('Services <span class="text-danger">*</span>');
                        $('#toggle_type').text('Switch to Products');
                        $('#product_section').hide();
                        $('.product_error').hide();
                        $('#service_section').show();
                        $('#service_variant_section').hide();
                        $('#service_variant_id').html('<option value="">Select Variant...</option>');
                        $('#product_id').val('').trigger('change');
                        $('#service_id').val('').trigger('change');
                    }

                    $('input[name="product_type"]').val($(this).data('type'));

                });

                function toggleCancelReason() {
                    let status = $('#status').val();
                    if (status === 'cancelled' || status === 'cancel_requested') {
                        $('#cancel_reason_section').slideDown();
                    } else {
                        $('#cancel_reason_section').slideUp();
                        // $('#cancel_reason').val('');
                    }
                }

                toggleCancelReason();

                $('#developer_id').select2({ placeholder: 'Select a developer', allowClear: true });
                $('#user_id').select2({ placeholder: 'Select a user', allowClear: true });
                $('#status').select2({ placeholder: 'Select a status', allowClear: true });
                $('#assign_id').select2({ placeholder: 'Select a user', allowClear: true });

                $('#product_id').select2({ placeholder: 'Select a product', allowClear: true });
                $('#service_id').select2({ placeholder: 'Select a service', allowClear: true });

                $('#status').on('change', function () {
                    toggleCancelReason();
                    $(this).valid();
                });

                $('#user_id, #developer_id').on('change', function () {
                    $(this).valid();
                });

                $('#service_id').on('change', function () {
                    let serviceId = $(this).val();
                    let variantSelect = $('#service_variant_id');
                    let variantSection = $('#service_variant_section');

                    // Reset the variant dropdown
                    variantSelect.html('<option value="">Select Variant...</option>');

                    if (serviceId) {
                        // Show loading text while fetching
                        variantSelect.html('<option value="">Loading variants...</option>');
                        variantSection.show();

                        $.ajax({
                            // Ensure this script is inside a blade file for the route() helper to work
                            url: "{{ route('admin.tickets.get.service.variant') }}",
                            type: "POST",
                            data: {
                                service_id: serviceId,
                                // variant_id: variantId
                            },
                            success: function (response) {
                                if (response.success === 1 && response.variants.length > 0) {
                                    $('input[name="is_variant"]').val(1);
                                    let options = '<option value="">Select Variant...</option>';

                                    // Loop through returned variants and append to options string
                                    $.each(response.variants, function (index, variant) {
                                        // Assuming your variants table has 'id' and 'name' columns.
                                        // Adjust 'variant.name' if your column is called something else (e.g., 'title')
                                        var selected = variantId == variant.id ? 'selected' : '';
                                        options += `<option value="${variant.id}" ${selected} >${variant.name}</option>`;
                                    });

                                    variantSelect.html(options);
                                } else {
                                    console.log('test');

                                    $('input[name="is_variant"]').val(0);
                                    variantSelect.html('<option value="">No variants found</option>');
                                }
                            },
                            error: function (xhr) {
                                console.error("An error occurred while fetching variants.");
                                variantSelect.html('<option value="">Error loading variants</option>');
                            }
                        });
                    } else {
                        variantSection.hide();
                    }
                });

                if (variantId) {
                    $('#service_id').trigger('change');
                } else {
                    $('input[name="is_variant"]').val(0);
                }

                var validator = $('#taskForm').validate({
                    rules: {
                        title: {
                            required: true
                        },
                        task_number: {
                            required: true,
                            maxlength: 255
                        },
                        product_id: {
                            required: function (element) {
                                return $('input[name="product_type"]').val() === 'product';
                            }
                        },
                        service_id: {
                            required: function (element) {
                                return $('input[name="product_type"]').val() === 'service';
                            }
                        },
                        service_variant_id: {
                            required: function (element) {
                                return $('input[name="is_variant"]').val() == 1 ? true : false;
                            }
                        },
                        assign_id: {
                            required: true
                        },
                        status: {
                            required: true
                        },
                        cancel_reason: {
                            required: function (element) {
                                let st = $('#status').val();
                                return (st === 'cancelled' || st === 'cancel_requested');
                            }
                        },
                        description: { required: true },
                    },
                    messages: {
                        task_number: { required: "Please enter a ticket number." },
                        product_id: { required: "Please select a product." },
                        service_id: { required: "Please select a service." },
                        status: { required: "Please select a status." },
                        cancel_reason: { required: "Please provide a reason for cancellation." },
                        description: { required: "Please provide a detailed description." },
                    },
                    errorClass: 'text-danger small mt-1',
                    errorElement: 'span',
                    ignore: ":hidden:not(.select2-hidden-accessible, #description)",
                    highlight: function (element) {
                        $(element).addClass('is-invalid');
                        if ($(element).hasClass("select2-hidden-accessible")) {
                            $(element).next('.select2-container').find('.select2-selection').addClass('border-danger');
                        }
                    },
                    unhighlight: function (element) {
                        $(element).removeClass('is-invalid');
                        if ($(element).hasClass("select2-hidden-accessible")) {
                            $(element).next('.select2-container').find('.select2-selection').removeClass('border-danger');
                        }
                    },
                    errorPlacement: function (error, element) {
                        if (element.hasClass('select2-hidden-accessible')) {
                            error.insertAfter(element.next('.select2-container'));
                        } else if (element.attr('id') === 'description') {
                            error.insertAfter('#quill-editor');
                        } else if (element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else if (element.prop('type') === 'radio') {
                            error.insertAfter(element.closest('.d-flex'));
                        } else {
                            error.insertAfter(element);
                        }
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

                quill.on('text-change', function () {
                    var html = quill.root.innerHTML;
                    if (quill.getText().trim().length === 0) {
                        html = '';
                    }
                    $('#description').val(html);

                    validator.element('#description');
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
                    highlight: function (element) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function (element) {
                        $(element).removeClass('is-invalid');
                    },
                    errorPlacement: function (error, element) {
                        error.insertAfter(element.closest('.mb-3'));
                    },
                    submitHandler: function (form) {
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
                    highlight: function (element) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function (element) {
                        $(element).removeClass('is-invalid');
                    },
                    errorPlacement: function (error, element) {
                        error.insertAfter(element.closest('.mb-3'));
                    },
                    submitHandler: function (form) {
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
    @endpush

</x-master-layout>