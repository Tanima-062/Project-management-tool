<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <div>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <p><?php echo e($error); ?></p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php endif; ?>
<?php if(\Illuminate\Support\Facades\Session::has('success')): ?>
    <div class="alert alert-success">
        <div>
            <p><?php echo e(\Illuminate\Support\Facades\Session::get('success')); ?></p>
        </div>
    </div>
<?php endif; ?>


<?php if(\Illuminate\Support\Facades\Session::has('error')): ?>
    <div class="alert alert-danger">
        <div>
            <p><?php echo e(\Illuminate\Support\Facades\Session::get('error')); ?></p>
        </div>
    </div>
<?php endif; ?>

<script type="text/javascript">
    $(function () {
        $(".alert").delay(5000).slideUp(300);
    });
</script>


<?php /**PATH C:\xampp\htdocs\banner_project\resources\views/backend/layouts/messages.blade.php ENDPATH**/ ?>