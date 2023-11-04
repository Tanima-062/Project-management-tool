<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Scrolling Banner Generator</title>
    <!-- Favicon-->
    <link rel="icon" href="<?php echo URL::to('public/favicon.ico'); ?>" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?php echo URL::to('public/plugins/bootstrap/css/bootstrap.css'); ?>" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?php echo URL::to('public/plugins/node-waves/waves.css'); ?>" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="<?php echo URL::to('public/plugins/animate-css/animate.css'); ?>" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="<?php echo URL::to('public/css/style.css'); ?>" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?php echo URL::to('public/css/themes/all-themes.css'); ?>" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo URL::to('public/plugins/sweetalert/sweetalert.css'); ?>">

    <?php echo $__env->yieldContent('custom_page_style'); ?>
</head>

<body class="theme-red">
<!-- Page Loader -->
<?php echo $__env->make('admin.includes.page_loader', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<!-- Search Bar -->
<?php echo $__env->make('admin.includes.search_bar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<!-- #END# Search Bar -->
<!-- Top Bar -->
<?php echo $__env->make('admin.includes.nav_bar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<!-- #Top Bar -->
<?php echo $__env->make('admin.includes.left_menu_bar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<section class="content">
    <?php echo $__env->yieldContent('content'); ?>
</section>

<!-- Jquery Core Js -->
<script src="<?php echo URL::to('public/plugins/jquery/jquery.min.js'); ?>"></script>

<!-- Bootstrap Core Js -->
<script src="<?php echo URL::to('public/plugins/bootstrap/js/bootstrap.js'); ?>"></script>

<!-- Select Plugin Js -->
<script src="<?php echo URL::to('public/plugins/bootstrap-select/js/bootstrap-select.js'); ?>"></script>

<!-- Slimscroll Plugin Js -->
<script src="<?php echo URL::to('public/plugins/jquery-slimscroll/jquery.slimscroll.js'); ?>"></script>

<!-- Waves Effect Plugin Js -->
<script src="<?php echo URL::to('public/plugins/node-waves/waves.js'); ?>"></script>

<!-- Custom Js -->
<script src="<?php echo URL::to('public/js/admin.js'); ?>"></script>
<script src="<?php echo URL::to('public/plugins/sweetalert/sweetalert.min.js'); ?>"></script>
<script src="<?php echo URL::to('public/js/vue.js'); ?>"></script>
<script src="<?php echo URL::to('public/js/helpers.js'); ?>" type="text/javascript"></script>

<script type="text/javascript">
    $('document').ready(function(){
        $(document).on({
            click:function(e){
                e.preventDefault();
                delete_with_swal($(this).attr('href'),'<?php echo csrf_token(); ?>',$(this).closest('tr'));
            }
        },'.delete_with_swal');
        $('[data-toggle="tooltip"]').tooltip();

    });
</script>
<?php echo $__env->yieldContent('custom_page_script'); ?>
<div id="snackbar">Code hasbeen copied...</div>
</body>

</html>