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
<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    @include('backend.layouts.header')
    <div class="container-fluid page-body-wrapper">
        @include('backend.layouts.sidebar')
        <div class="main-panel role-create-main-panel">
            <div class="content-wrapper">
                <div id="alert-show">
                    @include('backend.layouts.messages')
                </div>
                <h3 class="role-create-title">Tag</h3>
                <hr class="Dash-Line">
                <div class="CreateRolekBox card-body">
                    <h5 class="mb-4 mt-2">Add New Tag</h5>
                    <hr>
                    <div class="add-role-form">
                        <form action="{{route('tags.store')}}" method="POST">
                            @csrf
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label for="name">Tag Name <span class="mandatory">*</span></label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" data-role="tagsinput" name="tags[]"/>
                                </div>
                            </div>
                        
                            <div class="form-group row">
                                <div class="col-md-12 text-center">
                                    <a href="{{route('tags.index')}}" class="btn custom-outline-btn">Cancel</a>
                                    <button class="btn custom-btn">Create</button>
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
