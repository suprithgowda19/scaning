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
                        <a class="sidebar-link sidebar-title link-nav" href="">
                            <i data-feather="grid"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    {{-- ========================= --}}
                    {{-- BOOKINGS --}}
                    {{-- ========================= --}}
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="">
                            <i data-feather="credit-card"></i>
                            <span>Bookings / Tickets</span>
                        </a>
                    </li>

            
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                            <i data-feather="settings"></i>
                            <span>Masters</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('admin.venues.index') }}">Venues</a></li>
                            <li><a href="{{ route('admin.screens.index') }}">Screens</a></li>
                            <li><a href="">Seats</a></li>
                            <li><a href="{{ route('admin.slots.index') }}">Slots</a></li>
                            <li><a href="{{ route('admin.movies.index') }}">Movies</a></li>
                            <li><a href="{{ route('admin.ssa.index') }}">Screen Assignments</a></li>
                        </ul>
                    </li>

                   

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="">
                            <i data-feather="user-check"></i>
                            <span>Staff Assignment</span>
                        </a>
                    </li>


                   
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="">
                            <i data-feather="cpu"></i>
                            <span>Installations</span>
                        </a>
                    </li>

                    {{-- ========================= --}}
                    {{-- USERS (Admin Only) --}}
                    {{-- ========================= --}}

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="">
                            <i data-feather="users"></i>
                            <span>Users</span>
                        </a>
                    </li>


                    {{-- ========================= --}}
                    {{-- PROFILE --}}
                    {{-- ========================= --}}
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="">
                            <i data-feather="user"></i>
                            <span>Profile</span>
                        </a>
                    </li>

                </ul>
            </div>

            <div class="right-arrow" id="right-arrow">
                <i data-feather="arrow-right"></i>
            </div>

        </nav>
    </div>
</div>
