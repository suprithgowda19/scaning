<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Zeta admin dashboard">
    <meta name="author" content="pixelstrap">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('assets/images/biffeslogo2.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/biffeslogo2.png') }}" type="image/x-icon">

    <title>@yield('title', 'Admin Dashboard')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap"
        rel="stylesheet">

    {{-- Zeta Icon Libraries --}}
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/icofont.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/themify.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/flag-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/feather-icon.css') }}">

    {{-- Zeta Plugins --}}
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/animate.css') }}">

    {{-- Bootstrap + Main Styles --}}
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('assets/css/color-1.css') }}" media="screen">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">

    {{-- Page-level CSS --}}
    @stack('css')

    <style>
        /* Fix sidebar height overflow */
        .page-wrapper.compact-wrapper .page-body-wrapper div.sidebar-wrapper .sidebar-main .simplebar-offset {
            height: auto !important;
        }
    </style>
</head>

<body>

    {{-- Back to top --}}
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>

    <div class="page-wrapper default-wrapper" id="pageWrapper">

        {{-- NAVBAR --}}
        @include('partials.navbar')

        <div class="page-body-wrapper default-menu">

            {{-- SIDEBAR --}}
            @include('partials.sidebar')

            <div class="page-body">

                {{-- PAGE HEADER --}}
                @role('admin')
                    <div class="container-fluid">
                        <div class="page-title">
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <h3>@yield('page_title')</h3>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="{{ url('/') }}">
                                                <i data-feather="home"></i>
                                            </a>
                                        </li>
                                        @yield('breadcrumb')
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                @endrole

                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- PAGE CONTENT --}}
                <div class="container-fluid">
                    @yield('content')
                </div>

            </div>
        </div>

        {{-- FOOTER --}}
        @include('partials.footer')
    </div>

    {{-- JS FILES --}}
    <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('assets/js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/icons/feather-icon/feather-icon.js') }}"></script>

    <script src="{{ asset('assets/js/scrollbar/simplebar.js') }}"></script>
    <script src="{{ asset('assets/js/scrollbar/custom.js') }}"></script>

    <script src="{{ asset('assets/js/config.js') }}"></script>
    <script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>

    <script src="{{ asset('assets/js/script.js') }}"></script>

    {{-- Alpine.js (Global for Admin Panel) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Page-level custom scripts --}}
    @stack('scripts')

</body>

</html>
