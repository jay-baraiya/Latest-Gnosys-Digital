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
                <img src="{{ asset('assets/img/cropped-Ginosys-Digital-32x32.png') }}" width="40" height="30"
                    alt="Logo">
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
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="{{ request()->routeIs('admin.dashboard*') ? 'active subdrop' : '' }}">
                                <i class="ti ti-dashboard"></i><span>Dashboard</span><span class="menu-arrow"></span>
                            </a>
                            <ul style="display: {{ request()->routeIs('admin.dashboard*') ? 'block' : 'none' }};">
                                <li>
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                                </li>
                            </ul>
                        </li>

                        @canany(['view.users', 'view.roles'])
                            <li class="submenu">
                                <a href="javascript:void(0);"
                                    class="{{ request()->routeIs(['admin.users.*', 'admin.roles.*']) ? 'active subdrop' : '' }}">
                                    <i class="ti ti-users"></i><span>User Management</span><span class="menu-arrow"></span>
                                </a>
                                <ul style="display: {{ request()->routeIs(['admin.users.*', 'admin.roles.*']) ? 'block' : 'none' }};">
                                    @can('view.users')
                                        <li>
                                            <a href="{{ route('admin.users.index') }}"
                                                class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Manage Users</a>
                                        </li>
                                    @endcan
                                    @can('view.roles')
                                        <li>
                                            <a href="{{ route('admin.roles.index') }}"
                                                class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">Roles & Permissions</a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany

                        @can('view.categories')
                            <li class="submenu">
                                <a href="javascript:void(0);"
                                    class="{{ request()->routeIs(['admin.categories.*']) ? 'active subdrop' : '' }}">
                                    <i class="ti ti-category"></i><span>Category Management</span><span class="menu-arrow"></span>
                                </a>
                                <ul style="display: {{ request()->routeIs(['admin.categories.*']) ? 'block' : 'none' }};">
                                    <li>
                                        <a href="{{ route('admin.categories.index') }}"
                                            class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Categories</a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        @can('view.departments')
                            <li class="submenu">
                                <a href="javascript:void(0);"
                                    class="{{ request()->routeIs('admin.departments.*') ? 'active subdrop' : '' }}">
                                    <i class="ti ti-building"></i><span>Manage Departments</span><span class="menu-arrow"></span>
                                </a>
                                <ul style="display: {{ request()->routeIs('admin.departments.*') ? 'block' : 'none' }};">
                                    <li>
                                        <a href="{{ route('admin.departments.index') }}"
                                            class="{{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">Departments</a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        @can('view.email_accounts')
                            <li class="submenu">
                                <a href="javascript:void(0);"
                                    class="{{ request()->routeIs('admin.email_accounts.*') ? 'active subdrop' : '' }}">
                                    <i class="ti ti-mail"></i><span>Manage Email Accounts</span><span class="menu-arrow"></span>
                                </a>
                                <ul style="display: {{ request()->routeIs('admin.email_accounts.*') ? 'block' : 'none' }};">
                                    <li>
                                        <a href="{{ route('admin.email_accounts.index') }}"
                                            class="{{ request()->routeIs('admin.email_accounts.*') ? 'active' : '' }}">Email Accounts</a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        @canany(['view.digital.products', 'view.digital.services'])
                            <li class="submenu">
                                <a href="javascript:void(0);"
                                    class="{{ request()->routeIs(['admin.digital.products.*', 'admin.digital.services.*']) ? 'active subdrop' : '' }}">
                                    <i class="ti ti-box"></i><span>Products & Services</span><span class="menu-arrow"></span>
                                </a>
                                <ul style="display: {{ request()->routeIs(['admin.digital.products.*', 'admin.digital.services.*']) ? 'block' : 'none' }};">
                                    @can('view.digital.products')
                                        <li>
                                            <a href="{{ route('admin.digital.products.index') }}"
                                                class="{{ request()->routeIs('admin.digital.products.*') ? 'active' : '' }}">Digital Products</a>
                                        </li>
                                    @endcan
                                    @can('view.digital.services')
                                        <li>
                                            <a href="{{ route('admin.digital.services.index') }}"
                                                class="{{ request()->routeIs('admin.digital.services.*') ? 'active' : '' }}">Digital Services</a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany

                        @can('view.coupons')
                            <li class="submenu">
                                <a href="javascript:void(0);"
                                    class="{{ request()->routeIs('admin.coupons.*') ? 'active subdrop' : '' }}">
                                    <i class="ti ti-tag"></i><span>Manage Coupons</span><span class="menu-arrow"></span>
                                </a>
                                <ul style="display: {{ request()->routeIs('admin.coupons.*') ? 'block' : 'none' }};">
                                    <li>
                                        <a href="{{ route('admin.coupons.index') }}"
                                            class="{{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">Coupons</a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        @can('view.blogs')
                            <li class="submenu">
                                <a href="javascript:void(0);"
                                    class="{{ request()->routeIs('admin.blogs.*') ? 'active subdrop' : '' }}">
                                    <i class="ti ti-article"></i><span>Blog Management</span><span class="menu-arrow"></span>
                                </a>
                                <ul style="display: {{ request()->routeIs('admin.blogs.*') ? 'block' : 'none' }};">
                                    <li>
                                        <a href="{{ route('admin.blogs.index') }}"
                                            class="{{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">Blogs</a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        @can('view.wallets')
                            <li class="submenu">
                                <a href="javascript:void(0);"
                                    class="{{ request()->routeIs('admin.wallets.*') ? 'active subdrop' : '' }}">
                                    <i class="ti ti-wallet"></i><span>Wallet Management</span><span class="menu-arrow"></span>
                                </a>
                                <ul style="display: {{ request()->routeIs('admin.wallets.*') ? 'block' : 'none' }};">
                                    <li>
                                        <a href="{{ route('admin.wallets.index') }}"
                                            class="{{ request()->routeIs('admin.wallets.*') ? 'active' : '' }}">Wallets</a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        @can('view.orders')
                            <li class="submenu">
                                <a href="javascript:void(0);"
                                    class="{{ request()->routeIs('admin.orders.*') ? 'active subdrop' : '' }}">
                                    <i class="ti ti-shopping-cart"></i><span>Order Management</span><span class="menu-arrow"></span>
                                </a>
                                <ul style="display: {{ request()->routeIs('admin.orders.*') ? 'block' : 'none' }};">
                                    <li>
                                        <a href="{{ route('admin.orders.index') }}"
                                            class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">Orders</a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        @can('view.tickets')
                            <li class="submenu">
                                <a href="javascript:void(0);"
                                    class="{{ request()->routeIs('admin.tickets.*') ? 'active subdrop' : '' }}">
                                    <i class="ti ti-ticket"></i><span>Ticket Management</span><span class="menu-arrow"></span>
                                </a>
                                <ul style="display: {{ request()->routeIs('admin.tickets.*') ? 'block' : 'none' }};">
                                    <li>
                                        <a href="{{ route('admin.tickets.index') }}"
                                            class="{{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">Tickets</a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        @can('view.tasks')
                            <li class="submenu">
                                <a href="javascript:void(0);"
                                    class="{{ request()->routeIs('admin.tasks.*') ? 'active subdrop' : '' }}">
                                    <i class="ti ti-clipboard-list"></i><span>Task Management</span><span class="menu-arrow"></span>
                                </a>
                                <ul style="display: {{ request()->routeIs('admin.tasks.*') ? 'block' : 'none' }};">
                                    <li>
                                        <a href="{{ route('admin.tasks.index') }}"
                                            class="{{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}">Task</a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        @canany(['view.event_series', 'view.event'])
                            <li class="submenu">
                                <a href="javascript:void(0);"
                                    class="{{ request()->routeIs(['admin.event_series.*', 'admin.event.*']) ? 'active subdrop' : '' }}">
                                    <i class="ti ti-calendar-event"></i><span>Events Management</span><span class="menu-arrow"></span>
                                </a>
                                <ul style="display: {{ request()->routeIs(['admin.event_series.*', 'admin.event.*']) ? 'block' : 'none' }};">
                                    @can('view.event_series')
                                    <li>
                                        <a href="{{ route('admin.event_series.index') }}"
                                            class="{{ request()->routeIs('admin.event_series.*') ? 'active' : '' }}">Event Series</a>
                                    </li>
                                    @endcan
                                    @can('view.event')
                                    <li>
                                        <a href="{{ route('admin.event.index') }}"
                                            class="{{ request()->routeIs('admin.event.*') ? 'active' : '' }}">Events</a>
                                    </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany
                    </ul>
                </li>
            </ul>
        </div>
    </div>

</div>