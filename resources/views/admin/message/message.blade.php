@extends('admin.layouts.master')

@section('title','Category List Page')
@section('content')
 <!-- MAIN CONTENT-->
 <div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="table-responsive table-responsive-data2">
                    <div class=" my-3">
                        <h3 class="text-muted ">Contact Message From User</h3>
                    </div>
                    <table class="table table-data2 text-center">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Message</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="dataList">
                            @foreach ($contact as $c )
                            <tr>
                               <td>{{$c->id}}</td>
                               <td>{{$c->name}}</td>
                               <td>{{$c->email}}</td>
                               <td>{{$c->message}}</td>
                               <td>{{$c->created_at->format('F-j-Y')}}</td>

                               @endforeach
                            </tr>
                        </tbody>
                    </table>
                    {{-- <div class="mt-3">
                        {{$users->links()}}
                    </div> --}}
                </div>
            </div>

                <!-- END DATA TABLE -->
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->

@endsection



