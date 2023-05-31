@extends('admin.layouts.master')

@section('title','Category List Page')
@section('content')
 <!-- MAIN CONTENT-->
 <div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="login-form col-6 offset-3">

                    @if (Session::has('success'))
                        <div class="alert alert-success">
                            {{Session::get('success')}}
                        </div>
                    @endif
                    <form action="{{route('contact-us.storeContact')}}" method="POST">
                        <div class="text-center">
                            <h3 class="text-muted">Contact Us</h3>
                        </div>
                        @csrf
                        <div class="form-group">
                            <label>Name</label>
                            <input class="au-input au-input--full" type="text" name="name" value="{{old('name')}}" placeholder="Username">

                            @if ($errors->has('name'))
                            <span class="text-danger">{{$errors->first('name')}}</span>
                        @endif
                        </div>

                        <div class="form-group">
                            <label>Email Address</label>
                            <input class="au-input au-input--full" type="email" name="email" value="{{old('email')}}" placeholder="Email">

                            @if ($errors->has('email'))
                            <span class="text-danger">{{$errors->first('email')}}</span>
                            @endif
                        </div>

                        <div class="form-control">

                            <textarea name="message" id="" cols="60" rows="10" value="{{old('message')}}" placeholder="Your Message"></textarea>

                        </div>
                         @if ($errors->has('message'))
                            <span class="text-danger">{{$errors->first('message')}}</span>
                        @endif

                        <button class="au-btn au-btn--block bg-secondary m-b-20 col-4 offset-8 mt-3" type="submit">Send</button>

                    </form>
                </div>
            </div>

                <!-- END DATA TABLE -->
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->

@endsection


