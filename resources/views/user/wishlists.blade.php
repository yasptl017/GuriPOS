@extends('layout')
@section('title')
    <title>{{__('user.Wishlist')}}</title>
@endsection
@section('meta')
    <meta name="description" content="{{__('user.Wishlist')}}">
@endsection

@section('public-content')


    <!--=============================
        BREADCRUMB START
    ==============================-->
    <section class="tf__breadcrumb" style="background: url({{ asset($breadcrumb) }});">
        <div class="tf__breadcrumb_overlay">
            <div class="container">
                <div class="tf__breadcrumb_text">
                    <h1>{{__('user.Wishlist')}}</h1>
                    <ul>
                        <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                        <li><a href="javascript:;">{{__('user.Wishlist')}}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        BREADCRUMB END
    ==============================-->


        <!--=========================
        DASHBOARD START
    ==========================-->
    <section class="tf__dashboard mt_120 xs_mt_90 mb_100 xs_mb_70">
        <div class="container">
            <div class="tf__dashboard_area">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 wow fadeInUp" data-wow-duration="1s">
                        <div class="tf__dashboard_menu">
                            <div class="dasboard_header">
                                <div class="dasboard_header_img">

                                    @if ($personal_info->image)

                                    <img id="preview-user-avatar" src="{{ asset($personal_info->image) }}" alt="user" class="img-fluid w-100">
                                    @else
                                    <img id="preview-user-avatar" src="{{ asset($default_user_avatar) }}" alt="user" class="img-fluid w-100">
                                    @endif

                                    <label for="upload"><i class="far fa-camera"></i></label>
                                    <form id="upload_user_avatar_form" enctype="multipart/form-data" method="POST">
                                        @csrf
                                        <input type="file" name="image" id="upload" hidden onchange="previewThumnailImage(event)">
                                    </form>
                                </div>
                                <h2>{{ html_decode($personal_info->name) }}</h2>
                            </div>

                            @include('user.sidebar')

                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-8 wow fadeInUp" data-wow-duration="1s">
                        <div class="tf__dashboard_content">
                            <div class="tf_dashboard_body dashboard_review">
                                <h3>{{__('user.Wishlist')}}</h3>
                                <div class="tf__dashoard_wishlist">
                                    <div class="row">

                                        @foreach ($products as $product)
                                            <div class="col-xxl-4 col-md-6 wow fadeInUp" data-wow-duration="1s">
                                                <div class="tf__menu_item">
                                                   <!-- <div class="tf__menu_item_img">
                                                        <img src="{{ asset($product->thumb_image) }}" alt="menu" class="img-fluid w-100">
                                                    </div>-->
                                                    <div class="tf__menu_item_text">
                                                        <a class="category" href="{{ route('products',['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
                                                        <a class="title" href="{{ route('show-product', $product->slug) }}">{{ $product->name }}</a>
                                                        <label style="font-weight: 600;">{{$product->short_description}}</label>
                                                        @if ($product->is_offer)
                                                                <h5 class="price">{{ $currency_icon }}{{ $product->offer_price }} <del>{{ $currency_icon }}{{ $product->price  }}</del> </h5>
                                                            @else
                                                                <h5 class="price">{{ $currency_icon }}{{ $product->price }}</h5>
                                                            @endif

                                                        <a class="tf__add_to_cart" href="javascript:;" onclick="load_product_model({{ $product->id }})">{{__('user.add to cart')}}</a>
                                                        <ul class="d-flex flex-wrap justify-content-end">

                                                            <li><a href="javascript:;" onclick="remove_wishlist({{ $product->id }})"><i class="fal fa-heart"></i></a></li>

                                                            <form id="remove_wishlist_{{ $product->id }}" action="{{ route('remove-to-wishlist', $product->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>

                                                    
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>

        function remove_wishlist(id){

            Swal.fire({
                title: "{{__('user.Are you realy want to remove wishlist item ?')}}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{__('user.Yes, Remove It')}}",
                cancelButtonText: "{{__('user.Cancel')}}",
            }).then((result) => {
                if (result.isConfirmed) {

                    var isDemo = "{{ env('APP_MODE') }}"
                    if(isDemo == 0){
                        toastr.error('This Is Demo Version. You Can Not Change Anything');
                        return;
                    }

                    $("#remove_wishlist_"+id).submit();
                }

            })
        }



    </script>
@endsection
