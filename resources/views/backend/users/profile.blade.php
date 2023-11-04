<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Task Management Platform</title>
    @include('backend.layouts.styles')
    <style>
        #input_container {
            position:relative;
            padding:0 0 0 5px;
            margin-left: 5px;
        }
        #input {
            height:20px;
            margin:0;
            padding-right: 30px;
            border:none;
            width: 100%;
        }
        #input_img {
            position:absolute;
            bottom:2px;
            right:5px;
            width:24px;
            height:24px;
        }
    </style>
</head>
<body>
<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    @include('backend.layouts.header')
    <div class="container-fluid page-body-wrapper">
        @include('backend.layouts.sidebar')
        <div class="main-panel user-profile-panel">
            <div class="content-wrapper">
                <div id="alert-show">
                    @include('backend.layouts.messages')
                </div>
                <div class="profileBox card-body">
                    <div class="row">
                        <div class="col-7">
                         <h5 class="mb-2 mt-2 user-profile-title">User Profile</h5>
                        </div>
                        <div class="col-5 text-end edit-profile-icon">
                          <a href="{{route('users.changePassword',$user->id)}}"><i class="fa fa-edit"></i></a>
                        </div>
                    </div>
                    <hr class="mt-2">
                    <div class="row user-info-row">
                        <div class="col-md-12 text-center mb-5">
                            @if(!empty(\Illuminate\Support\Facades\Auth::user()['image']) && (file_exists(public_path('backend/uploads/profile_images/'.$user->id.'/'.\Illuminate\Support\Facades\Auth::user()['image']))))
                                <img class="profile-picture" src="{!! URL::to('public/backend/uploads/profile_images/'.$user->id.'/'.\Illuminate\Support\Facades\Auth::user()['image']) !!}" alt="profile" height="150px;" width="150px"/>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" height="150" viewBox="0 0 24 24" width="150"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg> 
                            @endif
                        </div>
                        <div class="col-md-6 user-info-col-2 vl">
                            <div class="row profile-info">
                                <div class="col-md-4"><b>Full Name:</b></div>
                                <div class="col-md-8">{{empty($user->name) ? '' : $user->name}}</div>
                            </div>
                            <div class="row profile-info">
                                <div class="col-md-4"><b>Email Address:</b></div>
                                <div class="col-md-8">{{empty($user->email) ? '' : $user->email}}</div>
                            </div>
                            <div class="row profile-info">
                                <div class="col-md-4"><b>Designation:</b></div>
                                <div class="col-md-8">{{empty($user->designation) ? '' : $user->designation}}</div>
                            </div>
                            <div class="row profile-info">
                                <div class="col-md-4"><b>Company:</b></div>
                                <div class="col-md-8">{{empty($user->unit) ? '' : $user->unit}}</div>
                            </div>
                            <div class="row profile-info">
                                <div class="col-md-4"><b>Supervisor:</b></div>
                                <div class="col-md-8">{{empty($supervisor->name) ? '' : $supervisor->name}}</div>
                            </div>
                            <div class="row profile-info">
                                <div class="col-md-4"><b>PIN No.:</b></div>
                                <div class="col-md-8">{{empty($user->pin_number) ? '' : $user->pin_number}}</div>
                            </div>
                        </div>
                        <div class="col-md-6 user-info-col-3">
                            <div>
                                <div class="row profile-info">
                                    <div class="col-md-4"><b>Phone No.:</b></div>
                                    <div class="col-md-8">{{empty($user->phone_number) ? '' : $user->phone_number}}</div>
                                </div>
                                <div class="row profile-info">
                                    <div class="col-md-4"><b>Department:</b></div>
                                    <div class="col-md-8">
                                    @php $program = \App\Modals\User::programNameConcate($user->userPrograms); @endphp
                                        {{ $program }}
                                    </div>
                                </div>
                                <div class="row profile-info">
                                    <div class="col-md-4"><b>Role:</b></div>
                                    <div class="col-md-8">{{empty($user->role) ? '' : $user->role}}</div>
                                </div>
                                <div class="row profile-info">
                                    <div class="col-md-4"><b>Team:</b></div>
                                    <div class="col-md-8">{{empty($member->Team->name) ? '' : $member->Team->name}}</div>
                                </div>
                                <div class="row profile-info">
                                    <div class="col-md-4"><b>Status:</b></div>
                                    <div class="col-md-8">
                                        @if($user->status == '1')
                                            Active
                                        @else
                                            Inactive
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- page-body-wrapper ends -->
        <!-- container-scroller -->
@include('backend.layouts.scripts')

</body>

</html>
