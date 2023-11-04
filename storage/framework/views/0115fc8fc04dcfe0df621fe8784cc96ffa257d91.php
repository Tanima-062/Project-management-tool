<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet"> -->
    <script type="text/javascript" src="<?php echo e(asset('backend/assets/templates/js/jquery.min.js')); ?>"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
    <!-- Style -->
    <link rel="stylesheet" href="<?php echo e(asset('backend/assets/css/login.css')); ?>">
    <link rel="icon" href="<?php echo e(asset('backend/assets/img/stl_icon.png')); ?>" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('backend/assets/css/bootstrap.min.css')); ?>">

    <title>Login</title>

</head>
<body class="hold-transition">
<div class="login-page">
    <div class="login-box">
        <div class="container">
            <!-- <div class="row"> -->
            <?php echo $__env->make('backend.layouts.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="row login-first-row">
                <!-- <div class="col-lg-5 left-item">
                        <img src="<?php echo e(asset('backend/assets/img/login-page-vector-graphics.png')); ?>"
                             class="Login-Page-Vector-Graphics img-fluid">
                </div> -->
                <div class="col-lg-6 col-sm-12 offset-md-1 right-item mx-auto">
                    <a href="<?php echo e(route('ssoLogin')); ?>">
                        <img src="<?php echo e(asset('backend/assets/img/logo_stl.png')); ?>" class="logo_brac img-fluid">
                    </a>
                    <div class="card">
                        <div class="card-body login-card-body">
                            <h2>Task Management Platform</h2>
                            <p><span>Reset Password</span></p>
                               <?php if(Session::has('message')): ?>
                                <div class="alert alert-<?php echo e(Session::get('alert-status')); ?>" role="alert">
                                    <?php echo e(Session::get('message')); ?>

                                </div>
                               <?php endif; ?>
                               <form method="POST" action="<?php echo e(route('sendEmailForReset')); ?>">
                                <?php echo csrf_field(); ?>

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right" style="color:#00ad4d;"><?php echo e(__('E-Mail Address')); ?></label>

                                    <div class="col-md-7">
                                        <input id="email" type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus>

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
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary" style="background-color:#00ad4d;">
                                            <?php echo e(__('Send Password Reset Link')); ?>

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
<?php /**PATH /home/bikroy/public_html/project_management_tool/resources/views/backend/auth//email.blade.php ENDPATH**/ ?>