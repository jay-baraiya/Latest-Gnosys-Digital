@php
    $currentRoute = request()->route()->getName();
@endphp

<div class="sidebar" id="sidebar">

    <!-- Start Logo -->
    <div class="sidebar-logo">
        <div>
            <!-- Logo Normal -->
            <a href="{{ route('admin.dashboard') }}" class="logo logo-normal">
                <img src="{{ asset('assets/img/Ginosys-Digital-logo.webp') }}" width="100" height="30" alt="Logo">
            </a>

            <!-- Logo Small -->
            <a href="{{ route('admin.dashboard') }}" class="logo-small">
                <img src="{{ asset('assets/img/cropped-Ginosys-Digital-32x32.png') }}" width="40" height="30" alt="Logo">
            </a>

            <!-- Logo Dark -->
            <a href="{{ route('admin.dashboard') }}" class="dark-logo">
                <img src="{{ asset('assets/img/Ginosys-Digital-logo.webp') }}" width="100" height="30" alt="Logo">
            </a>
        </div>
        <button class="sidenav-toggle-btn btn border-0 p-0 active" id="toggle_btn">
            <i class="ti ti-arrow-bar-to-left"></i>
        </button>

        <!-- Sidebar Menu Close -->
        <button class="sidebar-close">
            <i class="ti ti-x align-middle"></i>
        </button>
    </div>
    <!-- End Logo -->

    <!-- Sidenav Menu -->
    <div class="sidebar-inner" data-simplebar>
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                {{-- <li class="menu-title"><span>Main Menu</span></li> --}}
                <li>
                    <ul>
                        <li class="{{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="ti ti-dashboard"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    </ul>
                </li>

                @canany(['view.users','view.roles'])
                    <li class="menu-title"><span>User Management</span></li>
                    <li>
                        <ul>
                            @can('view.users')
                                <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.users.index') }}">
                                        <i class="ti ti-users"></i>
                                        <span>Manage Users</span>
                                    </a>
                                </li>
                            @endcan

                            @can('view.roles')
                                <li class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.roles.index') }}">
                                        <i class="ti ti-user-shield"></i>
                                        <span>Roles & Permissions</span>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcanany

                @can('view.categories')
                    <li class="menu-title"><span>Category Management</span></li>
                    <li>
                        <ul>
                            <li class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.categories.index') }}">
                                    <i class="ti ti-category"></i>
                                    <span>Categories</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @canany(['view.digital.products','view.digital.services'])
                    <li class="menu-title"><span>Products & Services</span></li>
                    <li>
                        <ul>
                            @can('view.digital.products')
                                <li class="{{ request()->routeIs('admin.digital.products.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.digital.products.index') }}">
                                        <i class="ti ti-category"></i>
                                        <span>Digital Products</span>
                                    </a>
                                </li>
                            @endcan

                            @can('view.digital.services')
                                <li class="{{ request()->routeIs('admin.digital.services.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.digital.services.index') }}">
                                        <i class="ti ti-category"></i>
                                        <span>Digital Services</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @can('view.blogs')
                    <li class="menu-title"><span>Blog Management</span></li>
                    <li>
                        <ul>
                            <li class="{{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.blogs.index') }}">
                                    <i class="ti ti-category"></i>
                                    <span>Blogs</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @can('view.wallets')
                    <li class="menu-title"><span>Wallet Management</span></li>
                    <li>
                        <ul>
                            <li class="{{ request()->routeIs('admin.wallets.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.wallets.index') }}">
                                    <i class="ti ti-category"></i>
                                    <span>Wallets</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

            </ul>
        </div>
    </div>

</div>
