<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task Management Platform</title>
    @include('backend.layouts.styles')
</head>
<body>
<div class="container-scroller task-create-page">
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
                    <div class="row">
                        <div class="col-md-12 text-center">
                        <h3 class="task-create-title"><b>Propose Task</b></h3>
                        </div>
                        <div class="col-md-12">
                            <form method="POST" action="{{route('myRequestTasks.update',$task->id)}}" enctype="multipart/form-data" id="taskForm">
                                @method('PUT')   
                                @csrf
                                <div class="form-group">
                                    <label>Task Title <span class="mandatory">*</span></label>
                                    <input type="text" class="form-control custom-input" id="title" name="title"autocomplete="off" value="{{$task->title}}">
                                    <p class="error title-error"></p>
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
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control custom-input ckeditor" id="description" name="description" rows="3"autocomplete="off">{{$task->description}}</textarea>
                                    <p class="error description-error"></p>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label>Start Date <span class="mandatory">*</span></label>
                                        <div class='right-inner-addon date datepicker'>
                                            <i class="fa fa-calendar-o date-picker"></i>
                                            <input name='start_date' value="{{date('d M, Y', strtotime($task->start_date))}}"  data-date="" data-date-format="d M, yyyy" type="text" class="form-control date-picker date-picker-input" autocomplete="off" readonly id="start_date"/>
                                        </div>
                                        <p class="error start_date-error"></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label>End Date</label>
                                        <div class='right-inner-addon date datepicker'>
                                            <i class="fa fa-calendar-o date-picker"></i>
                                            <input name='end_date' value="{{date('d M, Y', strtotime($task->end_date))}}"  data-date="" data-date-format="d M, yyyy" type="text" class="form-control date-picker date-picker-input" autocomplete="off" readonly id="end_date"/>
                                        </div>
                                        <p class="error end_date-error"></p>
                                    </div>
                                    <div class="col-md-4 task-create-page-priority">
                                        <label>Priority <span class="mandatory">*</span></label>
                                        <select class="form-control priority-input" name="priority" id="priority">
                                            <option value="2" {{ $task->priority == 2 ? 'Selected' : ''}}>High</option>
                                            <option value="1" {{ $task->priority == 1 ? 'Selected' : ''}}>Medium</option>
                                            <option value="0" {{ $task->priority == 0 ? 'Selected' : ''}}>Low</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label>Start Time <span class="mandatory">*</span></label>
                                        <div class='row right-inner-addon ml-1'>
                                            <input name='start_time' style="border:none; height:40px;" type="time" min="09:00" max="18:00" value="{{$task->start_time}}" autocomplete="off" id="start_time"/>
                                        </div>
                                        <p class="error start_time-error"></p>
                                    </div>
                                    <div class="col-md-6">
                                        <label>End Time <span class="mandatory">*</span></label>
                                        <div class='row right-inner-addon ml-1 mr-1'>
                                            <input name='end_time' style="border:none; height:40px;" type="time" min="09:00" max="18:00" value="{{$task->end_time}}" autocomplete="off" id="end_time"/>
                                        </div>
                                        <p class="error end_time-error"></p>
                                    </div>
                                </div>

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
                                    <a class="btn custom-outline-btn" href="{{route('myRequestTasks.index')}}">Cancel</a>
                                    <button class="btn custom-btn save-btn">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
            {{--            footer--}}
            <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
        <!-- container-scroller -->
        @include('backend.layouts.scripts')
        <script>
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
                                window.location = '/project_management_tool/myRequestTasks';
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
        </script>

</body>

</html>
