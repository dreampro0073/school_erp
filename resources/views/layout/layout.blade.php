<?php
    $version = env('JS_VERSION');
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School ERP Dashboard</title>
    <link rel="icon" type="image/png" href="{{ url('assets/images/favicon.png') }}" sizes="16x16">
    
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/remixicon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/lib/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/lib/apexcharts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/lib/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/lib/calendar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/custom.css')}}">
</head>

<body ng-app="app">
    <aside class="sidebar">
        @include('layout.theme_setting')
        @include('layout.sidebar')
    </aside>

    <main class="dashboard-main">
        @php
            $authUser = Auth::user();
            $authPriv = (int) ($authUser->priv ?? $authUser->privillage ?? $authUser->privilege ?? 0);
            $dashboardLabels = [
                1 => 'Super Admin Panel',
                2 => 'Admin Panel',
                3 => 'Teacher Panel',
                4 => 'Student Panel',
                5 => 'Guardian Panel',
            ];
            $panelLabel = $dashboardLabels[$authPriv] ?? 'Dashboard';
        @endphp

        <div class="navbar-header px-24 py-16 bg-white border-bottom border-neutral-200">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <button type="button" class="sidebar-mobile-toggle text-xl text-neutral-500">
                        <i class="ri-menu-line"></i>
                    </button>
                    <div>
                        <h6 class="mb-0 mb-2 fw-bold text-lg">{{ $panelLabel }}</h6>
                        <p class="mb-0 text-sm text-secondary-light">{{ now()->format('d M, Y') }}</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 text-sm text-secondary-light">
                    <i class="ri-user-3-line"></i>
                    <span>{{ $authUser->name }}</span>
                </div>
            </div>
        </div>

        <div class="dashboard-main-body">
            @yield('main')
        </div>
    </main>

    <script type="text/javascript">
        var base_url = "{{ url('/') }}";
        var CSRF_TOKEN = "{{ csrf_token() }}";
        var api_key = "{{ Auth::user()->api_token }}";
    </script>

    <script type="text/javascript" src="{{ url('assets/js/lib/jquery-3.7.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/js/lib/bootstrap.bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/js/lib/apexcharts.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/js/lib/iconify-icon.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/js/lib/dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/js/flatpickr.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/js/app.js') }}"></script>

    <script type="text/javascript" src="{{ url('assets/scripts/selectize.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/scripts/angular.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/scripts/ng-file-upload.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/scripts/angular-selectize.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/scripts/jcs-auto-validate.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/scripts/core/custom.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/scripts/form-submit-validation.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/scripts/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/scripts/core/services.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/scripts/core/controller.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/scripts/core/student_ctrl.js') }}"></script>
    <script type="text/javascript" src="{{ url('assets/scripts/core/school_controller.js') }}"></script>

    @yield('footer_scripts')

    <script>
      angular.module('app').constant('CSRF_TOKEN', "{{ csrf_token() }}");
    </script>
</body>

</html>
