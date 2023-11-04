<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet"> -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('backend/assets/templates/css/custom-styles.css')}}">
    <!-- Style -->
    <link rel="stylesheet" href="{{asset('backend/assets/css/sso_login.css')}}">
    <link rel="icon" href="{{asset('backend/assets/img/stl_icon.png')}}" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('backend/assets/css/bootstrap.min.css')}}">

    <title>Login</title>

    <style media="screen">
        .card .card-body{
            overflow: auto;
        }
        .login-page .login-box .container .right-item {
            margin-top: 0%;
        }
        .container {
            max-width: 80%;
        }
    </style>
</head>
<body class="hold-transition">
<!-- <div class="login-page">
    <div class="login-box">
        <div class="container">
            @include('backend.layouts.messages')
            <div class="row sso-login-top">
                <div class="col-lg-5 offset-md-3 right-item">
                    <img src="{{asset('backend/assets/img/logo_brac.png')}}" class="logo_brac">
                    <h2 class="Task-Management-Platform">Task Management Platform</h2>
                    <img class="sso-login-top-img" src="{{asset('backend/assets/img/sso-login-page-vector.png')}}">
                </div>
            </div>

            <div class="row offset-md-1 sso-login-content">
               <button class="SSO-Login">SSO Login</button>
               <span>OR</span>
               <a href="{{route('login')}}" class="Gmail-Login">Login with Email</a>
            </div>

            <div class="login-footer">
                <div class="footer-support footer-support-contact">
                    Support Contact: +8801847-267637, Email: bracsupport@shebatech.com.bd
                </div>
                <div class="footer-support footer-support-time">
                    Support Time: 8.00 AM - 7.30 PM (BST), Support Day: SIX (6) DAYS A WEEK (SATURDAY TO THURSDAY)
                </div>
                <div class="footer-copyright footer-support-copyright">
                    <p>&copy; 2021 All copyrights are reserved &trade;</p>
                </div>
            </div>

        </div>
    </div>
</div> -->
<div class="login-page">
    <div class="login-box">
      <div class="container">
          <div class="row total-item">
            <div class="col-md-12">
              <div class="disclosure-item">
                <img class="img-fluid brac-logo" src="{{asset('backend/assets/img/logo_stl.png')}}" alt="stl-logo">
                <h2>Task Management Platform</h2>
                <img class="sso-login-bg img-fluid" src="{{asset('backend/assets/img/Stl-Login -Page-Vector.png')}}" alt="">
                <div class="disclosure-item-button">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="disclosure-item-button-item">
                        {{-- <a class="sso_login" href="http://sso.brac.net/auth/isoap/login/session?site=tmptest">SSO Login</a> --}}
                        {{-- <p>OR</p> --}}
                        <a class="login_with_email" href="{{ route('login') }}">Login with Email</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-12 bottom-item" id="footer">
            <div class="login-footer">
              <div class="footer-support">
                <p>Support Contact: +8801847418950, Email: md.jeshad@shebatech.com.bd</p>
                <p>Support Time: 9:00 AM - 6:30 PM (BST), Support Day: FIVE (5) DAYS A WEEK (SUNDAY TO THURSDAY)</p>
              </div>
              <div class="footer-copyright">
                <p>&copy; 2021 All copyrights are reserved &trade;</p>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
</body>
</html>
