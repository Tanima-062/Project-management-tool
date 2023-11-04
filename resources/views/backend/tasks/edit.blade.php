<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Task Management Platform</title>
    @include('backend.layouts.styles')
</head>
<body>
<div class="container-scroller task-edit-page">
    <div class="loading">
        <div class="loader"></div>
    </div>
    <!-- partial:partials/_navbar.html -->
    @include('backend.layouts.header')
    <div class="container-fluid page-body-wrapper">
        @include('backend.layouts.sidebar')
        <div class="main-panel task-create-main-panel">
            <div class="content-wrapper">
                <div id="alert-show">
                    @include('backend.layouts.messages')
                </div>
                <div class="CreateTaskBox card-body">
                <div class="Task-edit-view-title py-2">Edit Task</div>
                    <form method="POST" action="{{route('tasks.update',$task->id)}}" enctype="multipart/form-data" id="taskForm">
                        @method('PUT')
                        @csrf
                        <input type="hidden" id="taskId" value="{{$task->id}}">
                        <div class="form-group task-title-group">
                            <label>Task Title <span class="mandatory">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="{{$task->title}}">
                            <p class="error title-error" ></p>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label>Allocated Time</label>
                                <input type="text" class="form-control custom-input" name="allocated_time" value="{{$task->allocated_time}}" onkeyup="if (/[^0-9\.]/g.test(this.value)) this.value = this.value.replace(/[^0-9\.]/g,'')">
                            </div>
                            <div class="col-md-6">
                                <label>Tags</label>
                                <select class="form-control col-md-12 custom-input select2" name="tags[]" multiple>
                                    @foreach($tags as $tag)
                                        @if(collect(old('tags'))->contains($tag->id)))
                                            @php $hasTag = 'selected'; @endphp
                                        @else
                                            @php $hasTag = \App\Modals\Task::hasTag($tag->id, $task->taskTags); @endphp
                                        @endif
                                        <option value="{{$tag->id}}" {{$hasTag}}>{{$tag->name}}</option>
                                    @endforeach
                                </select>    
                            </div>
                        </div>
                        <div class="form-group task-description-group">
                            <label>Description</label>
                            <textarea class="form-control ckeditor" id="description" name="description" rows="3">{{$task->description}}</textarea>
                            <p class="error description-error" ></p>
                        </div>

                        <div class="form-group row start-end-priority">
                            <div class="col-sm-4">
                                <label>Start Date<span class="mandatory">*</span></label>
                                <div class='right-inner-addon date datepicker'>
                                    <i class="fa fa-calendar-o date-picker"></i>
                                    <input name='start_date' value="{{isset($task->start_date) ? date('d M, Y', strtotime($task->start_date)) : ''}}" data-date="" data-date-format="d M, yyyy" type="text" class="form-control date-picker" id="start_date" autocomplete="off" readonly/>
                                </div>
                                <p class="error start_date-error" ></p>
                            </div>
                            <div class="col-sm-4">
                                <label>End Date</label>
                                <div class='right-inner-addon date datepicker'>
                                    <i class="fa fa-calendar-o date-picker"></i>
                                    <input name='end_date' value="{{isset($task->end_date) ? date('d M, Y', strtotime($task->end_date)) : ''}}" data-date="" data-date-format="d M, yyyy" type="text" class="form-control date-picker" id="end_date" autocomplete="off" readonly/>
                                </div>
                                <p class="error end_date-error" ></p>
                            </div>
                            <div class="col-sm-4">
                                <label>Priority <span class="mandatory">*</span></label>
                                <select class="col-sm-12 task-priority" name="priority" id="priority">
                                    <option value="2" {{ $task->priority == 2 ? 'Selected' : ''}}>High</option>
                                    <option value="1" {{ $task->priority == 1 ? 'Selected' : ''}}>Medium</option>
                                    <option value="0" {{ $task->priority == 0 ? 'Selected' : ''}}>Low</option>
                                </select>
                            </div>
                        </div>

                        {{-- <div class="form-group row">
                            <div class="col-md-12">
                                <label>Assign To <span class="mandatory">*</span></label>
                            </div>
                            <div class="col-md-12">
                                <input type="hidden" id="task-assign-to" value="{{$task->assign_to}}">
                                <div class="radio-item">
                                    <input type="radio" id="team" name="assign_to" value="team" {{($task->assign_to == 'team') ? 'checked' : ''}}>
                                    <label for="team">Team</label>
                                </div>
                                <div class="radio-item">
                                    <input type="radio" id="individual" name="assign_to" value="individual" {{($task->assign_to == 'individual') ? 'checked' : ''}}>
                                    <label for="individual">Individual</label>
                                </div>
                            </div>
                        </div> --}}
                        
                        {{-- <div>
                            <div class="Task-view-title" style="font-size:14px;">Assign To <span class="mandatory">*</span><br>
                            <input type="hidden" id="task-assign-to" value="{{$task->assign_to}}">
                            <div class="radio-item">
                                <input type="radio" id="team" name="assign_to" value="team" {{($task->assign_to == 'team') ? 'checked' : ''}}>
                                <label for="team">Team</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="individual" name="assign_to" value="individual" {{($task->assign_to == 'individual') ? 'checked' : ''}}>
                                <label for="individual">Individual</label>
                            </div>
                            </div>
                        </div>     --}}

                        {{-- <div class="form-group row assign-to-block">
                            @if($task->assign_to == 'team')
                                <div class="col-md-6 if-block-col-1 pr-4">
                                    <label>Assign To Team(s)</label>
                                    <div class=" teamAssignToBox Task-Assign-To">
                                        <div id="input_container">
                                            <input type="hidden" class="page_reassign_team" value="reassign_team">
                                            <input type="text" id="input" placeholder="Search for team..." class="disable-team team-search">
                                            <hr>
                                            <i class="fa fa-search" id="input_img"></i>
                                            <div class="team-section">
                                                @include('backend.tasks.fetch_team_reassign')
                                            </div>
                                        </div>
                                    </div>
                                    <p class="error assignee-error team-error" ></p>
                                </div>
                                <div class="col-md-6 if-block-col-2 pl-4">
                                    <label>Assign To Individual(s)</label>
                                    <div class="individualAssignToBox Task-Assign-To">
                                        <div id="input_container">
                                            <input type="hidden" class="page_reassign_individual" value="reassign_individual">
                                            <input type="text" id="input" placeholder="Search for individual..." class="disable-individual individual-search">
                                            <hr>
                                            <i class="fa fa-search" id="input_img"></i>
                                            <div class="individual-section">
                                                @include('backend.tasks.fetch_individual_reassign')
                                            </div>
                                        </div>
                                    </div>
                                    <p class="error assignee-error individual-error" ></p>
                                </div>
                            @else
                                <div class="col-md-6 else-block-col-1 pr-4">
                                    <label>Assign To Team(s)</label>
                                    <div class=" teamAssignToBox Task-Assign-To">
                                        <div id="input_container">
                                            <input type="hidden" class="page_reassign_team" value="reassign_team">
                                            <input type="text" id="input" placeholder="Search for team..." class="disable-team team-search">
                                            <hr>
                                            <i class="fa fa-search" id="input_img"></i>
                                            <div class="team-section">
                                                @include('backend.tasks.fetch_team_reassign')
                                            </div>
                                        </div>  
                                    </div>
                                    <p class="error assignee-error team-error" ></p>
                                </div>
                                <div class="col-md-6 else-block-col-2 pl-4">
                                    <label>Assign To Individual(s)</label>
                                    <div class="individualAssignToBox Task-Assign-To">
                                        <div id="input_container">
                                            <input type="hidden" class="page_reassign_individual" value="reassign_individual">
                                            <input type="text" id="input" placeholder="Search for individual..." class="disable-individual individual-search">
                                            <hr>
                                            <i class="fa fa-search" id="input_img"></i>
                                            <div class="individual-section">
                                                @include('backend.tasks.fetch_individual_reassign')
                                            </div>
                                        </div>
                                    </div>
                                    <p class="error assignee-error individual-error" ></p>
                                </div>
                            @endif
                        </div> --}}

                        <div class="form-group row">
                            <div class="col-md-12">
                                 {{-- <label>Assign To <span class="mandatory">*</span></label> --}}
                            </div>
                            <div class="col-md-12">
                                <div class="radio-item" style="display:none;">
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
                                        <div class="mb-3" id="show-member-div"></div>
                                        <input type="text" id="input" placeholder="Search for individual..." class="disable-individual individual-search">
                                        <hr>
                                        <i class="fa fa-search" id="input_img"></i>
                                        <div class="individual-section">
                                            @include('backend.tasks.fetch_individual_reassign')
                                        </div>
                                    </div>
                                </div>
                                <p class="error assignee-error individual-error"></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Attachments</label>
                            @if(count($task->attachments) > 0)
                            <div>
                            @foreach($task->attachments as $attachment)
                                <span class="existing-files" id="{{$attachment->file_name}}" style="margin-right:20px;">
                                    {{$attachment->file_name}}
                                    <i class="fa fa-times"></i>
                                </span>
                            @endforeach
                            </div>
                            @endif
                            <div class="filepond-error"></div>
                            <input type="file" name="attachments[]" multiple>
                        </div>
                       
                        <div class="form-group">
                            <label>Comments</label>
                            <textarea class="form-control task-comment-area ckeditor" id="comment" name="comment" rows="3" placeholder="Your comments here"></textarea>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{route('tasks.show',$task->id)}}" class="btn custom-outline-btn">Cancel</a>
                            <button class="btn custom-btn save-btn">Save</button>
                        </div>
                    </form>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
            {{--footer--}}
            <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
        <!-- container-scroller -->
        @include('backend.layouts.scripts')
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
            var individuals = {};
            var teamIds = [];

            pond.setOptions({
                server:{
                    process: {
                        url: '/project_management_tool/uploadFiles',
                        headers: {
                            'X-CSRF-TOKEN': '{!! csrf_token() !!}'
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

            const monthNames = ["January", "February", "March", "April", "May", "June",
                                    "July", "August", "September", "October", "November", "December"];

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
                $(".start_date-error").html("");
                $(".end_date-error").html("");
            });

            $('.assignee').click(function (){
                $('.assignee-error').html('');
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
                if($('#start_date').val() == ''){
                    $(".start_date-error").html("Start Date is required");
                    flag = 'start_date';
                }
                else if(($('#start_date').val() != '') && ($('#end_date').val() != '')){
                    var startDateTimeInMillis = Date.parse($('#start_date').val());
                    var endDateTimeInMillis = Date.parse($('#end_date').val());
                    if(startDateTimeInMillis > endDateTimeInMillis){
                        $(".start_date-error").html("Start Date should smaller than end date");
                        flag = 'start_date_not_equal';
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
                    formData.push({name: "description", value: description});
                    formData.push({name: "comment", value: comment});
                    for (let i = 0; i < existing_files.length; i++) {
                        formData.push({name: "existing_attachments[]", value: existing_files[i]});
                    }
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
                        data: formData,
                        success: function (data) {
                            console.log(data);
                            $('.loading').hide();
                            if (data.success == true) {
                                window.location = '/project_management_tool/tasks/'+$("#taskId").val();
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
            
            $(document).ready(function(){
                allSelect();
                memberSelect();

                $('.active').trigger('click');

                $('.existing-files').each(function(){
                    existing_files.push($(this).attr('id'));
                });

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

            $('.fa-times').click(function(){
                var filename = $(this).closest('.existing-files').attr('id');
                var index = existing_files.indexOf(filename);
                existing_files.splice(index, 1);
                $(this).closest('.existing-files').remove();
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
                            $('.all-div').hide();
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

            function allSelect(){
                $('.checkbox-all').click(function(){
                    var id = $(this).attr('id');
                    if($(this).is(':not(:checked)')){
                        $('.'+id+' .checkbox-member').each(function(){
                            var removeId = $(this).attr('id');
                            $(this).prev().prop('checked', false);
                            $(this).prop('checked', false);
                            var index = $(this).prev().val();
                            var value = $(this).val();
                            if(individuals[index].indexOf(value) > -1){
                                var removeIndex = individuals[index].indexOf(value);
                                individuals[index].splice(removeIndex,1);
                            }
                            $('[removeId='+removeId+']').closest('div').remove();
                        });
                    }else{
                        $('.'+id+' .checkbox-member').each(function(){ 
                            $(this).prev().prop('checked', true);
                            $(this).prop('checked', true);
                            var index = $(this).prev().val();
                            var value = $(this).val();
                            if(individuals[index].indexOf(value) == -1){
                                individuals[index].push(value);
                                var memberName = $(this).closest('label').text();
                                var id = $(this).attr('id');
                                $("#show-member-div").append("<div class='memberNameBox' >"+memberName+"<i class='fa fa-times remove-member' removeId = "+id+"></i></div>");   
                            }
                        });
                        removeMember();
                    }
                });
            }
            function memberSelect(){
                $('.checkbox-member').click(function (){
                    var className = $(this).closest('div').attr('class');
                    if($(this).is(':not(:checked)')){
                        $(this).prev().prop('checked', false);
                        var index = $(this).prev().val();
                        var value = $(this).val();
                        if(individuals[index].indexOf(value) > -1){
                            var removeIndex = individuals[index].indexOf(value);
                            individuals[index].splice(removeIndex,1);
                        }
                        var removeId = $(this).attr('id');
                        $('[removeId='+removeId+']').closest('div').remove();
                    }else{
                        $(this).prev().prop('checked', true);
                        var index = $(this).prev().val();
                        var value = $(this).val();
                        var memberName = $(this).closest('label').text();
                        var id = $(this).attr('id');
                        $("#show-member-div").append("<div class='memberNameBox'>"+memberName+"<i class='fa fa-times remove-member' removeId ="+id+"></i></div>");
                        if(individuals[index].indexOf(value) == -1){
                            individuals[index].push(value);
                        }
                    }
                    selectDeselectAll(className);
                    removeMember();
                });
            }

            function selectDeselectAll(className){
                var classCheckBox = $("."+className+" .checkbox-member");
                var flag = true;
                classCheckBox.each(function () {
                    if($(this).is(':not(:checked)')){
                        $("#"+className).prop('checked', false);
                        $("#"+className).removeAttr('checked');
                        flag = false;
                        return false;
                    }
                });
                if(flag){
                    $("#"+className).prop('checked', true);
                }
            }

            function removeMember(){
                $('.remove-member').click(function(){
                    var id= $(this).attr('removeId');
                    $("#"+id).prev().prop('checked', false);
                    $("#"+id).prop('checked', false);
                    $(this).closest('div').remove();
                });
            }
            
        </script>

</body>

</html>
