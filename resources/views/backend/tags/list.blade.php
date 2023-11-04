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
<div class="container-scroller role-list-page">
    <!-- partial:partials/_navbar.html -->
    @include('backend.layouts.header')
    <div class="container-fluid page-body-wrapper">
        @include('backend.layouts.sidebar')
        <div class="main-panel role-list-main-panel">
            <div class="content-wrapper">
                <div id="alert-show">
                    @include('backend.layouts.messages')
                </div>
                <h3 class="role-list-title">Tag</h3>
                <hr class="Dash-Line">
                <div class="row DataTableBox">
                    <div class="row role-list-table-subHeader">
                        <div class="col-sm-5 subHeader-col-1">
                            <div class="form-inline">
                                <span>
                                    <b>All Tag List</b>
                                </span>
                                <input class="form-control role-list-search mr-sm-2 " type="search" id="search" placeholder="Search..." aria-label="Search">
                            </div>
                        </div>
                        <div class="col-sm-7 subHeader-col-2">
                            @if(\App\Modals\User::hasSpecificPermission(\Illuminate\Support\Facades\Auth::user(),'tag.create'))
                            <a class="btn-hover" href="{{route('tags.create')}}">
                                <button class="role-list-New-role-btn">New Tag</button>
                            </a>
                            @endif
                        </div>
                    </div>
                    <div>
                        <hr>
                    </div>
                    <div class="table-responsive" style="margin-bottom: 20px;">
                        <table class="table">
                            <thead>
                            <tr>
                                <th style="width:10%">SL</th>
                                <th  style="width:65%;">Name</th>
                                <th  style="width:25%;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tags as $tag)
                                @if(isset($tag))
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$tag->name}}</td>
                                        <td>
                                            @if(\App\Modals\User::hasSpecificPermission(\Illuminate\Support\Facades\Auth::user(),'tag.edit'))
                                               <a class="Rectangle-Edit btn-hover2" style="text-decoration: none;" href="{{route('tags.edit', $tag->id)}}"><i class="fa fa-pencil" style="margin-right: 5px;"></i>Edit</a>
                                            @endif
                                            @if(\App\Modals\User::hasSpecificPermission(\Illuminate\Support\Facades\Auth::user(),'tag.delete'))
                                                <a class="Rectangle-Delete btn-hover2" style="text-decoration: none;" href="{{ route('roles.destroy', $tag->id) }}"
                                                   onclick="deleteData('delete-form-{{$tag->id}}')"><i class="fa fa-trash"></i>
                                                    Delete
                                                </a>

                                                <form id="delete-form-{{$tag->id}}" action="{{ route('tags.destroy', $tag->id) }}" method="POST" class="d-none" style="display: none">
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
            $(document).ready(function() {
                const tableCustom =  $('.table').DataTable({
                    "pageLength": 10,
                    "dom": 'lrtip',
                    "lengthChange": false,
                    columnDefs: [
                        { orderable: false, targets: [-1] }
                    ]
                });
                $('#search').keyup(function(){
                    tableCustom.search($(this).val()).draw() ;
                })

            });
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
                            success: function () {
                                location.reload();
                            }
                        });
                    }
                );
            }
        </script>

</body>

</html>
