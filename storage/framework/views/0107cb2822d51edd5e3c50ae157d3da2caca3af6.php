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
                <div class="CreateTaskBox card-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                        <h3 class="task-create-title"><b>Propose Task</b></h3>
                        </div>
                        <div class="col-md-12">
                            <form method="POST" action="<?php echo e(route('myRequestTasks.store')); ?>" enctype="multipart/form-data" id="taskForm">
                                <?php echo csrf_field(); ?>
                                <div class="form-group">
                                    <label>Task Title <span class="mandatory">*</span></label>
                                    <input type="text" class="form-control custom-input" id="title" name="title"autocomplete="off">
                                    <p class="error title-error"></p>
                                </div>
                                <div class="form-group">
                                    <label>Allocated Time</label>
                                    <input type="text" class="form-control custom-input" name="allocated_time" onkeyup="if (/[^0-9\.]/g.test(this.value)) this.value = this.value.replace(/[^0-9\.]/g,'')">
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control custom-input ckeditor" id="description" name="description" rows="3"autocomplete="off"></textarea>
                                    <p class="error description-error"></p>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label>Start Date</label>
                                        <div class='right-inner-addon date datepicker'>
                                            <i class="fa fa-calendar-o date-picker"></i>
                                            <input name='start_date' value="" type="text" class="form-control date-picker date-picker-input" autocomplete="off" readonly id="start_date"/>
                                        </div>
                                        <p class="error start_date-error"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label>End Date</label>
                                        <div class='right-inner-addon date datepicker'>
                                            <i class="fa fa-calendar-o date-picker"></i>
                                            <input name='end_date' value="" type="text" class="form-control date-picker date-picker-input" autocomplete="off" readonly id="end_date"/>
                                        </div>
                                        <p class="error end_date-error"></p>
                                    </div>
                                    <div class="col-md-4 task-create-page-priority">
                                        <label>Priority <span class="mandatory">*</span></label>
                                        <select class="form-control priority-input" name="priority" id="priority">
                                        <option value="2">High</option>
                                        <option value="1">Medium</option>
                                        <option value="0" selected>Low</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                       
                                    </div>
                                    <div class="col-md-12">
                                        <div class="radio-item" style="display:none;">
                                            <input type="radio" id="individual" name="assign_to" value="individual" class="assignee" checked>
                                            <label for="individual">Individual</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Attachments</label>
                                    <div class="filepond-error"></div>
                                    <input type="file" name="attachments[]" multiple>
                                </div>
                                <div class="form-group">
                                    <label>Comments</label>
                                    <textarea class="form-control comments-text-area ckeditor" id="comment" name="comment" rows="3" placeholder="Your comments here"></textarea>
                                </div>
                                <div class="text-center mt-4">
                                    <a class="btn custom-outline-btn" href="<?php echo e(route('myRequestTasks.index')); ?>">Cancel</a>
                                    <button class="btn custom-btn save-btn">Save</button>
                                </div>
                            </form>
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
        CKEDITOR.editorConfig = function( config ) {
            config.fullPage = true;
        };
            const input = document.querySelector("input[type='file']");
            const pond = FilePond.create(input);
            const files = [];
            // const pondBox = document.querySelector('.filepond--root');
            var filenames = [];
            var individuals = {};
            var teamIds = [];

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
                        //handleFileError(error, file);
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

            const monthNames = ["January", "February", "March", "April", "May", "June",
                                    "July", "August", "September", "October", "November", "December"];

            var assignTo = 'individual';

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
                    for (let i = 0; i < filenames.length; i++) {
                        formData.push({name: "attachments[]", value: filenames[i]});
                    }
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
                            // console.log(data);
                            $('.loading').hide();
                            if (data.success == true) {
                                window.location = '/project_management_tool/myRequestTasks';
                            }
                            console.log(data);
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
           $(".fa-calendar-o").on("click", function(){
                $(this).siblings("input").datepicker({
                    forceParse:false,
                    autoclose: true,
                    immediateUpdates: true,
                    todayBtn: true,
                    todayHighlight: true
                });

                // $(this).siblings("input").datepicker('update', new Date());
               $(this).siblings("input").datepicker('show');
           });

           $('.date-picker').on("change",function(){
                const d = $(this).val().split('/');
                const date = d[1] + " " +monthNames[Number(d[0]-1)] + ', '+ d[2]+ ' ';
                $(this).val(date);
           });
            
            $(document).ready(function() {
                $(window).keydown(function(event){
                    if(event.keyCode == 13) {
                        event.preventDefault();
                        return false;
                    }
                });
            });
        </script>

</body>

</html>
<?php /**PATH C:\xampp\htdocs\project_management_tool\resources\views/backend/my_request_tasks/create.blade.php ENDPATH**/ ?>