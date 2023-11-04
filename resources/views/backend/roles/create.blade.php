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
        <div class="main-panel role-create-main-panel">
            <div class="content-wrapper">
                <div id="alert-show">
                    @include('backend.layouts.messages')
                </div>
                <h3 class="role-create-title">Role Management</h3>
                <hr class="Dash-Line">
                <div class="CreateRolekBox card-body">
                    <h5 class="mb-4 mt-2">Add New Role</h5>
                    <hr>
                    <div class="add-role-form">
                        <form action="{{route('roles.store')}}" method="POST">
                            @csrf
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label for="name">Role Name <span class="mandatory">*</span></label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter a Role Name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label for="status">Status <span class="mandatory">*</span></label>
                                </div>
                                <div class="col-sm-10">
                                    <div class="radio-item">
                                        <input type="radio" id="status" name="status" value="1" checked>
                                        <label for="status">Active</label>
                                    </div>
    
                                    <div class="radio-item">
                                        <input type="radio" id="inactive" name="status" value="0">
                                        <label for="inactive">Inactive</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2 pt-3">
                                    <label>Permissions</label>
                                </div>
                                <div class="col-md-10 form-check">
                                    <input type="checkbox" class="checkbox" id="checkPermissionAll" value="1">
                                    <label class="form-check-label" for="checkPermissionAll">All</label>
                                </div>
                            </div>   
                            <hr> 
                            <div class="form-group row">
                                <div class="col-md-2"></div>
                                <div class="col-md-10 form-check">                               
                                    @php $i=1; @endphp
                                    @foreach($permission_groups as $group)
                                        <div class="row">
                                            <div class="col-3 group-check">
                                                <div class="form-check">
                                                    <input type="checkbox" class="checkbox" id="{{$i}}Management" value="{{$group->name}}" onclick="checkPermissionByGroup('role-{{$i}}-management-checkbox',this)">
                                                    <label class="form-check-label" for="{{$i}}Management">{{$group->name}}</label>
                                                </div>
                                            </div>
                                            <div class="col-9 role-group role-{{$i}}-management-checkbox">
                                                @php
                                                    $permissions = App\Modals\User::getPermissionsByGroupName($group->name);
                                                    $j=1;
                                                @endphp
                                                @foreach($permissions as $permission)
                                                    <div class="form-check">
                                                        <input type="checkbox" class="checkbox" name="permissions[]" id="checkPermission{{$permission->id}}" value="{{$permission->name}}" onclick="selectDeselectGroup('{{$i}}Management','role-{{$i}}-management-checkbox')">
                                                        <label class="form-check-label" for="checkPermission{{$permission->id}}">{{$permission->displayName}}</label>
                                                    </div>
                                                    @php $j++; @endphp
                                                @endforeach
                                            </div>
                                        </div><br>
                                        @php $i++; @endphp
                                    @endforeach
                                </div>                            
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12 text-center">
                                    <a href="{{route('roles.index')}}" class="btn custom-outline-btn">Cancel</a>
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
        <script>
            $("#checkPermissionAll").click(function () {
                if($(this).is(':checked')){
                    $("input[type=checkbox]").prop('checked',true);
                }else{
                    $("input[type=checkbox]").prop('checked',false);
                }
            });

            function checkPermissionByGroup(className, checkThis) {
                const groupIdName = $("#"+checkThis.id);
                const classCheckBox = $("."+className+" input");
                if(groupIdName.is(':checked')){
                    classCheckBox.prop('checked',true);
                }else{
                    classCheckBox.prop('checked',false);
                }
                checkAllPermissions();
            }
            function selectDeselectGroup(groupIdName,className){
                const classCheckBox = $("."+className+" input");
                let flag = true;
                classCheckBox.each(function () {
                    if($(this).is(':not(:checked)')){
                        $("#"+groupIdName).prop('checked', false);
                        $("#"+groupIdName).removeAttr('checked');
                        flag = false;
                        return false;
                    }
                });
                if(flag){
                    $("#"+groupIdName).prop('checked', true);
                }
                checkAllPermissions();
            }
            function checkAllPermissions() {
                let flagAll = true;
                $('.group-check input').each(function () {
                    if($(this).is(':not(:checked)')){
                        $("#checkPermissionAll").prop('checked', false);
                        flagAll = false;
                        return false;
                    }
                });
                if(flagAll){
                    $("#checkPermissionAll").prop('checked', true);
                }
            }
        </script>


</body>

</html>
