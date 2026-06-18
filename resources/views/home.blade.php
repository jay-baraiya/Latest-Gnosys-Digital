<x-master-layout>
    <!-- Start Content -->
    <div class="content pb-0">

        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
            <div>
                <h4 class="mb-0">Dashboard</h4>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- start row -->
        <div class="row">

            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-body position-relative">
                        <p class="fw-medium mb-1">Users</p>
                        <h4 class="mb-3">{{ $count['user_count'] }}</h4>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            {{-- <span class="d-inline-flex align-items-center badge rounded-pill badge-soft-success border-0">+2.5%</span> --}}
                            {{-- <p class="text-dark mb-0">User Total</p> --}}
                        </div>
                        <div class="custom-card-icon">
                            <div
                                class="avatar avatar-rounded avatar-lg bg-primary-gradient-100 position-absolute top-0 end-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M5 7a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->

            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-body position-relative">
                        <p class="fw-medium mb-1">Digital Products</p>
                        <h4 class="mb-3">{{ $count['digital_product_count'] }}</h4>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            {{-- <span class="d-inline-flex align-items-center badge rounded-pill badge-soft-danger border-0">-21.15%</span> --}}
                            {{-- <p class="text-dark mb-0">Digital Products Total</p> --}}
                        </div>
                        <div class="custom-card-icon">
                            <div
                                class="avatar avatar-rounded avatar-lg bg-info-gradient-100 position-absolute top-0 end-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-producthunt"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M10 16v-8h2.5a2.5 2.5 0 1 1 0 5h-2.5" /><path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->

            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-body position-relative">
                        <p class="fw-medium mb-1">Digital Services</p>
                        <h4 class="mb-3">{{ $count['digital_service_count'] }}</h4>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            {{-- <span class="d-inline-flex align-items-center badge rounded-pill badge-soft-success border-0">+15.5%</span> --}}
                            {{-- <p class="text-dark mb-0">From Last Week</p> --}}
                        </div>
                        <div class="custom-card-icon">
                            <div
                                class="avatar avatar-rounded avatar-lg bg-pink-gradient-100 position-absolute top-0 end-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-world-cog"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M21 12a9 9 0 1 0 -8.979 9" /><path d="M3.6 9h16.8" /><path d="M3.6 15h8.9" /><path d="M11.5 3a17 17 0 0 0 0 18" /><path d="M12.5 3a16.992 16.992 0 0 1 2.522 10.376" /><path d="M17.001 19a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M19.001 15.5v1.5" /><path d="M19.001 21v1.5" /><path d="M22.032 17.25l-1.299 .75" /><path d="M17.27 20l-1.3 .75" /><path d="M15.97 17.25l1.3 .75" /><path d="M20.733 20l1.3 .75" /></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-body position-relative">
                        <p class="fw-medium mb-1">Blogs</p>
                        <h4 class="mb-3">{{ $count['blog_count'] }}</h4>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            {{-- <span class="d-inline-flex align-items-center badge rounded-pill badge-soft-success border-0">+15.5%</span> --}}
                            {{-- <p class="text-dark mb-0">From Last Week</p> --}}
                        </div>
                        <div class="custom-card-icon">
                            <div
                                class="avatar avatar-rounded avatar-lg bg-success-gradient position-absolute top-0 end-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-article"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M3 6a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2l0 -12" /><path d="M7 8h10" /><path d="M7 12h10" /><path d="M7 16h10" /></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>


    </div>
    <!-- End Content -->

    <!-- Start Footer -->
    <footer class="footer d-block d-md-flex justify-content-between text-md-start text-center">
        <p class="mb-md-0 mb-1">Copyright &copy;
            <script type="3dfcc64020bcb2b8aa577a50-text/javascript">document.write(new Date().getFullYear())</script>
            <a href="https://gnosysdigital.com" target="_blank" class="link-primary text-decoration-underline">Gnosys Digital</a>
        </p>
    </footer>
    <!-- End Footer -->
</x-master-layout>
