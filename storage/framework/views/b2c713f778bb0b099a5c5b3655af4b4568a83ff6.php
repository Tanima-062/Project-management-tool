<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Task Management Platform</title>
    <?php echo $__env->make('backend.layouts.styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>
<body>
<div class="container-scroller task-create-page">
    <div class="loading">
        <div class="loader"></div>
    </div>
    <!-- partial:partials/_navbar.html -->
    <?php echo $__env->make('backend.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="container-fluid page-body-wrapper">
        <?php echo $__env->make('backend.layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="main-panel task-create-main-panel">
            <div class="content-wrapper">
                <div id="alert-show">
                    <?php echo $__env->make('backend.layouts.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <div class="RequestTaskBox card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <b class="ml-3">Requested By</b>
                            <div class="media" style="align-items: center;">
                                <div class="media-left">
                                <?php if(isset($task->requestFrom->image) && file_exists(public_path('backend/uploads/profile_images/'.$task->requestFrom->id.'/'.$task->requestFrom->image))): ?>
                                    <img src="<?php echo e(asset('backend/uploads/profile_images/'.$task->requestFrom->id.'/'.$task->requestFrom->image)); ?>" height="55" width="55" style="border-radius: 50%">
                                <?php else: ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" height="55" viewBox="0 0 24 24" width="55"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>  
                                <?php endif; ?>
                                </div>
                                <div class="media-body">&nbsp;&nbsp;<?php echo e(empty($task->requestFrom->name) ? '' : $task->requestFrom->name); ?></div>
                            </div>
                            <br>
                        </div>
                    </div>    
                </div>
                <div class="CreateTaskBox card-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                        <h3 class="task-create-title"><b>Propose Task</b></h3>
                        </div>
                        <div class="col-md-12">
                            <form method="POST" action="<?php echo e(route('otherRequestTasks.update',$task->id)); ?>" enctype="multipart/form-data" id="taskForm">
                                <?php echo method_field('PUT'); ?>   
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="taskId" value="<?php echo e($task->id); ?>">
                                <div class="form-group">
                                    <label>Task Title <span class="mandatory">*</span></label>
                                    <input type="text" class="form-control custom-input" id="title" name="title"autocomplete="off" value="<?php echo e($task->title); ?>">
                                    <p class="error title-error"></p>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label>Allocated Time</label>
                                        <input type="text" class="form-control custom-input" name="allocated_time" value="<?php echo e($task->allocated_time); ?>" onkeyup="if (/[^0-9\.]/g.test(this.value)) this.value = this.value.replace(/[^0-9\.]/g,'')">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Tags</label>
                                        <select class="form-control col-md-12 custom-input select2" name="tags[]" multiple>
                                            <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if(collect(old('tags'))->contains($tag->id)): ?>)
                                                    <?php $hasTag = 'selected'; ?>
                                                <?php else: ?>
                                                    <?php $hasTag = \App\Modals\Task::hasTag($tag->id, $task->taskTags); ?>
                                                <?php endif; ?>
                                                <option value="<?php echo e($tag->id); ?>" <?php echo e($hasTag); ?>><?php echo e($tag->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>    
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control custom-input ckeditor" id="description" name="description" rows="3"autocomplete="off"><?php echo e($task->description); ?></textarea>
                                    <p class="error description-error"></p>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label>Start Date</label>
                                        <div class='right-inner-addon date datepicker'>
                                            <i class="fa fa-calendar-o date-picker"></i>
                                            <input name='start_date' value="<?php echo e(date('d M, Y', strtotime($task->start_date))); ?>"  data-date="" data-date-format="d M, yyyy" type="text" class="form-control date-picker date-picker-input" autocomplete="off" readonly id="start_date"/>
                                        </div>
                                        <p class="error start_date-error"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label>End Date</label>
                                        <div class='right-inner-addon date datepicker'>
                                            <i class="fa fa-calendar-o date-picker"></i>
                                            <input name='end_date' value="<?php echo e(date('d M, Y', strtotime($task->end_date))); ?>"  data-date="" data-date-format="d M, yyyy" type="text" class="form-control date-picker date-picker-input" autocomplete="off" readonly id="end_date"/>
                                        </div>
                                        <p class="error end_date-error"></p>
                                    </div>
                                    <div class="col-md-4 task-create-page-priority">
                                        <label>Priority <span class="mandatory">*</span></label>
                                        <select class="form-control priority-input" name="priority" id="priority">
                                            <option value="2" <?php echo e($task->priority == 2 ? 'Selected' : ''); ?>>High</option>
                                            <option value="1" <?php echo e($task->priority == 1 ? 'Selected' : ''); ?>>Medium</option>
                                            <option value="0" <?php echo e($task->priority == 0 ? 'Selected' : ''); ?>>Low</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Attachments</label>
                                    <?php if(count($task->attachments) > 0): ?>
                                    <div>
                                    <?php $__currentLoopData = $task->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="existing-files" id="<?php echo e($attachment->file_name); ?>" style="margin-right:20px;">
                                            <?php echo e($attachment->file_name); ?>

                                            <i class="fa fa-times"></i>
                                        </span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <?php endif; ?>
                                    <div class="filepond-error"></div>
                                    <input type="file" name="attachments[]" multiple>
                                </div>
                                <div class="tab-pane fade show active taskCommentsSection" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <?php if(count($task->comments) > 0): ?>
                                        <?php $__currentLoopData = $task->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="media comment">
                                                <div class="media-left">
                                                    <?php if(isset($comment->user->image) && file_exists(public_path('backend/uploads/profile_images/'.$comment->user->id.'/'.$comment->user->image))): ?>
                                                        <img src="<?php echo e(asset('backend/uploads/profile_images/'.$comment->user->id.'/'.$comment->user->image)); ?>" height="40" width="40" style="border-radius: 50%">
                                                        &nbsp;&nbsp;
                                                    <?php else: ?>
                                                        <svg xmlns="http://www.w3.org/2000/svg" height="45" width="45" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                                                        &nbsp;&nbsp;
                                                    <?php endif; ?>
                                                </div>
                                                <div class="media-body">
                                                    <div class="comment-detail-<?php echo e($comment->id); ?>">
                                                        <p>
                                                            <span class="comment-by"><?php echo e(empty($comment->user->name) ? '' : $comment->user->name); ?></span>
                                                            <span class="Hour-ago"><?php echo e(empty($comment->created_at) ? '' : date_format($comment->created_at, 'd M, Y h:i:s A')); ?></span>
                                                        </p>
                                                        <p><?php echo $comment->text; ?></p>
                                                    </div>
                                            </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group comment-div">
                                    <label>Comments</label>
                                    <textarea class="form-control task-comment-area ckeditor" id="comment" name="comment" rows="3" placeholder="Your comments here"></textarea>
                                </div>
            
                                <div class="text-center mt-4">
                                    <a onClick="rejectBtn()" class="btn btn-danger button task-reject-button" style="border-radius:10%;">Reject</a>
                                    <button class="btn custom-btn" style="height:40px;">Accept</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="rejectModal" style="display:none;">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <form method="POST" action="<?php echo e(route('otherRequestTasks.reject')); ?>" id="rejectForm">         
                                    <?php echo csrf_field(); ?>    
                                    <div class="form-group">
                                        <label for="reason"><b>Reason For</b></label>
                                        <select id="reason" name="reason" class="form-control">
                                            <?php $__currentLoopData = $reasons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reason): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($reason->name); ?>" <?php echo e($reason->name == 'others' ? 'Selected' : ''); ?>><?php echo e(empty($reason->displayName) ? '' : $reason->displayName); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="offset-md-6"> 
                                            <a class="btn custom-outline-btn close-btn">Close</a>
                                            <button class="btn custom-btn save-btn">Save</button>  
                                        </div>   
                                    </div> 
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
            
            <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
        <!-- container-scroller -->
        <?php echo $__env->make('backend.layouts.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <script>
            $('.select2').select2();
            CKEDITOR.editorConfig = function( config ) {
                config.fullPage = true;
            };
            const input = document.querySelector("input[type='file']");
            const pond = FilePond.create(input);
            const pondBox = document.querySelector('.filepond--root');
            var filenames = [];
            var existing_files = [];
    
            pond.setOptions({
                server:{
                    process: {
                        url: '/project_management_tool/uploadFiles',
                        headers: {
                            'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
                        }
                    }
                }
            });
            pond.on('addfile',
                function(error, file){
                    if(filenames.includes(file.filename)){
                        error = {
                            main: 'duplicate',
                            sub: 'A file with that name already exists in the pond.'
                        }
                        handleFileError(error, file);
                    }
                    if(error) handleFileError(error, file);
                    filenames.push(file.filename);
                });

            pond.on('removefile',
                function(error, file){
                    var index = filenames.indexOf(file.filename);
                    filenames.splice(index, 1);
                });

            function handleFileError(error, file){
                let err = document.querySelector(".filepond-error");
                err.innerHTML = file.filename + " cannot be loaded " + error.sub;
                pond.removeFile(file);
            }

            $('#title').keyup(function (){
                $(".title-error").html("");
            });

            $('#description').keyup(function (){
                $(".description-error").html("");
            });

            $("#start_date").click(function (){
                $(".start_date-error").html("");
            });

            $("#end_date").click(function (){
                $(".end_date-error").html("");
            });
            

           $(".fa-calendar-o").on("click", function(){
                $(this).siblings("input").datepicker({
                    forceParse:false,
                    autoclose: true,
                    immediateUpdates: true,
                    todayBtn: true,
                    todayHighlight: true
                });
               $(this).siblings("input").datepicker('show');
           });

           $(".save-btn").click(function (e) {
                e.preventDefault();
                $(".Create").prop("disabled",true);
                $('.error').html('');
                var formData = $('#taskForm').serializeArray();
                var description = CKEDITOR.instances.description.getData(); 
                var comment = CKEDITOR.instances.comment.getData(); 
                var flag = '';
                if($('#title').val() == ''){
                    $(".title-error").html("Title field is required");
                    flag = 'title';
                }
                if(($('#start_date').val() != '') && ($('#end_date').val() != '')){
                    var startDateTimeInMillis = Date.parse($('#start_date').val());
                    var endDateTimeInMillis = Date.parse($('#end_date').val());
                    if(startDateTimeInMillis > endDateTimeInMillis){
                        $(".start_date-error").html("Start Date should smaller than end date");
                        flag = 'start_date_not_equal';
                    }
                }
                
                if(flag == '') {
                    $('.loading').show();
                    formData.push({name: "description", value: description});
                    formData.push({name: "comment", value: comment});
                    for (let i = 0; i < existing_files.length; i++) {
                        formData.push({name: "existing_attachments[]", value: existing_files[i]});
                    }
                    for (let i = 0; i < filenames.length; i++) {
                        formData.push({name: "attachments[]", value: filenames[i]});
                    }

                    $.ajax({
                        url: $("#taskForm").attr('action'),
                        method: $("#taskForm").attr('method'),
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: formData,
                        success: function (data) {
                            console.log(data);
                            $('.loading').hide();
                            if (data.success == true) {
                                window.location = '/project_management_tool/otherRequestTasks';
                            }
                        },
                        error: function (xhr, status, errorThrown) {
                            $('.loading').hide();
                            console.log(xhr.responseText);
                            //Here the status code can be retrieved like;
                            // xhr.status;

                            //The message added to Response object in Controller can be retrieved as following.
                            alert(xhr.responseText);
                        }
                    });
                }else{
                    $(".Create").prop("disabled",false);
                }

            });
            
            $(document).ready(function() {
                $('.existing-files').each(function(){
                    existing_files.push($(this).attr('id'));
                });
                $('.fa-times').click(function(){
                    var filename = $(this).closest('.existing-files').attr('id');
                    var index = existing_files.indexOf(filename);
                    existing_files.splice(index, 1);
                    $(this).closest('.existing-files').remove();
                });
                $(window).keydown(function(event){
                    if(event.keyCode == 13) {
                        event.preventDefault();
                        return false;
                    }
                });
            });

        
            $('#rejectForm').submit(function(e){
                e.preventDefault();
                $("#rejectModal").modal('hide');

                var formData = $('#taskForm').serializeArray();
                formData.splice(0,1);
                console.log(formData);
                var description = CKEDITOR.instances.description.getData(); 
                var comment = CKEDITOR.instances.comment.getData(); 
                
                $('.loading').show();
                formData.push({name: "description", value: description});
                formData.push({name: "comment", value: comment});
                for (let i = 0; i < existing_files.length; i++) {
                    formData.push({name: "existing_attachments[]", value: existing_files[i]});
                }
                for (let i = 0; i < filenames.length; i++) {
                    formData.push({name: "attachments[]", value: filenames[i]});
                }
                var reason = $("#reason").val(); 

                formData.push({name: "reason", value: reason});
                formData.push({name: "comment", value: comment});

                $.ajax({
                    url: $("#rejectForm").attr('action'),
                    method: $("#rejectForm").attr('method'),
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: formData,
                    success: function (data) {  
                        $('.loading').hide();
                        if (data.success == true) {
                            window.location = '/project_management_tool/otherRequestTasks';
                        }
                    }
                });
            })

            function rejectBtn(){
                event.preventDefault();
                var formData = $('#taskForm').serializeArray();
                var description = CKEDITOR.instances.description.getData(); 
                var comment = CKEDITOR.instances.comment.getData(); 
                var flag = '';
                if($('#title').val() == ''){
                    $(".title-error").html("Title field is required");
                    flag = 'title';
                }
                if(($('#start_date').val() != '') && ($('#end_date').val() != '')){
                    var startDateTimeInMillis = Date.parse($('#start_date').val());
                    var endDateTimeInMillis = Date.parse($('#end_date').val());
                    if(startDateTimeInMillis > endDateTimeInMillis){
                        $(".start_date-error").html("Start Date should smaller than end date");
                        flag = 'start_date_not_equal';
                    }
                }
                
                if(flag == '') {
                    $("#rejectModal").modal('show');
                }
            }

            $('.close-btn').click(function(){
                $("#rejectModal").modal('hide');
            });

        </script>

</body>

</html>
<?php /**PATH C:\xampp\htdocs\project_management_tool\resources\views/backend/other_request_tasks/update_request.blade.php ENDPATH**/ ?>