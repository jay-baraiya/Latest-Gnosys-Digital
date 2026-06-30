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
    'is_permission' => false,
])

@php
    $routeName = request()->route()->getName();

    $parts = explode('.', $routeName);

    $module = implode('.', array_slice($parts, 1, -1));

    $role = auth()->user()?->role?->id ?? null;
@endphp

<div class="dropdown table-action">
    <a href="#" class="action-icon btn btn-xs shadow btn-icon btn-outline-light" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical"></i>
    </a>

    <div class="dropdown-menu dropdown-menu-end">
        @if ($is_approved == 0)
            @can('approve.' . $module)
                {{-- Approve --}}
                @if ($approve)
                    <a class="dropdown-item wallet-action-btn" href="{{ $approve }}" data-action="approve">
                        <i class="ti ti-check text-success"></i> Approve
                    </a>
                @endif
            @endcan

            @can('reject.' . $module)
                {{-- Reject --}}
                @if ($reject)
                    <a class="dropdown-item wallet-action-btn" href="{{ $reject }}" data-action="reject">
                        <i class="ti ti-x text-danger"></i> Reject
                    </a>
                @endif
            @endcan
        @endif

        @if ($is_approved == 2)
            {{-- @can('reject.' . $module) --}}
                {{-- Reapprove --}}
                @if ($reapprove)
                    <a class="dropdown-item wallet-action-btn" href="{{ $reapprove }}" data-action="reapprove" >
                        <i class="ti ti-circle-dashed-check text-info"></i> Reapprove
                    </a>
                @endif
            {{-- @endcan --}}
        @endif

        @if ($role == auth()->user()::IS_ADMIN)
            {{-- @can('edit.' . $module) --}}
                {{-- Edit --}}
                @if ($is_permission)
                    <a class="dropdown-item" href="{{ $is_permission }}">
                        <i class="ti ti-shield-cog text-danger"></i> Permissions
                    </a>
                @endif
            {{-- @endcan --}}
        @endif

        @can('edit.' . $module)
            {{-- Edit --}}
            @if ($edit && !$is_deleted)
                <a class="dropdown-item" href="{{ $edit }}">
                    <i class="ti ti-edit text-warning"></i> Edit
                </a>
            @endif
        @endcan

        @can('view.' . $module)
            {{-- Show --}}
            @if ($show && !$is_deleted)
                <a class="dropdown-item" href="{{ $show }}">
                    <i class="ti ti-eye text-info"></i> View
                </a>
            @endif
        @endcan

        @can('view.' . $module)
            {{-- History --}}
            @if ($history)
                <a class="dropdown-item showWalletHistory" href="{{ $history }}">
                    <i class="ti ti-history text-warning"></i> Transaction History
                </a>
            @endif
        @endcan

        @can('restore.' . $module)
            {{-- Restore --}}
            @if ($restore && $is_deleted)
                <a class="dropdown-item" href="{{ $restore }}" id="restore_action">
                    <i class="ti ti-restore text-success"></i> Restore
                </a>
            @endif
        @endcan

        @can('delete.' . $module)
            {{-- Delete --}}
            @if ($delete)
                <a class="dropdown-item" href="{{ $delete }}" id="delete_action">
                    <i class="ti ti-trash text-danger"></i> Delete
                </a>
            @endif
        @endcan

    </div>
</div>
