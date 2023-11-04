<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <?php if((isset($banner->background_link)) && (validImage($banner->background_link) == 0)): ?>
            <iframe src="<?php echo e($banner->background_link); ?>"></iframe>
        <?php else: ?>
            <a href="<?php echo e($banner->background_site_url); ?>" target="_blank">
                <img src="<?php echo e($banner->background_link); ?>">
            </a>
        <?php endif; ?>
    </body>
</html>
