<!-- card start -->
<div {{ $attributes->merge(['class' => 'card border-0 rounded-0']) }}>

    @isset($filters)
        <div class="card-header d-flex align-items-center gap-2 flex-wrap">
            {{ $filters }}
        </div>
    @endisset
    @isset($header)
        <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
            {{ $header }}
        </div>
    @endisset

    <div class="card-body">
        {{ $slot }}
    </div>
</div>
<!-- card end -->
