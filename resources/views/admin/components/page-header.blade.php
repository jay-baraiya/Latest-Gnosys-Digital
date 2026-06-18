@props(['title', 'badge' => null])

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
    <div>
        <h4 class="mb-1">{{ $title }}<span class="badge badge-soft-primary ms-2 record-count">0</span></h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                @isset($breadcrumbs)
                    {{ $breadcrumbs }}
                @endisset
            </ol>
        </nav>
    </div>
    <div class="gap-2 d-flex align-items-center flex-wrap">
        @isset($actions)
            {{ $actions }}
        @endisset
    </div>
</div>
<!-- End Page Header -->
