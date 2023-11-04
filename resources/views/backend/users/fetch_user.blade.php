<table class="table table-responsive" id="userList">
    <thead>
        <tr>
           <th>SL No.<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="id"></i></th>
            <th style="width:25%">Full Name<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="name"></i></th>
            <th>Designation<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="designation"></i></th>
            <th style="width:25%;">Department</th>
            <th>Email Address<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="email"></i></th>
            <th>Phone No<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="phone_number"></i></th>
            <th>Role<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="role"></i></th>
            <th>Status<i class='fas fa-exchange-alt custom-sorting' order="asc" coloumn="status"></i></th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        @isset($user)
            <tr>
                <td>{{empty($sort_id) ? ++$serial : $serial--}}</td>
                <td>{{$user->name}}</td>
                <td>{{$user->designation}}</td>
                <td>
                   @foreach($user->userPrograms as $user_program)
                      <p>{{$user_program->program->name}}</p>
                   @endforeach
                </td>
                <td>{{$user->email}}</td>
                <td>{{$user->phone_number}}</td>
                <td>{{$user->role}}</td>
                <td>{{($user->status=='1') ? 'Active' : 'Inactive'}}</td>
                <td>
                    @if(\App\Modals\User::hasSpecificPermission(\Illuminate\Support\Facades\Auth::user(),'user.edit'))
                        <a class="Rectangle-Edit btn-hover2" style="text-decoration: none;" href="{{route('users.edit', $user->id)}}"><i class="fa fa-pencil" style="margin-right: 5px;"></i>Edit</a>
                    @endif
                    @if(\App\Modals\User::hasSpecificPermission(\Illuminate\Support\Facades\Auth::user(),'user.delete'))
                        <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="{{ route('users.destroy', $user->id) }}"
                           onclick="deleteData('delete-form-{{$user->id}}');"><i class="fa fa-trash"></i>
                            Delete
                        </a>

                        <form id="delete-form-{{$user->id}}" action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-none" style="display: none">
                            @method('DELETE')
                            @csrf
                        </form>
                    @endif
                </td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>
<div class="user-list-pagination" id="pageUser" style="margin-top:20px;">
        @if(count($users) > 0)
            @if((($users->currentPage()-1)*$users->perPage())+$users->perPage() < $users->total())
                Showing from {{(($users->currentPage()-1)*$users->perPage())+1}} to {{(($users->currentPage()-1)*$users->perPage())+$users->perPage()}} of {{$users->total()}} 
            @else
                Showing from {{(($users->currentPage()-1)*$users->perPage())+1}} to {{$users->total()}} of {{$users->total()}} 
            @endif
        @else
            Showing from 0 to 0 of 0
        @endif        
            {{ $users->links() }}
</div>
<script>
    function deleteData(id){
        event.preventDefault();
        swal({
                title: "Are you sure?",
                type: "error",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "CONFIRM",
                cancelButtonText: "CANCEL",
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function() {
                $.ajax({
                    url: $("#" + id).attr('action'),
                    method: 'POST',
                    data: $("#" + id).serializeArray(),
                    success: function (data) {
                        location.reload();
                    }
                });
            }
        );
    }
</script>
