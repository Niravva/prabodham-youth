<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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

    <meta http-equiv="refresh" content="2700;URL='{{ route('dashboard') }}?logout=1'" />

    <title>{{ config('app.name', 'Laravel') }} | @yield('title')</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- IonIcons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}" />

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/css/adminlte.min.css') }}">

    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

</head>

<body class="hold-transition layout-top-nav admin layout-navbar-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white shadow">
            <div class="container-fluid">
                <button class="navbar-toggler mr-3" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <a href="{{ route('dashboard') }}" class="navbar-brand">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="brand-image"
                        style="margin-bottom: -9px;">
                </a>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <!-- Left navbar links -->
                    <ul class="navbar-nav">
                        <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}" class="nav-link"><i class="fas fa-home"></i>
                                Dashboard</a>
                        </li>

                        @if (get_current_admin_level() != 'Followup_Admin')
                            <li
                                class="nav-item {{ request()->is('admins') || request()->is('admins/*') ? 'active' : '' }}">
                                <a href="{{ route('admins.index') }}" class="nav-link">
                                    <i class="fas fa-user-tie"></i> Admins
                                </a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a href="{{ route('members.index') }}"
                                class="nav-link {{ request()->is('members') || request()->is('members/*') ? 'active' : '' }}"><i
                                    class="fas fa-user"></i> Members</a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('attendances.index') }}"
                                class="nav-link {{ request()->is('attendances') || request()->is('attandances/*') ? 'active' : '' }}"><i
                                    class="fas fa-clipboard"></i> Attendances</a>
                        </li>


                        @if (!in_array(get_current_admin_level(), ['Group_Admin', 'Followup_Admin']))
                            <li class="nav-item dropdown">
                                <a id="dropdownSubMenuMasters" href="#" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle"><i
                                        class="fas fa-list"></i>
                                    Masters</a>
                                <ul aria-labelledby="dropdownSubMenuMasters" class="dropdown-menu border-0 shadow">
                                    @if (in_array(get_current_admin_level(), ['Super_Admin', 'Country_Admin', 'State_Admin']))
                                        <li><a href="{{ route('pradeshs.index') }}"
                                                class="dropdown-item nav-link {{ request()->is('pradeshs') || request()->is('pradeshs/*') ? 'active' : '' }}"><i
                                                    class="fas fa-city"></i> Pradeshs</a>
                                        </li>
                                    @endif
                                    @if (in_array(get_current_admin_level(), ['Super_Admin', 'Country_Admin', 'State_Admin', 'Pradesh_Admin']))
                                        <li><a href="{{ route('zones.index') }}"
                                                class="dropdown-item nav-link {{ request()->is('zones') || request()->is('zones/*') ? 'active' : '' }}"><i
                                                    class="fas fa-map-marker-alt"></i> Zones</a>
                                        </li>
                                    @endif
                                    @if (in_array(get_current_admin_level(), ['Super_Admin', 'Country_Admin', 'State_Admin', 'Pradesh_Admin', 'Zone_Admin']))
                                        <li><a href="{{ route('sabhas.index') }}"
                                                class="dropdown-item nav-link {{ request()->is('sabhas') || request()->is('sabhas/*') ? 'active' : '' }}"><i
                                                    class="fas fa-praying-hands"></i> Sabhas</a>
                                        </li>
                                    @endif
                                    @if (in_array(get_current_admin_level(), [
                                            'Super_Admin',
                                            'Country_Admin',
                                            'State_Admin',
                                            'Pradesh_Admin',
                                            'Zone_Admin',
                                            'Sabha_Admin',
                                        ]))
                                        <li><a href="{{ route('groups.index') }}"
                                                class="dropdown-item nav-link {{ request()->is('groups') || request()->is('groups/*') ? 'active' : '' }}"><i
                                                    class="fas fa-users"></i> Groups</a>
                                        </li>
                                    @endif

                                    @if (in_array(get_current_admin_level(), ['Super_Admin', 'Country_Admin', 'State_Admin', 'Pradesh_Admin', 'Zone_Admin']))
                                        <li><a href="{{ route('tagsMaster.index') }}"
                                                class="dropdown-item nav-link {{ request()->is('tagsMaster') || request()->is('tagsMaster/*') ? 'active' : '' }}"><i class="fas fa-tag"></i> Tags</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        @if (!in_array(get_current_admin_level(), ['Group_Admin', 'Followup_Admin']))
                            <li class="nav-item dropdown">
                                <a id="dropdownSubMenuMasters" href="#" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false"
                                    class="nav-link dropdown-toggle">Others</a>
                                <ul aria-labelledby="dropdownSubMenuMasters" class="dropdown-menu border-0 shadow">
                                    @if (in_array(get_current_admin_level(), [
                                            'Super_Admin',
                                            'Country_Admin',
                                            'State_Admin',
                                            'Pradesh_Admin',
                                            'Zone_Admin',
                                            'Sabha_Admin',
                                        ]))
                                        <li>
                                            <a href="{{ route('action-logs.index') }}"
                                                class="dropdown-item nav-link {{ request()->is('action-logs') || request()->is('action-logs/*') ? 'active' : '' }}">
                                                <i class="fas fa-user-tie"></i> Action Logs
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('login-logs.index') }}"
                                                class="dropdown-item nav-link {{ request()->is('login-logs') || request()->is('login-logs/*') ? 'active' : '' }}">
                                                <i class="fas fa-sign-in-alt"></i> Login Logs
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif


                        @if (in_array(get_current_admin_level(), ['Super_Admin', 'Country_Admin', 'State_Admin', 'Pradesh_Admin', 'Zone_Admin']))
                            {{-- <li class="nav-item dropdown">
                                <a id="dropdownSubMenuReport" href="#" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false" class="nav-link dropdown-toggle"><i
                                        class="fas fa-file-alt"></i>
                                    Reports</a>
                                <ul aria-labelledby="dropdownSubMenuReport" class="dropdown-menu border-0 shadow">
                                    <li>
                                        <a href="#!" class="dropdown-item nav-link"></a>
                                    </li>
                                </ul>
                            </li> --}}
                        @endif
                    </ul>
                </div>

                <!-- Right navbar links -->
                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <li class="nav-item dropdown">
                        <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false" class="nav-link dropdown-toggle">
                            @if (get_current_admin_level() == 'Super_Admin')
                                <b>{{ Auth::user()->name }}</b>
                            @else
                                {!! get_name_badge(Auth::user()->name) !!}
                            @endif
                        </a>
                        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                            <span class="dropdown-item dropdown-header" style="height: auto !important;">
                                <b>{{ Auth::user()->name }}</b><br />
                                {!! Auth::user()->email !!}<br />
                                {!! get_admin_type_badge(get_current_admin_level()) !!}
                                {!! get_admin_for_badge() !!}
                            </span>

                            <li><a href="javascript:void;" class="dropdown-item nav-link" data-toggle="modal"
                                    data-target="#modal-admin-profile" data-remote="{{ route('profile') }}"><i
                                        class="fas fa-user-alt"></i>
                                    Profile</a></li>

                            <li>
                                <a href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                    class="dropdown-item nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row align-items-center mb-2">
                        <div class="col">
                            <h1 class="m-0">@yield('left_header_content')</h1>
                        </div><!-- /.col -->
                        @hasSection('right_header_content')
                            <div class="col">
                                <div class="float-right">
                                    @yield('right_header_content')
                                </div>
                            </div><!-- /.col -->
                        @endif
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
    </div>
    <!-- ./wrapper -->

    <div class="modal fade" id="modal-admin-profile" data-backdrop="static">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="text-center p-5">
                    <i class="text-muted fas fa-spinner fa-spin" style="font-size: 25px;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>

    <!-- Bootstrap -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- bs-custom-file-input -->
    <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

    <!-- DataTables  & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <script src="{{ asset('plugins/datatables-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedheader/js/fixedHeader.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/fixedColumns.bootstrap4.min.js') }}"></script>

    <!-- AdminLTE -->
    <script src="{{ asset('assets/js/adminlte.js') }}"></script>

    <!-- Summernote -->
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>

    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true
        });

        function printErrorMsg(errors) {
            $("span.invalid-feedback").remove();
            if (errors) {
                var i = 0;
                $.each(errors, function(key, value) {
                    if (i == 0) {
                        jQuery("*[name='" + key + "']").focus();
                    }
                    if (jQuery("*[name='" + key + "']").hasClass("select2-hidden-accessible") ==
                        true) {
                        jQuery("*[name='" + key + "']").next().after(
                            '<span class="invalid-feedback" role="alert" style="display: inline-block;"><strong>' +
                            value[0] + '</strong></span>');
                    } else {
                        jQuery("*[name='" + key + "']").after(
                            '<span class="invalid-feedback" role="alert" style="display: inline-block;"><strong>' +
                            value[0] + '</strong></span>');
                    }
                    i++;
                });
            }
        }
        $(document).ready(function() {
            jQuery('li.dropdown ul.dropdown-menu li a.active').parents('li.nav-item.dropdown').find(
                '.dropdown-toggle').addClass('active');

            $('body').on('click', '[data-toggle="modal"]', function() {
                if ($(this).data("remote")) {
                    $($(this).data("target") + ' .modal-content').load($(this).data("remote"));
                }
            });
            $('body').tooltip({
                selector: '[data-toggle=tooltip]'
            });
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });

            <?php if(isset($_GET['logout']) && $_GET['logout'] == 1){ ?>
                document.getElementById('logout-form').submit();
            <?php } ?>
        });
    </script>

    @stack('scripts')
</body>

</html>
