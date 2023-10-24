<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="theme-color" content="#212529" />

    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets/img/site.webmanifest') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | Admin | Login</title>


    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/css/adminlte.min.css') }}?ver={{ time() }}">

    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}?ver={{ time() }}">

    {{-- <style>
        body {
            background-size: cover;
            position: relative;
            background-image: url({{ asset('assets/img/hero-10.jpg') }});
        }
    </style> --}}
</head>

<body class="hold-transition login-page">

    <div id="preloader">
        <div id="loader"></div>
    </div>

    <div class="text-center mb-5" style="max-width: 150px;">
        <img src="{{ asset('assets/img/sampark_logo.png') }}" alt="Logo" class="" style="width: 100%;">
    </div>
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <form method="POST" action="{{ route('login') }}">
                    {{-- @csrf --}}

                    <div class="mb-3">
                        <div class="input-group">
                            <label for="">Email Address</label>
                            <input id="email" type="email"
                                class="form-control @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" placeholder="{{ __('Email Address') }}"
                                autocomplete="email" autofocus>

                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round"
                                            d="M16.5 12a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zm0 0c0 1.657 1.007 3 2.25 3S21 13.657 21 12a9 9 0 10-2.636 6.364M16.5 12V8.25" />
                                    </svg>

                                </div>
                            </div>
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert" style="display: block;">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                    <div class="mb-3">
                        <div class="input-group">
                            <label for="">Password</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password"
                                placeholder="{{ __('Password') }}" autocomplete="current-password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                    </svg>

                                </div>
                            </div>
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert" style="display: block;">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input name="remember" value="1" type="checkbox" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit"
                                class="btn btn-primary btn-block btn-login">{{ __('Login') }}</button>
                        </div>
                        <!-- /.col -->
                    </div>


                    <div class="row">
                        <div class="col-12">
                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('assets/js/adminlte.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('a:not([href*=javascript])').on('click', function() {
                $('#preloader').show();
            });
            $('.btn-login').on('click', function() {
                $('#preloader').show();
            });


            $('body').addClass('loaded');
            // Once the container has finished, the scroll appears
            if ($('body').hasClass('loaded')) {
                // It is so that once the container is gone, the entire preloader section is deleted
                $('#preloader').delay(1000).queue(function() {
                    $(this).hide();
                });
            }
        });
    </script>
</body>

</html>
