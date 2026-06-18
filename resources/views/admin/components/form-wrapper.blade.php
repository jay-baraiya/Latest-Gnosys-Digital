@props(['action' => 'Create'])
<div class="content pb-0">

    <!-- Page Header -->
    <div class="mb-4">
        {{-- <h4 class="mb-1">Create {{ $moduleName }}</h4> --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route($moduleUrl) }}">{{ $moduleName }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $action }} {{ $moduleName }}</li>
            </ol>
        </nav>
    </div>
    <!-- End Page Header -->

    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    {{ $slot }}

                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div> <!-- end col -->

    </div>

</div>
