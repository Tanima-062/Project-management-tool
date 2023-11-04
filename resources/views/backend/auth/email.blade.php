<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet"> -->
    <script type="text/javascript" src="{{asset('backend/assets/templates/js/jquery.min.js')}}"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
    <!-- Style -->
    <link rel="stylesheet" href="{{asset('backend/assets/css/login.css')}}">
    <link rel="icon" href="{{asset('backend/assets/img/stl_icon.png')}}" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('backend/assets/css/bootstrap.min.css')}}">

    <title>Login</title>

</head>
<body class="hold-transition">
<div class="login-page">
    <div class="login-box">
        <div class="container">
            <!-- <div class="row"> -->
            @include('backend.layouts.messages')
            <div class="row login-first-row">
                <!-- <div class="col-lg-5 left-item">
                        <img src="{{asset('backend/assets/img/login-page-vector-graphics.png')}}"
                             class="Login-Page-Vector-Graphics img-fluid">
                </div> -->
                <div class="col-lg-6 col-sm-12 offset-md-1 right-item mx-auto">
                    <a href="{{route('ssoLogin')}}">
                        <img src="{{asset('backend/assets/img/logo_stl.png')}}" class="logo_brac img-fluid">
                    </a>
                    <div class="card">
                        <div class="card-body login-card-body">
                            <h2>Task Management Platform</h2>
                            <p><span>Reset Password</span></p>
                               @if(Session::has('message'))
                                <div class="alert alert-{{ Session::get('alert-status') }}" role="alert">
                                    {{ Session::get('message') }}
                                </div>
                               @endif
                               <form method="POST" action="{{ route('sendEmailForReset') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right" style="color:#00ad4d;">{{ __('E-Mail Address') }}</label>

                                    <div class="col-md-7">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary" style="background-color:#00ad4d;">
                                            {{ __('Send Password Reset Link') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-12 bottom-item" id="footer">
                <div class="login-footer">
                    <div class="footer-support">

                    </div>
                    <div class="footer-copyright">
                        <p>&copy; 2021 All copyrights are reserved &trade;</p>
                    </div>
                </div>
            </div>
            <!-- </div> -->
        </div>
    </div>
</div>
</body>
</html>
