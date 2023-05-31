@extends('admin.layouts.master')

@section('title','Category List Page')
@section('content')
 <!-- MAIN CONTENT-->
 <div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="col-md-12">
                {{-- alert message  --}}
                @if (session('createSuccess'))
                <div class="col-4 offset-8">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                       <i class="fa-solid fa-check"></i>  {{session('createSuccess')}}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>
                </div>
                @endif
                @if (session('deleteSuccess'))
                <div class="col-4 offset-8">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                       <i class="fa-solid fa-circle-xmark"></i>   {{session('deleteSuccess')}}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-3">
                        <h4 class="text-secondary"> Search Key : <span class="text-danger">{{request('key')}}</span> </h4>
                    </div>
                    <div class="col-3 offset-6">
                        <form action="{{route('admin#list')}}" method="GET">
                            @csrf
                           <div class="d-flex">
                            <input type="text" name="key" class="form-control" placeholder="Search..." value="{{request('key')}}">
                            <button class="btn bg-dark text-white " type="submit">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                           </div>
                        </form>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-1 offset-10  bg-white shadow-sm p-2 text-center">
                        <h3> <i class="fa-solid fa-database mr-2"></i> {{$admin->total()}}</h3>
                    </div>
                </div>


                <div class="table-responsive table-responsive-data2">
                    <table class="table table-data2 text-center">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admin as $a )
                            <tr class="tr-shadow">
                                <td class="col-2 ">
                                    @if ($a->image == null)
                                        @if ($a->gender == 'male')
                                        <img src="{{asset('image/default_user1.png') }}" class=" img-thumbnail shadow-sm">
                                        @else
                                        <img src="{{asset('image/fe-profile.jpg') }}" class=" img-thumbnail shadow-sm">
                                        @endif
                                    @else
                                    <img src="{{asset('storage/'.$a->image) }}" class=" img-thumbnail shadow-sm">
                                    @endif
                                </td>
                                <input type="hidden" id="userId" value="{{$a->id}}">
                                <td>{{$a->name}}</td>
                                <td>{{$a->email}}</td>
                                <td>{{$a->gender}}</td>
                                <td>{{$a->phone}}</td>
                                <td>{{$a->address}}</td>
                                <td>
                                <div class="table-data-feature">
                                    @if (Auth::user()->id ==  $a->id)

                                    @else

                                        {{-- <button class="item me-1" data-toggle="tooltip" data-placement="top" title="Change Admin Role">
                                            <i class="fa-solid fa-person-circle-minus"></i>
                                        </button> --}}


                                        <div class="me-2">
                                            <select class="form-control statusChange" id="">
                                                <option value="user" @if($a->role == 'user') selected @endif>User</option>
                                                <option value="admin" @if($a->role == 'admin') selected @endif>Admin</option>
                                            </select>
                                        </div>

                                    <a href="{{route('admin#delete',$a->id)}}">
                                        <button class="item me-1" data-toggle="tooltip" data-placement="top" title="Delete">
                                            <i class="zmdi zmdi-delete"></i>
                                        </button>
                                    </a>
                                    @endif
                                    </div>
                                </td>
                            </tr>

                            @endforeach

                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $admin->links() }}
                    </div>
                </div>

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
                url: 'http://127.0.0.1:8000/user/change/role',
                data: $data,
                dataType: 'json',

                })
                location.reload();
            })
        })
</script>

@endsection
