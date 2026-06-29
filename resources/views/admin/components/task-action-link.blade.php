@props([
    'edit' => null,
    'show' => null,
    'delete' => null,
    'restore' => null,
    'id' => null,
    'is_deleted' => 0,
    'history' => null,
    'is_approved' => 0,
    'approve' => null,
    'reject' => null,
    'reapprove' => null,
    'show_status_options' => true,
    'current_status' => null
])

@php
    $routeName = request()->route()->getName();
    $parts = explode('.', $routeName);
    $module = implode('.', array_slice($parts, 1, -1));

    $statuses = [
        'pending' => ['label' => 'Pending', 'icon' => 'ti ti-clock text-warning'],
        // 'assign_requested' => ['label' => 'Assign Requested', 'icon' => 'ti ti-user-plus text-info'],
        'assigned' => ['label' => 'Assigned', 'icon' => 'ti ti-user-check text-primary'],
        // 'assign_not_accepted' => ['label' => 'Assign Not Accepted', 'icon' => 'ti ti-user-x text-danger'],
        'in_progress' => ['label' => 'In Progress', 'icon' => 'ti ti-loader text-info'],
        'completed' => ['label' => 'Completed', 'icon' => 'ti ti-circle-check text-success'],
        // 'cancel_requested' => ['label' => 'Cancel Requested', 'icon' => 'ti ti-alert-circle text-warning'],
        'closed' => ['label' => 'Closed', 'icon' => 'ti ti-circle-x text-danger'],
        // 'refund' => ['label' => 'Refund', 'icon' => 'ti ti-receipt-refund text-secondary'],
    ];

    $allowedStatuses = [];

    if ($current_status === 'pending') {
        $allowedStatuses = array_keys($statuses);
    } elseif ($current_status === 'assigned' || $current_status === 'in_progress') {
        $allowedStatuses = ['in_progress', 'completed', 'cancel_requested', 'refund'];
    } else {
        $allowedStatuses = array_keys($statuses);
    }
@endphp

<div class="dropdown table-action">
    <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical"></i>
    </a>

    <div class="dropdown-menu dropdown-menu-end">

        @can('edit.' . $module)
            @if ($edit && !$is_deleted)
                <a class="dropdown-item" href="{{ $edit }}">
                    <i class="ti ti-edit text-warning"></i> Edit
                </a>
            @endif
        @endcan

        @can('view.' . $module)
            @if ($show && !$is_deleted)
                <a class="dropdown-item" href="{{ $show }}">
                    <i class="ti ti-eye text-info"></i> View
                </a>
            @endif
        @endcan

        @can('delete.' . $module)
            @if ($delete)
                <a class="dropdown-item" href="{{ $delete }}" id="delete_action">
                    <i class="ti ti-trash text-danger"></i> Delete
                </a>
            @endif
        @endcan

        {{-- Status Updates --}}
        @if($show_status_options && $id)
            <div class="dropdown-divider"></div>
            <h6 class="dropdown-header">Update Status</h6>

            @foreach($statuses as $statusValue => $statusData)
                @if(in_array($statusValue, $allowedStatuses) && $current_status !== $statusValue)
                    <a class="dropdown-item change-status-btn" href="javascript:void(0)" data-id="{{ $id }}" data-status="{{ $statusValue }}">
                        <i class="{{ $statusData['icon'] }}"></i> {{ $statusData['label'] }}
                    </a>
                @endif
            @endforeach
        @endif

    </div>
</div>
