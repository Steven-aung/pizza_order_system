@extends('admin.layouts.master')

@section('title','Category List Page')
@section('content')
 <!-- MAIN CONTENT-->
 <div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="table-responsive table-responsive-data2">
                    <h3>Total - {{$users->total()}}</h3>
                    <table class="table table-data2 text-center">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Role</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody id="dataList">
                            @foreach ($users as $u )
                            <tr>
                               <td class="col-1">
                                     @if ($u->image == null)
                                        @if ($u->gender == 'male')
                                        <img src="{{asset('image/default_user1.png')}}"  />
                                        @else
                                        <img src="{{asset('image/fe-profile.jpg')}}"  />
                                     @endif
                                     @else
                                        <img src="{{asset('storage/'.$u->image)}}"  />
                                     @endif
                               </td>
                               <input type="hidden" id="userId" value="{{$u->id}}">
                               <td>{{$u->name}}</td>
                               <td>{{$u->email}}</td>
                               <td>{{$u->gender}}</td>
                               <td>{{$u->phone}}</td>
                               <td>{{$u->address}}</td>
                               <td>
                                    <select class="form-control statusChange" id="">
                                        <option value="user" @if($u->role == 'user') selected @endif>User</option>
                                        <option value="admin" @if($u->role == 'admin') selected @endif>Admin</option>
                                    </select>
                               </td>

                                <td class="col-1">
                                    <div class="table-data-feature">
                                        @if (Auth::user()->id ==  $u->id)

                                        @else
                                    <a href="{{route('admin#changeUser',$u->id)}}">
                                        <button class="item me-1" data-toggle="tooltip" data-placement="top" title="Change Admin Role">
                                            <i class="fa-solid fa-person-circle-minus"></i>
                                        </button>
                                    </a>
                                    <a href="{{route('user#delete',$u->id)}}">
                                        <button class="item me-1" data-toggle="tooltip" data-placement="top" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </a>
                                    @endif
                                    </div>
                                </td>

                               @endforeach
                            </tr>
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{$users->links()}}
                    </div>
                </div>
            </div>

                <!-- END DATA TABLE -->
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->

@endsection

@section('scriptSource')

<script>
       $(document).ready(function(){
            //change status
            $('.statusChange').change(function(){
                $currentStatus = $(this).val();
                $parentNode = $(this).parents("tr");
                $userId = $parentNode.find('#userId').val();
                $data = {
                    'userId' : $userId,
                    'role' : $currentStatus
                };



                $.ajax({
                type: 'get',
                url: '/user/change/role',
                data: $data,
                dataType: 'json',

                })
                location.reload();
            })
        })
</script>

@endsection


