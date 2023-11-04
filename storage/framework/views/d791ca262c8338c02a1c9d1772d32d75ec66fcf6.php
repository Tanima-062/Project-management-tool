<table class="table table-responsive" id="userList">
    <thead>
        <tr>
           <th>SL No.<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="id"></i></th>
            <th style="width:25%">Full Name<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="name"></i></th>
            <th>Designation<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="designation"></i></th>
            <th style="width:25%;">Department</th>
            <th>Email Address<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="email"></i></th>
            <th>Phone No<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="phone_number"></i></th>
            <th>Role<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="role"></i></th>
            <th>Status<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="status"></i></th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(isset($user)): ?>
            <tr>
                <td><?php echo e(empty($sort_id) ? ++$serial : $serial--); ?></td>
                <td><?php echo e($user->name); ?></td>
                <td><?php echo e($user->designation); ?></td>
                <td>
                   <?php $__currentLoopData = $user->userPrograms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user_program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <p><?php echo e($user_program->program->name); ?></p>
                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
                <td><?php echo e($user->email); ?></td>
                <td><?php echo e($user->phone_number); ?></td>
                <td><?php echo e($user->role); ?></td>
                <td><?php echo e(($user->status=='1') ? 'Active' : 'Inactive'); ?></td>
                <td>
                    <?php if(\App\Modals\User::hasSpecificPermission(\Illuminate\Support\Facades\Auth::user(),'user.edit')): ?>
                        <a class="Rectangle-Edit btn-hover2" style="text-decoration: none;" href="<?php echo e(route('users.edit', $user->id)); ?>"><i class="fa fa-pencil" style="margin-right: 5px;"></i>Edit</a>
                    <?php endif; ?>
                    <?php if(\App\Modals\User::hasSpecificPermission(\Illuminate\Support\Facades\Auth::user(),'user.delete')): ?>
                        <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="<?php echo e(route('users.destroy', $user->id)); ?>"
                           onclick="deleteData('delete-form-<?php echo e($user->id); ?>');"><i class="fa fa-trash"></i>
                            Delete
                        </a>

                        <form id="delete-form-<?php echo e($user->id); ?>" action="<?php echo e(route('users.destroy', $user->id)); ?>" method="POST" class="d-none" style="display: none">
                            <?php echo method_field('DELETE'); ?>
                            <?php echo csrf_field(); ?>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<div class="user-list-pagination" id="pageUser" style="margin-top:20px;">
        <?php if(count($users) > 0): ?>
            <?php if((($users->currentPage()-1)*$users->perPage())+$users->perPage() < $users->total()): ?>
                Showing from <?php echo e((($users->currentPage()-1)*$users->perPage())+1); ?> to <?php echo e((($users->currentPage()-1)*$users->perPage())+$users->perPage()); ?> of <?php echo e($users->total()); ?> 
            <?php else: ?>
                Showing from <?php echo e((($users->currentPage()-1)*$users->perPage())+1); ?> to <?php echo e($users->total()); ?> of <?php echo e($users->total()); ?> 
            <?php endif; ?>
        <?php else: ?>
            Showing from 0 to 0 of 0
        <?php endif; ?>        
            <?php echo e($users->links()); ?>

</div>
<script>
    function deleteData(id){
        event.preventDefault();
        swal({
                title: "Are you sure?",
                type: "error",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "CONFIRM",
                cancelButtonText: "CANCEL",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function() {
                $.ajax({
                    url: $("#" + id).attr('action'),
                    method: 'POST',
                    data: $("#" + id).serializeArray(),
                    success: function (data) {
                        location.reload();
                    }
                });
            }
        );
    }
</script>
<?php /**PATH /home/bikroy/public_html/project_management_tool/resources/views/backend/users/fetch_user.blade.php ENDPATH**/ ?>