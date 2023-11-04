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
    <link rel="icon" href="{{asset('backend/assets/img/icon.png')}}" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('backend/assets/css/bootstrap.min.css')}}">

    <title>Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
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
                <div class="col-lg-8 col-sm-12 offset-md-1 right-item mx-auto">
                <a href="{{route('login')}}">
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

                               <form method="POST" action="{{ route('passwordUpdate') }}">
                               {{ csrf_field() }}
                               <input type="hidden" name="token" value="{{ csrf_token() }}">

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                    <div class="col-md-6">
                                        
                                        <input id="userId" type="hidden" class="form-control" name="userId" required value={{$userId}}>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required autocomplete="new-password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4 login-submit">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Reset Password') }}
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
