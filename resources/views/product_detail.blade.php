
@extends('layout')
@section('title')
    <title>{{ $product->seo_title }}</title>
@endsection
@section('meta')
    <meta name="title" content="{{ $product->seo_title }}">
    <meta name="description" content="{{ $product->seo_description }}">
@endsection
@section('public-content')

    <!--=============================
        BREADCRUMB START
    ==============================-->
    <section class="tf__breadcrumb" style="background: url({{ asset($breadcrumb) }});">
        <div class="tf__breadcrumb_overlay">
            <div class="container">
                <div class="tf__breadcrumb_text">
                    <h1>{{__('user.Our Products')}}</h1>
                    <ul>
                        <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                        <li><a href="{{ route('products') }}">{{__('user.Our Products')}}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        BREADCRUMB END
    ==============================-->


    <!--=============================
        MENU DETAILS START
    ==============================-->
    <section class="tf__menu_details mt_100 xs_mt_75 mb_95 xs_mb_65">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-sm-10 col-md-9 wow fadeInUp" data-wow-duration="1s">
                    <div class="exzoom hidden" id="exzoom">
                        <div class="exzoom_img_box tf__menu_details_images">
                            <ul class='exzoom_img_ul'>
                                @foreach ($gellery as $single_gallery)
                                    <li><img class="zoom ing-fluid w-100" src="{{ asset($single_gallery->image) }}" alt="product"></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="exzoom_nav"></div>
                        <p class="exzoom_btn">
                            <a href="javascript:void(0);" class="exzoom_prev_btn"> <i class="far fa-chevron-left"></i>
                            </a>
                            <a href="javascript:void(0);" class="exzoom_next_btn"> <i class="far fa-chevron-right"></i>
                            </a>
                        </p>
                    </div>
                </div>
                <div class="col-lg-7 wow fadeInUp" data-wow-duration="1s">
                    <div class="tf__menu_details_text">
                        <h2>{{ $product->name }}</h2>

                        @if ($product->is_offer)
                            <h3 class="price">{{ $currency_icon }}{{ $product->offer_price }} <del>{{ $currency_icon }}{{ $product->price }}</del></h3>
                        @else
                            <h3 class="price">{{ $currency_icon }}{{ $product->price }} </h3>
                        @endif

                        <p class="short_description">{{ $product->short_description }}</p>

                        <form id="add_to_cart_form" action="{{ route('add-to-cart') }}" method="POST">
                            @csrf

                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="price" value="0" id="price">
                                <input type="hidden" name="variant_price" value="0" id="variant_price">

                            <div class="details_size">
                                <h5>{{__('user.select size')}}</h5>
                                @foreach ($size_variants as $index => $size_variant)
                                    <div class="form-check">
                                        <input name="size_variant" class="form-check-input" type="radio" name="flexRadioDefault" id="large-{{ $index }}" value="{{ $size_variant->size }}(::){{ $size_variant->price }}" data-variant-price="{{ $size_variant->price }}" data-variant-size="{{ $size_variant->size }}">
                                        <label class="form-check-label" for="large-{{ $index }}">
                                            {{ $size_variant->size }} <span>- {{ $currency_icon }}{{ $size_variant->price }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            @if (count($optional_items) > 0)
                            <div class="details_extra_item">
                                <h5>{{__('user.select Addon')}} <span>({{__('user.optional')}})</span></h5>
                                @foreach ($optional_items as $index => $optional_item)
                                    <div class="form-check">
                                        <input data-optional-item="{{ $optional_item->price }}" name="optional_items[]" class="form-check-input check_optional_item" type="checkbox" value="{{ $optional_item->item }}(::){{ $optional_item->price }}" id="optional-item-{{ $index }}">
                                        <label class="form-check-label" for="optional-item-{{ $index }}">
                                            {{ $optional_item->item }} <span>+ {{ $currency_icon }}{{ $optional_item->price }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @endif

                            <div class="details_quentity">
                                <h5>{{__('user.select quantity')}}</h5>
                                <div class="quentity_btn_area d-flex flex-wrapa align-items-center">
                                    <div class="quentity_btn">
                                        <button type="button" class="btn btn-danger decrement_qty_detail_page"><i class="fal fa-minus"></i></button>
                                        <input type="text" value="1" name="qty" class="product_qty" readonly>
                                        <button  type="button" class="btn btn-success increment_qty_detail_page"><i class="fal fa-plus"></i></button>
                                    </div>
                                    <h3 >{{ $currency_icon }} <span class="grand_total">0.00</span></h3>
                                </div>
                            </div>
                            <ul class="details_button_area d-flex flex-wrap">
                                <li><a id="add_to_cart" class="common_btn" href="javascript:;">{{__('user.add to cart')}}</a></li>
                                @auth('web')
                                    <li><a class="wishlist" href="javascript:;" onclick="add_to_wishlist({{ $product->id }})"><i class="far fa-heart"></i></a></li>
                                @else
                                    <li><a class="wishlist" href="javascript:;" onclick="before_auth_wishlist()"><i class="far fa-heart"></i></a></li>
                                @endauth
                            </ul>

                        </form>

                    </div>
                </div>
                <div class="col-12 wow fadeInUp" data-wow-duration="1s">
                    <div class="tf__menu_description_area mt_100 xs_mt_70">
                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                    aria-selected="true">{{__('user.Description')}}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-contact" type="button" role="tab"
                                    aria-controls="pills-contact" aria-selected="false">{{__('user.Reviews')}}</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                aria-labelledby="pills-home-tab" tabindex="0">
                                <div class="menu_det_description">

                                    {!! clean($product->long_description) !!}

                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                                aria-labelledby="pills-contact-tab" tabindex="0">
                                <div class="tf__review_area">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <h4>{{ $product->total_review }} {{__('user.reviews')}}</h4>
                                            @if ($product->total_review > 0)
                                            <div class="tf__comment pt-0 mt_20">
                                                @foreach ($product_reviews as $product_review)
                                                    <div class="tf__single_comment m-0 border-0">
                                                        @if ($product_review->user->image)
                                                                <img src="{{ asset($product_review->user->image) }}" alt="review" class="img-fluid">
                                                            @else
                                                                <img src="{{ asset($default_user_avatar) }}" alt="review" class="img-fluid">
                                                            @endif

                                                        <div class="tf__single_comm_text">
                                                            <h3>{{ $product_review->user->name }} <span>{{ $product_review->created_at->format('d M Y') }} </span></h3>
                                                            <span class="rating">
                                                                @for ($i = 1; $i <=5; $i++)
                                                                    @if ($i <= $product_review->rating)
                                                                        <i class="fas fa-star"></i>
                                                                    @else
                                                                        <i class="fal fa-star"></i>
                                                                    @endif
                                                                @endfor
                                                            </span>
                                                            <p>{{ $product_review->review }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach

                                                <div class="tf__pagination mt_30">
                                                    {{ $product_reviews->links('custom_paginator') }}
                                                </div>
                                            </div>
                                            @endif

                                        </div>
                                        <div class="col-lg-4">
                                            <div class="tf__post_review">
                                                <h4>{{__('user.write a Review')}}</h4>
                                                <form id="review_form">
                                                    @csrf
                                                    <p class="rating">
                                                        <span>{{__('user.rating')}} : </span>
                                                        <i data-rating="1" class="fas fa-star product_rat" onclick="productReview(1)"></i>
                                                        <i data-rating="2" class="fas fa-star product_rat" onclick="productReview(2)"></i>
                                                        <i data-rating="3" class="fas fa-star product_rat" onclick="productReview(3)"></i>
                                                        <i data-rating="4" class="fas fa-star product_rat" onclick="productReview(4)"></i>
                                                        <i data-rating="5" class="fas fa-star product_rat" onclick="productReview(5)"></i>
                                                    </p>

                                                    <div class="row">
                                                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                                                        <input type="hidden" name="rating" value="5" id="product_rating">

                                                        <div class="col-xl-12">
                                                            <textarea name="review" rows="3"
                                                                placeholder="{{__('user.Write your review')}}"></textarea>
                                                        </div>

                                                        @if($recaptcha_setting->status==1)
                                                            <div class="col-xl-12 mt-2">
                                                                <div class="g-recaptcha" data-sitekey="{{ $recaptcha_setting->site_key }}"></div>
                                                            </div>
                                                        @endif

                                                        <div class="col-12">
                                                            @auth('web')
                                                        <button class="common_btn" type="submit">{{__('user.submit review')}}</button>
                                                            @else
                                                            <a href="{{ route('login') }}" class="common_btn" type="button">{{__('user.please login first')}}</a>
                                                            @endauth

                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tf__related_menu mt_90 xs_mt_60">
                <h2>{{__('user.related item')}}</h2>
                <div class="row related_product_slider">
                    @foreach ($related_products as $related_product)
                        <div class="col-xl-3 wow fadeInUp" data-wow-duration="1s">
                            <div class="tf__menu_item">
                                <div class="tf__menu_item_img">
                                    <img src="{{ asset($related_product->thumb_image) }}" alt="menu" class="img-fluid w-100">
                                </div>
                                <div class="tf__menu_item_text">
                                    <a class="category" href="{{ route('products',['category' => $related_product->category->slug]) }}">{{ $related_product->category->name }}</a>
                                    <a class="title" href="{{ route('show-product', $related_product->slug) }}">{{ $related_product->name }}</a>
                                    @php
                                        if ($related_product->total_review > 0) {
                                            $average = $related_product->average_rating;

                                            $int_average = intval($average);

                                            $next_value = $int_average + 1;
                                            $review_point = $int_average;
                                            $half_review=false;
                                            if($int_average < $average && $average < $next_value){
                                                $review_point= $int_average + 0.5;
                                                $half_review=true;
                                            }
                                        }
                                    @endphp
                                    <p class="rating">
                                        @if ($product->total_review > 0)
                                                @for ($i = 1; $i <=5; $i++)
                                                    @if ($i <= $review_point)
                                                        <i class="fas fa-star"></i>
                                                    @elseif ($i> $review_point )
                                                        @if ($half_review==true)
                                                            <i class="fas fa-star-half-alt"></i>
                                                            @php
                                                                $half_review=false
                                                            @endphp
                                                        @else
                                                        <i class="far fa-star"></i>
                                                        @endif
                                                    @endif
                                                @endfor
                                            @else
                                                <i class="far fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <i class="far fa-star"></i>
                                                <i class="far fa-star"></i>
                                            @endif
                                        <span>({{ $related_product->total_review }})</span>
                                    </p>
                                    @if ($related_product->is_offer)
                                            <h5 class="price">{{ $currency_icon }}{{ $related_product->offer_price }} <del>{{ $currency_icon }}{{ $related_product->price  }}</del> </h5>
                                        @else
                                            <h5 class="price">{{ $currency_icon }}{{ $related_product->price }}</h5>
                                        @endif

                                    <a class="tf__add_to_cart" href="javascript:;" onclick="load_product_model({{ $related_product->id }})">{{__('user.add to cart')}}</a>
                                    <ul class="d-flex flex-wrap justify-content-end">

                                        @auth('web')
                                            <li><a href="javascript:;" onclick="add_to_wishlist({{ $related_product->id }})"><i class="fal fa-heart"></i></a></li>
                                            @else
                                            <li><a href="javascript:;" onclick="before_auth_wishlist({{ $related_product->id }})"><i class="fal fa-heart"></i></a></li>
                                            @endauth

                                        <li><a href="{{ route('show-product', $related_product->slug) }}"><i class="far fa-eye"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <script>
        (function($) {
            "use strict";
            $(document).ready(function () {

                $("#review_form").on("submit", function(e){
                    e.preventDefault();

                    var isDemo = "{{ env('APP_MODE') }}"
                    if(isDemo == 0){
                        toastr.error('This Is Demo Version. You Can Not Change Anything');
                        return;
                    }

                    $.ajax({
                        type: 'post',
                        data: $('#review_form').serialize(),
                        url: "{{ url('/submit-review') }}",
                        success: function (response) {
                            toastr.success("{{__('user.Review added successfully')}}")
                            $("#review_form").trigger("reset");
                        },
                        error: function(response) {

                            if(response.status == 422){
                                if(response.responseJSON.errors.rating)toastr.error(response.responseJSON.errors.rating[0])
                                if(response.responseJSON.errors.review)toastr.error(response.responseJSON.errors.review[0])
                                if(response.responseJSON.errors.product_id)toastr.error(response.responseJSON.errors.product_id[0])

                                if(!response.responseJSON.errors.rating || !response.responseJSON.errors.review || !response.responseJSON.errors.product_id){
                                    toastr.error("{{__('user.Please complete the recaptcha to submit the form')}}")
                                }
                            }

                            if(response.status == 500){
                                toastr.error("{{__('user.Server error occured')}}")
                            }

                            if(response.status == 403){
                                toastr.error(response.responseJSON.message)
                            }
                        }
                    });

                })

                $("#add_to_cart").on("click", function(e){
                    e.preventDefault();
                    if ($("input[name='size_variant']").is(":checked")) {

                        $.ajax({
                            type: 'get',
                            data: $('#add_to_cart_form').serialize(),
                            url: "{{ url('/add-to-cart') }}",
                            success: function (response) {
                                let html_response = `    <div>
                                    <div class="wsus__menu_cart_header">
                                        <h5 class="mini_cart_body_item">{{__('user.Total Item')}}(0)</h5>
                                        <span class="close_cart"><i class="fal fa-times"></i></span>
                                    </div>
                                    <ul class="mini_cart_list">

                                    </ul>

                                    <p class="subtotal">{{__('user.Sub Total')}} <span class="mini_sub_total">{{ $currency_icon }}0.00</span></p>
                                    <a class="cart_view" href="{{ route('cart') }}"> {{__('user.view cart')}}</a>
                                    <a class="checkout" href="{{ route('checkout') }}">{{__('user.checkout')}}</a>
                                </div>`;


                                $(".wsus__menu_cart_boody").html(html_response);

                                $(".mini_cart_list").html(response);
                                toastr.success("{{__('user.Item added successfully')}}")
                                calculate_mini_total();

                                let new_qty = $(".cart_total_qty").html();
                                let update_qty = parseInt(new_qty) + parseInt(1);
                                $(".cart_total_qty").html(update_qty);
                            },
                            error: function(response) {
                                if(response.status == 500){
                                    toastr.error("{{__('user.Server error occured')}}")
                                }

                                if(response.status == 403){
                                    toastr.error(response.responseJSON.message)
                                }
                            }
                        });

                    } else {
                        toastr.error("{{__('user.Please select a size')}}")
                    }
                });

                $("input[name='size_variant']").on("change", function(){
                    $("#variant_price").val($(this).data('variant-price'))
                    calculatePrice()
                })

                $("input[name='optional_items[]']").change(function() {
                    calculatePrice()
                });

                $(".increment_qty_detail_page").on("click", function(){
                    let product_qty = $(".product_qty").val();
                    let new_qty = parseInt(product_qty) + parseInt(1);
                    $(".product_qty").val(new_qty);
                    calculatePrice();
                })

                $(".decrement_qty_detail_page").on("click", function(){
                    let product_qty = $(".product_qty").val();
                    if(product_qty == 1) return;
                    let new_qty = parseInt(product_qty) - parseInt(1);
                    $(".product_qty").val(new_qty);
                    calculatePrice();
                })

            });
        })(jQuery);

        function calculatePrice(){
            let optional_price = 0;
            let product_qty = $(".product_qty").val();
            $("input[name='optional_items[]']:checked").each(function() {
                let checked_value = $(this).data('optional-item');
                optional_price = parseInt(optional_price) + parseInt(checked_value);
            });

            let variant_price = $("#variant_price").val();
            let main_price = parseInt(variant_price) * parseInt(product_qty);

            let total = parseInt(main_price) + parseInt(optional_price);
            $(".grand_total").html(total)
            $("#price").val(total);
        }


        function productReview(rating){
            $(".product_rat").each(function(){
                var product_rat = $(this).data('rating')
                if(product_rat > rating){
                    $(this).removeClass('fas fa-star').addClass('fal fa-star');
                }else{
                    $(this).removeClass('fal fa-star').addClass('fas fa-star');
                }
            })
            $("#product_rating").val(rating);
        }

    </script>
@endsection
