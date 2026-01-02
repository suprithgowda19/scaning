<div class="sidebar-wrapper">
    <div>

        {{-- LOGO --}}
        <div class="logo-wrapper">
            <a href="">
                <img class="img-fluid for-light" src="{{ asset('assets/images/newimages/headernewlogo16biffes.png') }}"
                    alt="">
                <img class="img-fluid for-dark" src="{{ asset('assets/images/newimages/headernewlogo16biffes.png') }}"
                    alt="">
            </a>
            <div class="back-btn"><i class="fa fa-angle-left"></i></div>
        </div>

        {{-- Mobile Logo Icon --}}
        <div class="logo-icon-wrapper">
            <a href="">
                <img class="img-fluid" src="{{ asset('assets/images/logo-icon.png') }}" alt="">
            </a>
        </div>

        <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>

            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">

                    {{-- Mobile back --}}
                    <li class="back-btn">
                        <a href="">
                            <img class="img-fluid" src="{{ asset('assets/images/logo-icon.png') }}" alt="">
                        </a>
                        <div class="mobile-back text-end">
                            <span>Back</span>
                            <i class="fa fa-angle-right ps-2"></i>
                        </div>
                    </li>

                    {{-- ========================= --}}
                    {{-- DASHBOARD --}}
                    {{-- ========================= --}}
                    <li class="sidebar-list">
                        @role('admin')
                            <a class="sidebar-link sidebar-title link-nav" href="{{ route('dashboard.admin.index') }}">
                                <i data-feather="grid"></i>
                                <span>Dashboard</span>
                            </a>
                        @else
                            <a class="sidebar-link sidebar-title link-nav" href="{{ route('dashboard.staff.index') }}">
                                <i data-feather="grid"></i>
                                <span>Dashboard</span>
                            </a>
                        @endrole
                    </li>





                    @role('admin')
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                                <i data-feather="settings"></i>
                                <span>Masters</span>
                            </a>
                            <ul class="sidebar-submenu">
                                <li><a href="{{ route('admin.venues.index') }}">Venues</a></li>
                                <li><a href="{{ route('admin.screens.index') }}">Screens</a></li>
                                

                                <li><a href="">Scheduler</a></li>
                            </ul>
                        </li>
                    @endrole



                    @role('admin')
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title link-nav"
                                href="{{ route('admin.staff-assignments.index') }}">
                                <i data-feather="user-check"></i>
                                <span>Staff Assignment</span>
                            </a>
                        </li>
                    @endrole





                    {{-- ========================= --}}
                    {{-- USERS (Admin Only) --}}
                    {{-- ========================= --}}

                    @role('admin')
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.users.index') }}">
                                <i data-feather="users"></i>
                                <span>Users</span>
                            </a>
                        </li>
                    @endrole


                    {{-- ========================= --}}
                    {{-- PROFILE --}}
                    {{-- ========================= --}}

                    @role('staff')
                        <li class="sidebar-list">
                            <a class="sidebar-link sidebar-title link-nav"
                                href="{{ route('profile.show', auth()->id()) }}">
                                <i data-feather="user"></i>
                                <span>Profile</span>
                            </a>
                        </li>
                    @endrole

                    @role('staff')
                        <li class="sidebar-list ">
                            <a class="sidebar-link sidebar-title link-nav" href="{{ route('staff.scan.index') }}">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <g>
                                        <path d="M4 4H10V6H6V10H4V4Z" fill="#130F26" />
                                        <path d="M14 4H20V10H18V6H14V4Z" fill="#130F26" />
                                        <path d="M4 14H6V18H10V20H4V14Z" fill="#130F26" />
                                        <path d="M18 14H20V20H14V18H18V14Z" fill="#130F26" />
                                        <path d="M7 11H17V13H7V11Z" fill="#130F26" />
                                    </g>
                                </svg>
                                <span>Scanning</span>
                            </a>
                        </li>
                    @endrole


                </ul>
            </div>

            <div class="right-arrow" id="right-arrow">
                <i data-feather="arrow-right"></i>
            </div>

        </nav>
    </div>
</div>
