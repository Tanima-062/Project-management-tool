<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Task Management Platform</title>
    <?php echo $__env->make('backend.layouts.styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>
<body>
<div class="container-scroller task-reassign-page">
    <div class="loading">
        <div class="loader"></div>
    </div>
    <!-- partial:partials/_navbar.html -->
    <?php echo $__env->make('backend.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="container-fluid page-body-wrapper">
        <?php echo $__env->make('backend.layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="main-panel task-reassign-panel">
            <div class="content-wrapper">
                <div id="alert-show">
                    <?php echo $__env->make('backend.layouts.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <div class="CreateTaskBox">
                <div class="Task-reassign-title">Reassign</div>
                    <form method="POST" action="<?php echo e(route('tasks.reassign.update',$task->id)); ?>" enctype="multipart/form-data" id="taskForm">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" id="taskId" value="<?php echo e($task->id); ?>">
                        <div class="form-group task-title-group">
                            <label>Task Title <span class="mandatory">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo e($task->title); ?>" disabled>
                            <p class="error title-error"></p>
                        </div>
                        <div class="form-group task-description-group">
                            <label>Description <span class="mandatory">*</span></label>
                            <textarea class="form-control ckeditor" id="description" name="description" rows="3" disabled><?php echo e($task->description); ?></textarea>
                            <p class="error description-error"></p>
                        </div>
                        <div class="form-group row start-end-priority">
                            <div class="col-sm-4">
                                <label>Start Date <span class="mandatory">*</span></label>
                                <div class='right-inner-addon date datepicker'>
                                    <i class="fa fa-calendar-o date-picker"></i>
                                    <input name='start_date' value="<?php echo e(date('d M, Y', strtotime($task->start_date))); ?>" type="text" class="form-control date-picker" id="start_date" autocomplete="off" readonly disabled/>
                                </div>
                                <p class="error start_date-error"></p>
                            </div>
                            <div class="col-sm-4">
                                <label>End Date <span class="mandatory">*</span></label>
                                <div class='right-inner-addon date datepicker'>
                                    <i class="fa fa-calendar-o date-picker"></i>
                                    <input name='end_date' value="<?php echo e(date('d M, Y', strtotime($task->end_date))); ?>" type="text" class="form-control date-picker" id="end_date" autocomplete="off" readonly disabled/>
                                </div>
                                <p class="error end_date-error"></p>
                            </div>
                            <div class="col-sm-4">
                                <label>Priority <span class="mandatory">*</span></label>
                                <select class="col-sm-12 task-priority" name="priority" id="priority" disabled>
                                    <option value="2" <?php echo e($task->priority == 2 ? 'Selected' : ''); ?>>High</option>
                                    <option value="1" <?php echo e($task->priority == 1 ? 'Selected' : ''); ?>>Medium</option>
                                    <option value="0" <?php echo e($task->priority == 0 ? 'Selected' : ''); ?>>Low</option>
                                </select>
                            </div>
                        </div>

                        

                        

                        

                        <div class="form-group row">
                            <div class="col-md-12">
                                 
                            </div>
                            <div class="col-md-12" style="display:none;">
                                <div class="radio-item">
                                    <input type="radio" id="individual" name="assign_to" value="individual" class="assignee" checked>
                                    <label for="individual">Individual</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row assign-to-block">
                            
                            <div class="col-md-12 pl-4">
                                <label>Assign To Individual(s)</label>
                                <div class="individualAssignToBox Task-Assign-To" style="opacity:1;">
                                    <div id="input_container">
                                        <input type="text" id="input" placeholder="Search for individual..." class="disable-individual individual-search">
                                        <hr>
                                        <i class="fa fa-search" id="input_img"></i>
                                        <div class="individual-section">
                                            <?php echo $__env->make('backend.tasks.fetch_individual_reassign', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        </div>
                                    </div>
                                </div>
                                <p class="error assignee-error individual-error"></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Attachments</label>
                            <div class="filepond-error"></div>
                            <input type="file" name="attachments[]" multiple disabled>
                        </div>

                        <div class="form-group">
                            <label>Comments</label>
                            <textarea class="form-control task-comment-area ckeditor" id="comment" name="comment" rows="3" placeholder="Your comments here" disabled></textarea>
                        </div>

                        <div class="text-center mt-4">
                            <a href="<?php echo e(route('tasks.show',$task->id)); ?>" class="btn custom-outline-btn">Cancel</a>
                            <button class="btn custom-btn">Save</button>
                        </div>
                    </form>
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
            const input = document.querySelector("input[type='file']");
            const pond = FilePond.create(input);
            var individuals = {};
            var teamIds = [];
            
            $("#taskForm").submit(function (e) {
                e.preventDefault();
                $(".Create").prop("disabled",true);
                $('.error').html('');
                var formData = $('#taskForm').serializeArray();
                var flag = '';
               
                if($('#team').is(':checked')){
                    var hasTeam = '';
                    for(var i=0; i < formData.length; i++){
                        if(formData[i].name === 'teams[]'){
                            hasTeam = 'team';
                            break;
                        }
                    }
                    if(hasTeam == ''){
                        $('.team-error').html('Please Select a Team')
                        flag='team';
                    }
                }
                if($('#individual').is(':checked')){
                    var hasAssignee = '';
                    for(var j=0; j < teamIds.length; j++){
                        var index = teamIds[j];
                        var userIds = individuals[index];
                        if(userIds.length > 0){
                            hasAssignee = 'assignee';
                            break;
                        }
                    }
                    if(hasAssignee == ''){
                        $('.individual-error').html('Please Select a Assignee')
                        flag='assignee';
                    }
                }

                if(flag == '') {
                    $('.loading').show();

                    for(var j=0; j < teamIds.length; j++){
                        var index = teamIds[j];
                        var userIds = individuals[index];
                        if(userIds.length > 0){
                            for(var i=0; i < userIds.length; i++){
                                formData.push({name: "individual_from_teams[]", value: index});
                                formData.push({name: "individuals[]", value: userIds[i]});
                            }
                        }
                    }

                    $.ajax({
                        url: $("#taskForm").attr('action'),
                        method: $("#taskForm").attr('method'),
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: formData,
                        success: function (data) {
                            console.log(data);
                            if (data.success == true) {
                                window.location = '/project_management_tool/tasks/'+$("#taskId").val();
                            }
                        }
                    });
                }else{
                    $(".Create").prop("disabled",false);
                }

            });
            $(document).ready(function(){

                memberSelect();
                
                // if($('#task-assign-to').val() == 'individual'){
                //     $('.disable-individual').prop("disabled", false);
                //     $('.disable-team').prop("disabled", true);
                // }

                // $('.date-picker').trigger('click');
                // $('.active').trigger('click');

                $('.individual-teams').each(function(){
                    var team_id = $(this).attr('team_id');
                    teamIds.push(team_id);
                    individuals[team_id] = [];

                    $('.individual-'+team_id).each(function(){
                        if($(this).is(':checked')){
                            var value = $(this).val();
                            individuals[team_id].push(value);
                        }
                    });
                });

                $(window).keydown(function(event){
                    if(event.keyCode == 13) {
                        event.preventDefault();
                        return false;
                    }
                });

            });
            $(".fa-calendar-o").on("click", function(){
               $(this).siblings("input").datepicker({
                   forceParse:false,
                    autoclose: true,
                    immediateUpdates: true,
                    todayBtn: true,
                    todayHighlight: true
               });

                // if(!$(this).siblings("input").val()) {
                //     $(this).siblings("input").datepicker('update', new Date());
                // }
               $(this).siblings("input").datepicker('show');
           });

           $('.date-picker').on("change",function(){
                const d = $(this).val().split('/');
                const date = d[1] + " " +monthNames[Number(d[0]-1)] + ', '+ d[2]+ ' ';
                $(this).val(date);
           });

            $("#individual").click(function (){
                $(".individualAssignToBox").css("opacity", "1");
                $(".teamAssignToBox").css("opacity", "0.3");
                $('.disable-individual').prop("disabled", false);
                $('.disable-team').prop("disabled", true);
            });
            $("#team").click(function (){
                $(".teamAssignToBox").css("opacity", "1");
                $(".individualAssignToBox").css("opacity", "0.3");
                $('.disable-team').prop("disabled", false);
                $('.disable-individual').prop("disabled", true);
            });

            $(".team-search").keyup(function (){
                const page = $('.page_reassign_team').val();
                const taskId = $("#taskId").val();
                const query = $(this).val();
                $.ajax({
                    url: '/team/search',
                    method: 'POST',
                    data: {query:query,page:page,taskId:taskId},
                    success: function (data) {
                        $('.team-section').html(data.view);
                        $('.disable-team').prop("disabled", false);
                    }
                });
            });

            $(".individual-search").keyup(function (){
                const taskId = $("#taskId").val();
                const query = $(this).val();
                $.ajax({
                    url: '/project_management_tool/individual/search',
                    method: 'POST',
                    data: {query:query,taskId:taskId},
                    success: function (data) {
                        $('.individual-section').html(data.view);
                        if($('.individual-search').val() != ''){
                            $('.accordion-collapse').addClass('show');
                        }
                        $('.disable-individual').prop("disabled", false);
                        $('.checkbox').prop('checked',false);
                        for(var j=0; j < teamIds.length; j++){
                            var index = teamIds[j];
                            var userIds = individuals[index];
                            if(userIds.length > 0){
                                for(var i=0; i < userIds.length; i++){
                                    $("#"+index+userIds[i]).prop('checked',true);
                                    $("#"+index+userIds[i]).prev().prop('checked', true);
                                }
                            }
                        }
                        memberSelect();
                    }
                });
            });

            function memberSelect(){
                $('.checkbox').click(function (){
                    if($(this).is(':not(:checked)')){
                        $(this).prev().prop('checked', false);
                        var index = $(this).prev().val();
                        var value = $(this).val();
                        if(individuals[index].indexOf(value) > -1){
                            var removeIndex = individuals[index].indexOf(value);
                            individuals[index].splice(removeIndex,1);
                        }
                    }else{
                        $(this).prev().prop('checked', true);
                        var index = $(this).prev().val();
                        var value = $(this).val();
                        if(individuals[index].indexOf(value) == -1){
                            individuals[index].push(value);
                        }
                    }
                });
            }
        </script>

</body>

</html>

<?php /**PATH /home/bikroy/public_html/project_management_tool/resources/views/backend/tasks/reassign.blade.php ENDPATH**/ ?>