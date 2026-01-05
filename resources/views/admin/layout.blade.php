<?php 
    $version = env('JS_VERSION'); 
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edudash - School, College & LMS Admin Dashboard Template | Bootstrap 5</title>
    <link rel="icon" type="image/png" href="assets/images/favicon.png" sizes="16x16">
    <!-- All library css  -->
    <!-- main css -->

    <link rel="stylesheet" type="text/css" href="{{url('assets/css/remixicon.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/lib/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/lib/apexcharts.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/lib/flatpickr.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/lib/calendar.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/style.css')}}">
<body ng-app="app">
    <aside class="sidebar">
        @include('admin.theme_setting')
        @include('admin.sidebar')
    </aside>
  
    <main class="dashboard-main">
        <div class="navbar-header">
            
        </div>

        <div class="dashboard-main-body">

            @yield('main')
        </div>

            <!-- <footer class="d-footer">

            </footer> -->
    </main>
    <script type="text/javascript">
        var base_url = "{{url('/')}}";
        var CSRF_TOKEN = "{{ csrf_token() }}";
       
    </script>

    <script type="text/javascript" src="{{url('assets/js/lib/jquery-3.7.1.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/lib/bootstrap.bundle.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/lib/apexcharts.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/lib/iconify-icon.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/lib/dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/lib/jquery-ui.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/js/app.js')}}"></script>

    <script type="text/javascript" src="{{url('assets/scripts/selectize.min.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/scripts/angular.min.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/scripts/ng-file-upload.min.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/scripts/angular-selectize.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/scripts/jcs-auto-validate.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/scripts/core/custom.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/scripts/core/app.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/scripts/core/services.js')}}" ></script>
    <script type="text/javascript" type="text/javascript" src="{{url('assets/scripts/core/controller.js')}}"></script>

    @yield('footer_scripts')

    <script>
      angular.module("app").constant("CSRF_TOKEN", "{{ csrf_token() }}");
    </script>
</body>
</html>
