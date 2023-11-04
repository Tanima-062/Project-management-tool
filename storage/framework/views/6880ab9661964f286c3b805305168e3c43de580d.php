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
<?php /**PATH /home/bikroy/public_html/project_management_tool/resources/views/backend/auth/login.blade.php ENDPATH**/ ?>