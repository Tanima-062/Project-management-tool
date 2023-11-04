<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet"> -->
    <script type="text/javascript" src="<?php echo URL::to ('public/backend/assets/templates/js/jquery.min.js'); ?>"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
    <!-- Style -->
    <link rel="stylesheet" href="<?php echo URL::to ('public/backend/assets/css/login.css'); ?>">
    <link rel="icon" href="<?php echo URL::to ('public/backend/assets/img/stl_icon.png'); ?>" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo URL::to ('public/backend/assets/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo URL::to ('public/backend/assets/css/font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo URL:: to ('public/backend/assets/templates/vendors/font-awesome/css/font-awesome.min.css'); ?>">

    <title>Login</title>

</head>
<body class="hold-transition">
<div class="login-page">
    <div class="login-box">
        <div class="container">
            <!-- <div class="row"> -->
            <?php echo $__env->make('backend.layouts.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="row login-first-row">
                <div class="col-lg-6 left-item">
                    <img src="<?php echo URL::to('public/backend/assets/img/Login_Page_Vector_Graphics.png'); ?>" class="Login-Page-Vector-Graphics img-fluid">
                </div>
                <div class="col-lg-4 col-sm-12 right-item">
                    <a href="<?php echo e(route('login')); ?>">
                        <img src="<?php echo URL::to ('public/backend/assets/img/Bikroy-LogoTag.png'); ?>" class="logo_brac img-fluid">
                    </a>
                    <div class="card">
                        <div class="card-body login-card-body">
                            <h2>Task Management Platform</h2>
                            <p>Welcome Back! Please <span>login</span> to continue.</p>
                               <?php if(Session::has('message')): ?>
                                <div class="alert alert-<?php echo e(Session::get('alert-status')); ?>" role="alert">
                                    <?php echo e(Session::get('message')); ?>

                                </div>
                               <?php endif; ?>
                            <form method="POST" action="<?php echo e(route('login.submit')); ?>">
                                <?php echo csrf_field(); ?>
                                <div class="input-group mb-3">
                                    <img src="<?php echo URL::to ('public/backend/assets/img/stl_user_icon.png'); ?>"
                                         class="User-Icon">
                                    <input type="text" class="form-control" value="<?php echo e(old('email')); ?>" name="email" placeholder="Email" required>
                                    <div class="text-danger"></div>
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="input-group mb-3">
                                    <img src="<?php echo URL:: to('public/backend/assets/img/stl_password_icon.png'); ?>"
                                         class="Password-Icon">
                                    <input type="password" name="password" class="form-control" id="password" placeholder="Password" style="padding-right:25px;" required>
                                    <i class="fa fa-eye password_eye" style="color: #009877"></i>
                                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>










                               
                                    <div class="forgot-password">
                                      <a href="<?php echo e(route('showForgetPasswordForm')); ?>">Forgot Password</a>
                                    </div>
                                <div class="mb-1 login-submit">
                                    <button type="submit" class="btn btn-primary btn-block">LOGIN</button>
                                </div>
                            </form>
                            <a href="<?php echo e(route('login.google')); ?>" class="mt-5 login-submit"><svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 48 48" width="48px" height="48px"><path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/><path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/><path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/><path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/></svg>SIGN IN WITH GOOGLE</a>
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
<script>
     $(document).ready(function() {
         showPassword();
     });
    function showPassword(){
        $('.fa-eye').click(function(){
            document.getElementById("password").type = "text";
            $(this).removeClass("fa-eye");
            $(this).addClass("fa-eye-slash");

            $('.fa-eye-slash').click(function(){
                document.getElementById("password").type = "password";
                $(this).removeClass("fa-eye-slash");
                $(this).addClass("fa-eye");

                showPassword();
            });
        });
    }
</script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\project_management_tool\resources\views/backend/auth/login.blade.php ENDPATH**/ ?>