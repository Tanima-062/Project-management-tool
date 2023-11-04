<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Task Management Platform</title>
    @include('backend.layouts.styles')
    <link rel="stylesheet" href="{{asset('backend/assets/templates/vendors/font-awesome/css/font-awesome.min.css')}}">
</head>
<body>
<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    @include('backend.layouts.header')
    <div class="container-fluid page-body-wrapper">
        @include('backend.layouts.sidebar')
        <div class="main-panel role-edit-main-panel">
            <div class="content-wrapper">
                <div id="alert-show">
                    @include('backend.layouts.messages')
                </div>
                <h3 class="role-edit-title">Department</h3>
                <hr class="Dash-Line">
                <div class="CreateRolekBox card-body">
                    <h5 class="mb-4 mt-2">Edit Department - {{$program->name}}</h5>
                    <hr>
                    <div class="add-role-form">
                        <form action="{{route('departments.update',$program->id)}}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label for="name">Department Name <span class="mandatory">*</span></label>
                                </div>
                                <div class="col-md-10" >
                                    <input type="text" class="form-control" id="name" name="name" value="{{$program->name}}">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <div class="col-md-12 text-center">
                                    <a href="{{route('departments.index')}}" class="btn custom-outline-btn">Cancel</a>
                                    <button class="btn custom-btn" type="submit">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
            {{--            footer--}}
            <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
    </div>
</div>
        <!-- page-body-wrapper ends -->
        <!-- container-scroller -->
        @include('backend.layouts.scripts')

</body>

</html>
