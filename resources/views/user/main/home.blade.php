@extends('user.layouts.master')
@section('content')
    <!-- Shop Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <!-- Shop Sidebar Start -->
            <div class="col-lg-3 col-md-4">
                <!-- Price Start -->
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Filter by
                        Categories</span></h5>
                <div class="bg-light p-4 mb-30">
                    <form>
                        <div class="  d-flex align-items-center justify-content-between mb-3 px-3 py-1 text-white bg-dark">

                            <label class="mt-2">Categories</label>
                            <span class="badge border font-weight-normal ">{{ count($category) }}</span>
                        </div>
                        <div class="  d-flex align-items-center justify-content-between mb-3 pt-1">
                            <a class=" text-dark" href="{{route('user#home')}}">
                                <label for="price-1">All</label>
                            </a>
                        </div>
                        @foreach ($category as $c)
                            <div class="  d-flex align-items-center justify-content-between mb-3 pt-1">
                                <a class=" text-dark" href="{{route('user#filter',$c->id)}}">
                                    <label for="price-1">{{ $c->name }}</label>
                                </a>
                            </div>
                        @endforeach

                    </form>
                </div>
                <!-- Price End -->

                <div class="">
                    <button class="btn btn btn-warning w-100">Order</button>
                </div>
            </div>
            <!-- Shop Sidebar End -->


            <!-- Shop Product Start -->
            <div class="col-lg-9 col-md-8">
                <div class="row pb-3">
                    <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <a href="{{route('user#cartList')}}">
                                    <button type="button" class="btn bg-dark text-white position-relative">
                                        <i class="fa-solid fa-cart-shopping"></i>
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{count($cart)}}

                                        </span>
                                      </button>
                                </a>

                                <a href="{{route('user#history')}}" class="ms-3">
                                    <button type="button" class="btn bg-dark text-white position-relative">
                                        <i class="fa-solid fa-clock-rotate-left me-2"></i>History
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{count($history)}}

                                        </span>
                                      </button>
                                </a>

                            </div>
                            <div class="ml-2">
                                <div class="btn-group">
                                    <select name="sorting" id="sortingOption" class="form-control">
                                        <option value="">Choose Option...</option>
                                        <option value="asc">Ascending</option>
                                        <option value="desc">Descending</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="row" id="dataList">
                       @if (count($pizza) != 0)
                       @foreach ($pizza as $p)
                       <div class="col-lg-4 col-md-6 col-sm-6 pb-1">
                           <div class="product-item bg-light mb-4" id="myForm">
                               <div class="product-img position-relative overflow-hidden">
                                   <img class="img-fluid w-100" style="height: 300px"
                                       src="{{ asset('storage/' . $p->image) }}" alt="">
                                   <div class="product-action">
                                       <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-shopping-cart"></i></a>
                                       <a class="btn btn-outline-dark btn-square" href="{{route('user#pizzaDetails',$p->id)}}"><i class="fa-solid fa-circle-info"></i></a>
                                   </div>
                               </div>
                               <div class="text-center py-4">
                                   <a class="h6 text-decoration-none text-truncate" href="">{{ $p->name }}</a>
                                   <div class="d-flex align-items-center justify-content-center mt-2">
                                       <h5>{{ $p->price }} kyats</h5>
                                   </div>
                               </div>
                           </div>
                       </div>
                   @endforeach
                       @else
                            <p class="text-center fs-1 shadow-sm col-6 offset-3 py-5">There is no Pizza <i class="fa-solid fa-pizza-slice ms-2"></i></p>
                       @endif
                    </span>



                </div>
            </div>
            <!-- Shop Product End -->
        </div>
    </div>
    <!-- Shop End -->
@endsection

@section('scriptSource')
    <script>
        $(document).ready(function() {
            $('#sortingOption').change(function() {
                $eventOption = $('#sortingOption').val();

                if ($eventOption == 'asc') {
                    $.ajax({
                        type: 'get',
                        url: '/user/ajax/pizza/list',
                        data: {'status': 'asc'},
                        dataType: 'json',
                        success: function(response) {
                            $list = '';
                            for($i=0;$i<response.length;$i++){
                                $list += `
                                <div class="col-lg-4 col-md-6 col-sm-6 pb-1">
                                        <div class="product-item bg-light mb-4" id="myForm">
                                            <div class="product-img position-relative overflow-hidden">
                                                <img class="img-fluid w-100" style="height: 300px"
                                                    src="{{ asset('storage/${response[$i].image}') }}" alt="">
                                                <div class="product-action">
                                                    <a class="btn btn-outline-dark btn-square" href=""><i
                                                            class="fa fa-shopping-cart"></i></a>
                                                    <a class="btn btn-outline-dark btn-square" href=""><i
                                                            class="fa-solid fa-circle-info"></i></a>
                                                </div>
                                            </div>
                                            <div class="text-center py-4">
                                                <a class="h6 text-decoration-none text-truncate" href="">${response[$i].name}</a>
                                                <div class="d-flex align-items-center justify-content-center mt-2">
                                                    <h5>${response[$i].price} kyats</h5>
                                                </div>
                                            </div>
                                        </div>
                                </div>`;
                            }
                            $('#dataList').html($list);
                        }

                    });
                } else if ($eventOption == 'desc') {
                    $.ajax({
                        type: 'get',
                        url: '/user/ajax/pizza/list',
                        data: {
                            'status': 'desc'
                        },
                        dataType: 'json',
                        success: function(response) {
                            $list = '';
                            for($i=0;$i<response.length;$i++){
                                $list += `
                                <div class="col-lg-4 col-md-6 col-sm-6 pb-1">
                                        <div class="product-item bg-light mb-4" id="myForm">
                                            <div class="product-img position-relative overflow-hidden">
                                                <img class="img-fluid w-100" style="height: 300px"
                                                    src="{{ asset('storage/${response[$i].image}') }}" alt="">
                                                <div class="product-action">
                                                    <a class="btn btn-outline-dark btn-square" href=""><i
                                                            class="fa fa-shopping-cart"></i></a>
                                                    <a class="btn btn-outline-dark btn-square" href=""><i
                                                            class="fa-solid fa-circle-info"></i></a>
                                                </div>
                                            </div>
                                            <div class="text-center py-4">
                                                <a class="h6 text-decoration-none text-truncate" href="">${response[$i].name}</a>
                                                <div class="d-flex align-items-center justify-content-center mt-2">
                                                    <h5>${response[$i].price} kyats</h5>
                                                </div>
                                            </div>
                                        </div>
                                </div>`;
                            }
                            $('#dataList').html($list);
                        }
                    })
                }
            })
        });

    </script>
@endsection
