<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="User Login Portal">
    <meta name="keywords" content="login, user login, portal">
    <meta name="author" content="pixelstrap">

    <link rel="icon" href="{{ asset('assets/images/BBMPlogo.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/BBMPlogo.png') }}" type="image/x-icon">

    <title>Login</title>

    <!-- Bootstrap css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/bootstrap.css') }}">

    <!-- App css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('assets/css/color-1.css') }}" media="screen">

    <!-- Responsive css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<style>
    body {
        margin: 0;
        padding: 0;
        background: url("{{ asset('assets/video/original-background.gif') }}") no-repeat center center fixed;
        background-size: cover;
    }

    @media (min-width:756px) {
        .login-form {
            width: auto !important;
        }
    }
</style>

<body>

<div class="page-body-wrapper">
    <div class="container-fluid">
        <div class="row m-0">
            <div class="col-lg-12 col-sm-12">

                <div class="login-card p-3" style="height:auto">
                    <div class="theme-form col-md-9 p-4 shadow"
                         style="background:white;border:1px solid #00000047;border-radius:10px;margin:auto;">

                        <div class="d-flex row align-items-center">

                            <!-- LEFT BRAND BLOCK -->
                            <div class="col-lg-6 col-12 mb-3">
                                <div class="d-flex flex-column gap-1 align-items-center justify-content-center">
                                    <img src="{{ asset('assets/images/BBMPlogo.png') }}" width="15%" alt="">
                                    <h3 class="text-dark fw-bold text-center">
                                        scanning module
                                    </h3>
                                    <img src="{{ asset('assets/images/logo/logo-icon.jpg') }}" width="15%" alt="">
                                </div>
                            </div>

                            <!-- RIGHT LOGIN FORM -->
                            <div class="col-lg-6 col-12">

                                <h4 class="text-center mb-3" style="color:#2a1570;">
                                    Login to your Account
                                </h4>

                                <form class="theme-form login-form" method="POST" action="{{ route('login') }}">
                                    @csrf

                                    {{-- EMAIL --}}
                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-envelope"></i>
                                            </span>

                                            <input class="form-control @error('email') is-invalid @enderror"
                                                   type="email"
                                                   name="email"
                                                   value="{{ old('email') }}"
                                                   required
                                                   autofocus
                                                   placeholder="admin@example.com">

                                            @error('email')
                                                <span class="invalid-feedback d-block">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- PASSWORD --}}
                                    <div class="form-group mt-3">
                                        <label>Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-lock"></i>
                                            </span>

                                            <input id="password"
                                                   class="form-control @error('password') is-invalid @enderror"
                                                   type="password"
                                                   name="password"
                                                   required
                                                   placeholder="********">

                                            <span class="input-group-text" onclick="togglePassword()">
                                                <i id="toggleIcon"
                                                   class="fa-solid fa-eye-slash"
                                                   style="cursor:pointer;"></i>
                                            </span>

                                            @error('password')
                                                <span class="invalid-feedback d-block">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- SUBMIT --}}
                                    <div class="form-group mt-4">
                                        <button class="btn btn-primary btn-block w-100" type="submit">
                                            Sign In
                                        </button>
                                    </div>

                                </form>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        let pass = document.getElementById("password");
        let icon = document.getElementById("toggleIcon");

        if (pass.type === "password") {
            pass.type = "text";
            icon.classList.replace("fa-eye-slash", "fa-eye");
        } else {
            pass.type = "password";
            icon.classList.replace("fa-eye", "fa-eye-slash");
        }
    }
</script>

<!-- Scripts -->
<script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/icons/feather-icon/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/icons/feather-icon/feather-icon.js') }}"></script>
<script src="{{ asset('assets/js/scrollbar/simplebar.js') }}"></script>
<script src="{{ asset('assets/js/scrollbar/custom.js') }}"></script>
<script src="{{ asset('assets/js/config.js') }}"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>

</body>
</html>
