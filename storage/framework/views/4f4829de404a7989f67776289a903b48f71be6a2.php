<?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="accordion accordion-flush" id="accordionFlushExample-<?php echo e($team->id); ?>">
        <div class="accordion-item">
            <h2 class="accordion-header individual-teams" id="flush-headingOne-<?php echo e($team->id); ?>" team_id="<?php echo e($team->id); ?>">
                <button class="accordion-button collapsed disable-individual" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne-<?php echo e($team->id); ?>" aria-expanded="false" aria-controls="flush-collapseOne-<?php echo e($team->id); ?>">
                    <?php echo e($team->name); ?>

                </button>
            </h2>
            <div id="flush-collapseOne-<?php echo e($team->id); ?>" class="accordion-collapse collapse" aria-labelledby="flush-headingOne-<?php echo e($team->id); ?>" data-bs-parent="#accordionFlushExample-<?php echo e($team->id); ?>">
                <div class="accordion-body">
                    <div style="overflow-y: auto; height: 100px;">
                        <div class="all-div">
                            <label class="container" for="<?php echo e($team->id); ?>-all">
                                All
                                <input type="checkbox" id="<?php echo e($team->id); ?>-all" class="checkbox checkbox-all individual_values">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <?php $__currentLoopData = $team->members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(isset($member->user->name) && ($member->user->status > 0) && ($member->user->id != \Illuminate\Support\Facades\Auth::user()['id'])): ?>
                                <?php $hasAssignee = \App\Modals\Task::hasAssignee($task->assignees, $member); ?>
                                <div class="<?php echo e($team->id); ?>-all">
                                    <label class="container" for="<?php echo e($team->id); ?><?php echo e($member->user_id); ?>">
                                        <?php echo e(empty($member->user->name) ? '' : $member->user->name); ?><?php echo e(empty($member->user->email) ? '' : (' (' . $member->user->email . ')')); ?>

                                        <input type="checkbox" value="<?php echo e($member->team_id); ?>" style="display: none" class="checkbox" <?php echo e($hasAssignee); ?>>
                                        <input type="checkbox" id="<?php echo e($team->id); ?><?php echo e($member->user_id); ?>" class="checkbox checkbox-member individual-<?php echo e($team->id); ?>" value="<?php echo e($member->user_id); ?>" <?php echo e($hasAssignee); ?>>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH /home/bikroy/public_html/project_management_tool/resources/views/backend/tasks/fetch_individual_reassign.blade.php ENDPATH**/ ?>