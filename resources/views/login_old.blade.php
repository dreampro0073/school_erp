<!DOCTYPE html>
<html style="height:100%;">
<head >
    <!-- Basic Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Aadhyasri Web Solutions | Web Development & Digital Services</title>
    <meta name="description" content="Aadhyasri Web Solutions provides professional web development, website design, SEO and digital solutions for businesses.">
    <meta name="keywords" content="Aadhyasri Web Solutions, web development, website design, SEO services, digital marketing">
    <meta name="author" content="Aadhyasri Web Solutions">
    <link rel="icon" sizes="32x32" type="image/x-icon" href="{{url('assets/img/favicon.png')}}" >

    <link rel="stylesheet" type="text/css" href="{{url('assets/css/lib/bootstrap.min.css')}}">


    <link rel="stylesheet" type="text/css" href="{{url('assets/css/style.css')}}">
    <style>
        .min-wid{
            min-width: 400px;
        }
        @media only screen and (max-width: 668px) {
            .min-wid{
                min-width: 80%;
            }
        }
        label.error{
            color: #f00 !important;
            font-size: 14px !important;
        }
    </style>
</head>
<body class="h-100">


<div class="d-lg-flex theme-bg-white h-100">
    <div class="w-50 d-lg-flex d-none overflow-hidden h-100">
        <img src="{{url('assets/img/login-bg.jpg')}}" alt="Login Image" class="w-100 h-100 object-fit-cover">
    </div>
    <div class="lg-w-50 px-24 py-32 d-flex justify-content-center align-items-center h-100">
        <div class="max-w-540-px min-wid mx-auto">
            <a href="{{url('/')}}" class="">
                <img src="{{url('assets/img/sx1-logo.png')}}" style="height:50px;width: auto;">
            </a>
            <div class="mt-32 mb-32">
                <h1 class="h6 fw-bold text-primary-light mb-8">
                    Welcome Back 👋
                </h1>
                <p class="text-sm text-secondary-light mb-0">
                    Log in to your account to continue
                </p>
            </div>
           <form action="{{url('login')}}" method="post" class="d-flex flex-column gap-32 submit-form">
                @csrf
                @if (session('failure'))
                    <div class="alert alert-danger py-8 px-12 mb-0">{{ session('failure') }}</div>
                @endif
                <div class="d-flex flex-column gap-16">
                    <div>
                        <label for="email" class="text-sm fw-semibold text-primary-light d-inline-block mb-8">
                            Email Address
                            <span class="text-danger-600">*</span>
                        </label>
                        <input name="email" type="email" id="email" class="email-field form-control" placeholder="Enter your email" value="{{ old('email') }}" required>
                        @error('email')
                            <label class="error">{{ $message }}</label>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="text-sm fw-semibold text-primary-light d-inline-block mb-8">
                            Password
                            <span class="text-danger-600">*</span>
                        </label>
                        <div class="position-relative">
                            <input name="password" type="password" id="password" class="password-field form-control" placeholder="Enter your password" required>
                            <button type="button" class="toggle-password btn p-0 border-0 bg-transparent position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light cursor-pointer ri-eye-line" data-toggle="#password" aria-label="Toggle password visibility">
                            </button>
                        </div>
                        @error('password')
                            <label class="error">{{ $message }}</label>
                        @enderror
                    </div>

                    <div>
                        <label for="captcha" class="text-sm fw-semibold text-primary-light d-inline-block mb-8">
                            Captcha
                            <span class="text-danger-600">*</span>
                        </label>
                        <div class="d-flex align-items-center gap-8 mb-8">
                            <img id="captcha-image" src="{{ route('login.captcha') }}?t={{ time() }}" alt="Captcha image" width="150" height="50" class="border radius-8">
                            <button type="button" id="refresh-captcha" class="btn btn-outline-secondary btn-sm">Refresh</button>
                        </div>
                        <input name="captcha" type="text" id="captcha" class="form-control" placeholder="Enter captcha text" autocomplete="off" required>
                        @error('captcha')
                            <label class="error">{{ $message }}</label>
                        @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-between gap-2">
                    <div class="form-check style-check d-flex align-items-center">
                        <input class="form-check-input border border-neutral-400" type="checkbox" value="" id="remeber">
                        <label class="form-check-label" for="remeber">Remember me </label>
                    </div>
                    <a href="javascript:void(0)" class="text-primary-600 fw-medium text-decoration-underline">Forgot
                        Password?</a>
                </div>
                <div class="">
                    <button type="submit" class="loginBtn btn btn-primary-600 text-sm btn-sm px-12 py-16 w-100 radius-8"> Log In
                    </button>
                </div>
                <div class="text-center text-sm text-secondary-light">
                    or login as
                </div>
                <div class="d-grid sm-grid-cols-3 grid-cols-2 gap-16">
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-8 fw-semibold text-sm radius-6 justify-content-center flex-grow-1 bg-success text-white py-10 px-8">
                        <span class="d-flex">
                            <img src="assets/images/icons/sheild-icon.png" alt="Icon">
                        </span>
                        <span class="">Supper Admin</span>
                    </a>
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-8 fw-semibold text-sm radius-6 justify-content-center flex-grow-1 bg-info-600 text-white py-10 px-8">
                        <span class="d-flex">
                            <img src="assets/images/icons/dashboard-icon.png" alt="Icon">
                        </span>
                        <span class="">Admin</span>
                    </a>
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-8 fw-semibold text-sm radius-6 justify-content-center flex-grow-1 bg-warning-600 text-white py-10 px-8">
                        <span class="d-flex">
                            <img src="assets/images/icons/student-icon.png" alt="Icon">
                        </span>
                        <span class="">Student</span>
                    </a>
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-8 fw-semibold text-sm radius-6 justify-content-center flex-grow-1 bg-purple-600 text-white py-10 px-8">
                        <span class="d-flex">
                            <img src="assets/images/icons/teacher-icon.png" alt="Icon">
                        </span>
                        <span class="">Teacher</span>
                    </a>
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-8 fw-semibold text-sm radius-6 justify-content-center flex-grow-1 bg-primary-600 text-white py-10 px-8">
                        <span class="d-flex">
                            <img src="assets/images/icons/guardian-icon.png" alt="Icon">
                        </span>
                        <span class="">Guardians </span>
                    </a>
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-8 fw-semibold text-sm radius-6 justify-content-center flex-grow-1 bg-pink text-white py-10 px-8">
                        <span class="d-flex">
                            <img src="assets/images/icons/library-icon.png" alt="Icon">
                        </span>
                        <span class="">Librarian</span>
                    </a>
                </div>
            </form>
            
            
        </div>
    </div>
</div>

<script type="text/javascript">
    var base_url = "{{url('/')}}";
    var CSRF_TOKEN = "{{ csrf_token() }}";
    </script>
    <script type="text/javascript" src="{{url('assets/scripts/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/scripts/jquery.validate.js')}}"></script>
    <script type="text/javascript" src="{{ url('assets/scripts/form-submit-validation.js') }}"></script>
    <script type="text/javascript">
        $(".submit-form").validate();

        $('#refresh-captcha').on('click', function () {
            var img = document.getElementById('captcha-image');
            img.src = "{{ route('login.captcha') }}?t=" + Date.now();
        });
    </script>
</body>
</html>
