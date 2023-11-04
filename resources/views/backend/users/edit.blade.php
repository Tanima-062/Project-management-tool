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
        <div class="main-panel user-edit-main-panel">
            <div class="content-wrapper">
                <div id="alert-show">
                    @include('backend.layouts.messages')
                </div>
                <h4 class="user-edit-title">Edit User - {{empty($user->name) ? '' : $user->name}}</h4>
                <hr class="Dash-Line">
                <div class="CreateTaskBox card-body" style="margin-bottom: 20px;">
                    <h5 class="mb-4 mt-2">Edit User Information</h5>
                    <hr>
                    <form method="POST" action="{{route('users.update',$user->id)}}" enctype="multipart/form-data" autocomplete="off">
                        @method('PUT')
                        @csrf
                        <div class="row py-4">
                            {{-- Srart 1st column --}}
                            <div class="col-md-6 px-5">
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label for="name">Full Name <span class="mandatory">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ !empty(old('name')) ? old('name') : $user->name}}">
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label for="email">Email Id<span class="mandatory">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ !empty(old('email')) ? old('email') : $user->email}}" autocomplete="off">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label for="designation">Designation <span class="mandatory">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control @error('designation') is-invalid @enderror" id="designation" name="designation" value="{{ !empty(old('designation')) ? old('designation') : $user->designation}}">
                                        @error('designation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label for="unit">Company <span class="mandatory">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="@error('unit') is-invalid @enderror col-md-12 dropdownField" name="unit">
                                            @foreach($units as $unit)
                                                <option value="{{$unit->name}}" {{ (!empty(old('unit')) && old('unit') == $unit->name) ? 'selected' : ($user->unit == $unit->name ? 'selected' : '') }}>{{$unit->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('unit')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label for="supervisor">Supervisor</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="@error('supervisor') is-invalid @enderror col-md-12 dropdownField" name="supervisor">
                                            <option value="">Select Supervisor</option>
                                            @foreach($supervisors as $supervisor)
                                                <option value="{{$supervisor->id}}" {{(!empty(old('supervisor')) && old('supervisor') == $supervisor->id) ? 'selected' : ((!empty($user->supervisor) && $user->supervisor == $supervisor->id) ? 'selected' : '')}}>{{$supervisor->name . ' (' . $supervisor->email . ')'}}</option>
                                            @endforeach
                                        </select>
                                        @error('supervisor')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label for="password">Password</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" autocomplete="new-password">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label for="password_confirmation">Confirm Password</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation">
                                        @error('password_confirmation')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            {{-- End 1st column --}}
                            {{-- Start 2nd column --}}
                            <div class="col-md-6 px-5">
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label for="phone_number">Phone Number <span class="mandatory">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ !empty(old('phone_number')) ? old('phone_number') : $user->phone_number}}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                        @error('phone_number')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label for="unit">Department <span class="mandatory">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control{{ $errors->has('programs') ? ' is-invalid' : '' }} col-md-12 select2" name="programs[]" id="programs" multiple>
                                            @foreach($programs as $program)
                                            @if(collect(old('programs'))->contains($program->id)))
                                            @php $hasProgram = 'selected'; @endphp
                                            @else
                                            @php $hasProgram = \App\Modals\User::hasProgram($program->id, $user->userPrograms); @endphp
                                            @endif
                                                <option value="{{$program->id}}" {{ $hasProgram }}>{{$program->name}}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('programs'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('programs') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label for="team">Team <span class="mandatory">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="@error('team') is-invalid @enderror col-md-12 dropdownField" name="team">
                                            <option value="">Select Team</option>
                                            @foreach($teams as $team)
                                                <option value="{{$team->id}}" {{(!empty(old('team')) && old('team') == $team->id) ? 'selected' : ((!empty($selected_team->team_id) &&  $selected_team->team_id == $team->id) ? 'selected' : '') }}>{{$team->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('team')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label for="role">Role <span class="mandatory">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="@error('role') is-invalid @enderror col-md-12 dropdownField" name="role">
                                            @foreach($roles as $role)
                                                <option value="{{$role->name}}" {{(!empty(old('role')) && old('role') == $role->name) ? 'selected' : (($user->role == $role->name) ? 'selected' : '')}}>{{$role->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('role')
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label for="inputPassword">User Photo</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input  @error('image') is-invalid @enderror" id="customFileLang" lang="es" name="image">
                                            @error('image')
                                                <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <label class="custom-file-label" for="customFileLang"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label for="unit">Status <span class="mandatory">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="radio-item">
                                            <input type="radio" id="status" name="status" value="1" {{($user->status == '1') ? 'checked' : ''}}>
                                            <label for="status">Active</label>
                                        </div>

                                        <div class="radio-item">
                                            <input type="radio" id="inactive" name="status" value="0" {{($user->status == '0') ? 'checked' : ''}}>
                                            <label for="inactive">Inactive</label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div style="text-align: center">
                                    <a href="{{route('users.index')}}" class="btn custom-outline-btn">Cancel</a>
                                    <button class="btn custom-btn">Update</button>
                                </div>
                            </div>
                        </div>                       
                    </form>
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
    $('input[type="file"]').change(function(e){
        const fileName = e.target.files[0].name;
        $('.custom-file-label').html(fileName);
    });
    $('.select2').select2();
</script>

</body>

</html>
