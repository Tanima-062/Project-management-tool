<h3 class="users-list-title">Programme Task Details</h3>
    <hr class="Dash-Line">
    
    <div class="card directSuperviseStatusCard">
        <div class="card analytics-task-status-crd">
            <div class="row">
                
                <div class="col-md-2 col-sm-3">
                    <div class="overall-task-img pl-2">
                        <img src="<?php echo URL::to('public/backend/assets/img/stl_report.png'); ?>" class="">
                    </div>
                </div>
                <div class="col-md-5 col-sm-9 overall-task-quantity">
                    
                            <div class="row">
                                <div class="col pl-sm-0">
                                    <h4 class="task-quantity-title">Members</h4>
                                    <h3 class="task-quantity-number Members"><?php echo e($memberCount); ?></h3>
                                </div>
                                <div class="col pl-sm-0">
                                    <h4 class="task-quantity-title">Tasks</h4>
                                    <h3 class="task-quantity-number Tasks"><?php echo e($overall_task_count['tasks']); ?></h3>
                                </div>
                                <div class="col pl-sm-0">
                                    <h4 class="task-quantity-title">Pending</h4>
                                    <h3 class="task-quantity-number Pending"><?php echo e($overall_task_count['pending']); ?></h3>
                                </div>
                                <div class="col pl-sm-0">
                                    <h4 class="task-quantity-title">Accepted</h4>
                                    <h3 class="task-quantity-number Accepted"><?php echo e($overall_task_count['accepted']); ?></h3>
                                </div>
                                <div class="col pl-sm-0">
                                    <h4 class="task-quantity-title">In Progress</h4>
                                    <h3 class="task-quantity-number In-Progress"><?php echo e($overall_task_count['inProgress']); ?></h3>
                                </div>
                            </div>
                    
                </div>
                <div class="col-md-3 col-sm-9 overall-task-quantity">
                    <div class="row">
                
                        <div class="col-4 p-md-0">
                            <h4 class="task-quantity-title">Completed</h4>
                            <h3 class="task-quantity-number Completed"><?php echo e($overall_task_count['completed']); ?></h3>
                        </div>
                        <div class="col-4 pl-sm-0">
                            <h4 class="task-quantity-title">Rejected</h4>
                            <h3 class="task-quantity-number Rejected"><?php echo e($totalRejected); ?></h3>
                        </div>
                        <div class="col-4 pl-sm-0">
                            <h4 class="task-quantity-title">Overdue</h4>
                            <h3 class="task-quantity-number overdue"><?php echo e($overall_task_count['overdue']); ?></h3>
                        </div>            
                           
                    </div>
                </div>
                <div class="col-md-2 col-sm-3 program-wise-member-images">
                    <div class="kanban-card-footer task-card-body" >
                        <div class="d-flex">
                            <?php $__currentLoopData = $totalMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(isset($member)): ?>
                                    <?php if(!empty($member->image) && (file_exists(public_path('backend/uploads/profile_images/'.$member->id.'/'.$member->image)))): ?>
                                        <img src="<?php echo URL::to('public/backend/uploads/profile_images/'.$member->id.'/'.$member->image); ?>" class="team-card-member-image">
                                    <?php else: ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="kanban-card-member-image"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if($key == 3): ?>  <?php break; ?>;  <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if(count($totalMembers)>4): ?>
                                <div  class="team-card-member-image team-card-member-image-addition-count">+<?php echo e(count($totalMembers) - 4); ?></div>
                            <?php endif; ?>                                                         
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row DataTableBox">
            <div class="row role-list-table-subHeader pr-0">
                <div class="col-sm-5 subHeader-col-1">
                    <div class="form-inline">
                        <span>
                            <b>Individual Task Details</b>
                        </span>
                        <input class="form-control supervise-status-search mr-sm-2 " type="search" id="member_search" placeholder="Search..." aria-label="Search">
                    </div>
                </div>
                <input type="hidden" name="member-order" id="member_order" value="order">
                <input type="hidden" name="member-coloumn" id="member_coloumn">
                <div class="col-sm-7 subHeader-col-2 pr-0">
                    <a href="<?php echo e(route('members.export')); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="34.253" viewBox="0 0 116.299 34.253">
                            <defs>
                                <style>
                                    .cls-3{fill:#576271}
                                </style>
                            </defs>
                            <g id="Export_Btn_with_Icon" transform="translate(-1712.137 -1010)">
                                <g id="Rectangle_57" fill="#fbfbfb" stroke="#586372" transform="translate(1712.137 1010)">
                                    <rect width="116.299" height="34.253" stroke="none" rx="7"/>
                                    <rect width="115.299" height="33.253" x=".5" y=".5" fill="none" rx="6.5"/>
                                </g>
                                <text id="Export" fill="#576271" font-family="OpenSans-Regular, Open Sans" font-size="17px" transform="translate(1776.711 1033)">
                                    <tspan x="-25.994" y="0">Export</tspan>
                                </text>
                                <g id="Export_Icon" transform="translate(1724.555 1018.585)">
                                    <path id="Path_503" d="M29.983 1.2a4.116 4.116 0 0 0-5.814 0l-2.907 2.909a.632.632 0 1 0 .894.891l2.907-2.9a2.846 2.846 0 0 1 4.025 4.025l-3.8 3.8a2.85 2.85 0 0 1-4.025 0 .632.632 0 1 0-.894.894 4.116 4.116 0 0 0 5.814 0l3.8-3.8a4.121 4.121 0 0 0 0-5.814z" class="cls-3" transform="translate(-13.801 -.001)"/>
                                    <path id="Path_504" d="M8.582 24.917l-2.46 2.46A2.846 2.846 0 0 1 2.1 23.352l3.578-3.578a2.857 2.857 0 0 1 4.025 0 .632.632 0 0 0 .894-.894 4.116 4.116 0 0 0-5.814 0L1.2 22.458a4.111 4.111 0 0 0 5.814 5.813l2.46-2.46a.632.632 0 0 0-.894-.894z" class="cls-3" transform="translate(-.003 -12.087)"/>
                                </g>
                            </g>
                        </svg>
                    </a>
                </div>
            </div>
            <div>
                <hr>
            </div>
            <div class= "member-table">
            <div class="table-responsive" style="margin-bottom: 20px;">
                <?php echo $__env->make('backend.task_analytics.member', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
            </div>
        </div>
    </div><?php /**PATH C:\xampp\htdocs\project_management_tool\resources\views/backend/task_analytics/supervise_status.blade.php ENDPATH**/ ?>