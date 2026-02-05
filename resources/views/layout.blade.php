<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densityDpi=device-dpi"/>
    @yield('title')
    @yield('meta')

    <link rel="icon" type="image/png" href="{{ asset($setting->favicon) }}">

    <link rel="stylesheet" href="{{ asset('user/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/venobox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/jquery.exzoom.css') }}">

    <link rel="stylesheet" href="{{ asset('user/css/spacing.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('user/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/select2.min.css') }}">

    <link rel="manifest" href="/public/manifest.json">
    <style>
        .installPWA {
            line-height: normal;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            background: #ff7c08;
            color: white;
            display: none;
        }

        .installPWA:hover {
            background: #231f40;
            transition: background ease-in-out .2s;
        }
    </style>
    @yield('custom-style')
    <!--jquery library js-->
    <script src="{{ asset('user/js/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('user/js/sweetalert2@11.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    @if ($googleAnalytic->status == 1)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $googleAnalytic->analytic_id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());
            gtag('config', '{{ $googleAnalytic->analytic_id }}');
        </script>
    @endif

    @if ($facebookPixel->status == 1)
        <script>
            !function (f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function () {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $facebookPixel->app_id }}');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
                       src="https://www.facebook.com/tr?id={{ $facebookPixel->app_id }}&ev=PageView&noscript=1"
            /></noscript>
    @endif

</head>

<body>

<div class="d-none" id="preloader">
    <div class="img">
        <img src="{{ asset('uploads/website-images/Spinner.gif') }}" alt="UniFood" class="img-fluid">
    </div>
</div>

<!--=============================
    TOPBAR START
==============================-->
<section class="tf__topbar">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-sm-6 col-md-8">
                <ul class="tf__topbar_info d-flex flex-wrap d-none d-sm-flex">
                    <li><a href="mailto:{{ $footer->email }}"><i class="fas fa-envelope"></i> {{ $footer->email }}</a>
                    </li>
                    <li class="d-none d-md-block"><a href="callto:{{ $footer->phone }}"><i class="fas fa-phone-alt"></i>
                            {{ $footer->phone }}</a></li>
                </ul>
            </div>
            <div class="col-xl-6 col-sm-6 col-md-4">
                <ul class="topbar_icon d-flex flex-wrap">
                    <marquee><p class="text-white fw-bold">Pickup and Delivery facility Availble Here</p></marquee>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--=============================
    TOPBAR END
==============================-->


<!--=============================
    MENU START
==============================-->
<nav class="navbar navbar-expand-lg main_menu">
    <div class="container">
        <!-- Navbar Brand and Collapse Button -->
        <a class="navbar-brand" href="{{ route('home') }}">
        <img src="/uploads/website-images/pp.svg" alt="Privacy Policy" class="img-fluid">
            <!--<img src="{{ asset($setting->logo) }}" alt="RegFood" class="img-fluid">-->
        </a>
        @php
            $mini_cart_contents = Cart::content();
        @endphp
        <ul class="menu_icon d-flex flex-wrap vis">
            <li>
                <a class="cart_icon" href="{{ route('cart') }}"><i class="fas fa-shopping-basket"></i>
                    <span class="cart_total_qty">{{ count($mini_cart_contents) }}</span></a>
            </li>
            <li>
            <a class="nav-link" href="{{ route('location') }}" style="margin-right:10px;"><i class="fa fa-map-marker"></i></a>
            </li>
        </ul>
<!--
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="far fa-bars menu_icon_bar"></i>
            <i class="far fa-times close_icon_close"></i>
        </button>
        -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav m-auto">
                <li class="nav-item">
                    
                </li>
<!--
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about-us') }}">{{__('user.About Us')}}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products') }}">{{__('user.Products')}}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">{{__('user.pages')}} <i class="far fa-plus"></i></a>
                    <ul class="droap_menu">

                        <li>
                            <a href="{{ route('our-chef') }}">{{__('user.Our chef')}}</a>
                        </li>

                        <li><a href="{{ route('testimonial') }}">{{__('user.Testimonial')}}</a></li>

                        <li><a href="{{ route('faq') }}">{{__('user.FAQs')}}</a></li>

                        <li><a href="{{ route('privacy-policy') }}">{{__('user.privacy policy')}}</a></li>
                        <li><a href="{{ route('terms-and-condition') }}">{{__('user.terms and condition')}}</a></li>

                        @foreach ($custom_pages as $custom_page)
                            <li><a href="{{ route('show-page', $custom_page->slug) }}">{{ $custom_page->page_name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('blogs') }}">{{__('user.Blogs')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contact-us') }}">{{__('user.contact us')}}</a>
                </li>
        -->
                <li class="nav-item">
                    <button class="installPWA">Install</button>
                </li>
            </ul>

            @php
                $mini_cart_contents = Cart::content();
            @endphp
            <ul class="menu_icon d-flex flex-wrap">
                <div class="d-flex order-1 order-lg-0 ml-auto">
                    <!-- Cart Button -->
                    <ul class="menu_icon d-flex flex-wrap">
                        <li>
                            <a class="cart_icon" href="{{ route('cart') }}"><i class="fas fa-shopping-basket"></i>
                                <span class="cart_total_qty">{{ count($mini_cart_contents) }}</span></a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ route('location') }}"><i class="fa fa-map-marker"></i></a>
                        </li>
                    </ul>
                </div>
            </ul>
        </div>
    </div>
</nav>
<!--=============================
    MENU END
==============================-->

@yield('public-content')

<!-- CART POPUT START -->
<div class="tf__cart_popup">
    <div class="modal fade" id="cartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fal fa-times"></i></button>
                    <div class="load_product_modal_response">
                        <img src="{{ asset('uploads/website-images/Spinner-1s-200px.gif') }}" alt="">
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- CART POPUT END -->

<!--=============================
    FOOTER START
==============================-->
<!---
<footer style="background: url({{ asset($footer->footer_background) }});">
    <div class="footer_overlay pt_100 xs_pt_70 pb_100 xs_pb_20">
        <div class="container wow fadeInUp" data-wow-duration="1s">
            <div class="row justify-content-between">
                <div class="col-xxl-4 col-lg-4 col-sm-9 col-md-7">
                    <div class="tf__footer_content">
                        <a class="footer_logo" href="{{ route('home') }}">
                            <img src="{{ asset($setting->footer_logo) }}" alt="RegFood" class="img-fluid w-100">
                        </a>
                        <span>{{ $footer->about_us }}</span>
                        <ul class="social_link d-flex flex-wrap">
                            @foreach ($social_links as $social_link)
                                <li><a href="{{ $social_link->link }}"><i class="{{ $social_link->icon }}"></i></a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!--
                <div class="col-xxl-2 col-lg-2 col-sm-5 col-md-5">
                    <div class="tf__footer_content">
                        <h3>{{__('user.Short Link')}}</h3>
                        <ul>
                            <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                            <li><a href="{{ route('about-us') }}">{{__('user.About Us')}}</a></li>
                            <li><a href="{{ route('contact-us') }}">{{__('user.Contact Us')}}</a></li>
                            <li><a href="{{ route('our-chef') }}">{{__('user.Our Chef')}}</a></li>
                            <li><a href="{{ route('our-chef') }}">{{__('user.Dashboard')}}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xxl-2 col-lg-2 col-sm-6 col-md-5 order-md-4">
                    <div class="tf__footer_content">
                        <h3>{{__('user.Help Link')}}</h3>
                        <ul>
                            <li><a href="{{ route('blogs') }}">{{__('user.Our Blogs')}}</a></li>
                            <li><a href="{{ route('testimonial') }}">{{__('user.Testimonial')}}</a></li>
                            <li><a href="{{ route('faq') }}">{{__('user.FAQ')}}</a></li>
                            <li><a href="{{ route('privacy-policy') }}">{{__('user.Privacy and Policy')}}</a></li>
                            <li><a href="{{ route('terms-and-condition') }}">{{__('user.Terms and Conditions')}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
      
                <div class="col-xxl-3 col-lg-4 col-sm-9 col-md-7 order-lg-4">
                    <div class="tf__footer_content">
                        <h3>{{__('user.Contact Us')}}</h3>
                        <p class="info"><i class="fas fa-phone-alt"></i> {{ $footer->phone }}</p>
                        <p class="info"><i class="fas fa-envelope"></i> {{ $footer->email }}</p>
                        <p class="info"><i class="far fa-map-marker-alt"></i> {{ $footer->address }}</p>
                    </div>
                </div>
                  -->
            </div>
        </div>
    </div>
    <div class="tf__footer_bottom d-flex flex-wrap">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="tf__footer_bottom_text">
                        <p>{{ $footer->copyright }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<!--=============================
        FOOTER END
    ==============================-->

@if ($tawk_setting->status == 1)
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        (function () {
            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = '{{ $tawk_setting->chat_link }}';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
@endif


@if ($cookie_consent->status == 1)
    <script src="{{ asset('user/js/cookieconsent.min.js') }}"></script>

    <script>
        window.addEventListener("load", function () {
            window.wpcc.init({
                "border": "{{ $cookie_consent->border }}",
                "corners": "{{ $cookie_consent->corners }}",
                "colors": {
                    "popup": {
                        "background": "{{ $cookie_consent->background_color }}",
                        "text": "{{ $cookie_consent->text_color }} !important",
                        "border": "{{ $cookie_consent->border_color }}"
                    },
                    "button": {
                        "background": "{{ $cookie_consent->btn_bg_color }}",
                        "text": "{{ $cookie_consent->btn_text_color }}"
                    }
                },
                "content": {
                    "href": "{{ route('privacy-policy') }}",
                    "message": "{{ $cookie_consent->message }}",
                    "link": "{{ $cookie_consent->link_text }}",
                    "button": "{{ $cookie_consent->btn_text }}"
                }
            })
        });
    </script>
@endif

<!--=============================
        SCROLL BUTTON START
    ==============================-->
<!--<div class="tf__scroll_btn"><i class="fas fa-hand-pointer"></i></div>-->
<!--=============================
    SCROLL BUTTON END
==============================-->

<!--bootstrap js-->
<script src="{{ asset('user/js/bootstrap.bundle.min.js') }}"></script>
<!--font-awesome js-->
<script src="{{ asset('user/js/Font-Awesome.js') }}"></script>
<!-- slick slider -->
<script src="{{ asset('user/js/slick.min.js') }}"></script>
<!-- isotop js -->
<script src="{{ asset('user/js/isotope.pkgd.min.js') }}"></script>
<!-- counter up js -->
<script src="{{ asset('user/js/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('user/js/jquery.countup.min.js') }}"></script>
<!-- nice select js -->
<script src="{{ asset('user/js/jquery.nice-select.min.js') }}"></script>
<!-- venobox js -->
<script src="{{ asset('user/js/venobox.min.js') }}"></script>
<!-- sticky sidebar js -->
<script src="{{ asset('user/js/sticky_sidebar.js') }}"></script>
<!-- wow js -->
<script src="{{ asset('user/js/wow.min.js') }}"></script>
<!-- ex zoom js -->
<script src="{{ asset('user/js/jquery.exzoom.js') }}"></script>

<!--main/custom js-->
<script src="{{ asset('user/js/main.js') }}"></script>

<script src="{{ asset('toastr/toastr.min.js') }}"></script>
<script src="{{ asset('backend/js/select2.min.js') }}"></script>
<script src="{{ asset('backend/js/bootstrap-datepicker.min.js') }}"></script>


<script>
    @if(Session::has('messege'))
    var type = "{{Session::get('alert-type','info')}}"
    switch (type) {
        case 'info':
            toastr.info("{{ Session::get('messege') }}");
            break;
        case 'success':
            toastr.success("{{ Session::get('messege') }}");
            break;
        case 'warning':
            toastr.warning("{{ Session::get('messege') }}");
            break;
        case 'error':
            toastr.error("{{ Session::get('messege') }}");
            break;
    }
    @endif
</script>

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            toastr.error('{{ $error }}');
        </script>
    @endforeach
@endif


<script>
    (function ($) {
        "use strict";
        $(document).ready(function () {
            $(".first_menu_product").click();

            $('.select2').select2();
            $('.modal_select2').select2({
                dropdownParent: $("#address_modal")
            });

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                startDate: '-Infinity'
            });

            $(document).on('click', '.mini-item-remove', function () {
                let root_li = $(this).parents('li');
                let rowid = root_li.data('mini-item-rowid');
                root_li.remove();

                let is_cart_page = "{{ Route::is('cart') ? 'yes' : 'no' }}";
                if (is_cart_page == 'yes') {
                    $(".main-cart-item-" + rowid).remove();
                    calculate_total();
                }

                calculate_mini_total();

                $.ajax({
                    type: 'get',
                    url: "{{ url('/remove-cart-item') }}" + "/" + rowid,
                    success: function (response) {
                        toastr.success(response.message);

                        let ready_to_reload = "{{ Route::is('checkout') || Route::is('payment') ? 'yes' : 'no' }}"
                        if (ready_to_reload == 'yes') {
                            window.location.reload();
                        }
                    },
                    error: function (response) {
                        if (response.status == 500) {
                            toastr.error("{{__('user.Server error occured')}}")
                        }

                        if (response.status == 403) {
                            toastr.error("{{__('user.Server error occured')}}")
                        }
                    }
                });
            });

            $("#subscribe_form").on('submit', function (e) {
                e.preventDefault();
                var isDemo = "{{ env('APP_MODE') }}"
                if (isDemo == 0) {
                    toastr.error('This Is Demo Version. You Can Not Change Anything');
                    return;
                }

                $("#subscribe_btn").prop("disabled", true);
                $("#subscribe_btn").html(`<i class="fas fa-spinner"></i>`);

                $.ajax({
                    type: 'POST',
                    data: $('#subscribe_form').serialize(),
                    url: "{{ route('subscribe-request') }}",
                    success: function (response) {
                        toastr.success(response.message)
                        $("#subscribe_form").trigger("reset");
                        $("#subscribe_btn").prop("disabled", false);
                        $("#subscribe_btn").html(`<i class="fas fa-paper-plane"></i>`);
                    },
                    error: function (response) {
                        $("#subscribe_btn").prop("disabled", false);
                        $("#subscribe_btn").html(`<i class="fas fa-paper-plane"></i>`);

                        if (response.status == 403) {
                            if (response.responseJSON.message) toastr.error(response.responseJSON.message)
                        }
                    }
                });
            })

            $("#upload_user_avatar_form").on("submit", function (e) {
                e.preventDefault();

                var isDemo = "{{ env('APP_MODE') }}"
                if (isDemo == 0) {
                    toastr.error('This Is Demo Version. You Can Not Change Anything');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    data: new FormData(this),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    url: "{{ route('upload-user-avatar') }}",
                    success: function (response) {
                        toastr.success(response.message)
                    },
                    error: function (response) {

                    }
                });
            })
        });
    })(jQuery);

    function previewThumnailImage(event) {
        var reader = new FileReader();
        reader.onload = function () {
            var output = document.getElementById('preview-user-avatar');
            output.src = reader.result;
        }

        reader.readAsDataURL(event.target.files[0]);
        $("#upload_user_avatar_form").submit();
    }

    function calculate_mini_total() {
        let mini_sub_total = 0;
        let mini_total_item = 0;
        $(".mini-input-price").each(function () {
            let current_val = $(this).val();
            mini_sub_total = parseInt(mini_sub_total) + parseInt(current_val);
            mini_total_item = parseInt(mini_total_item) + parseInt(1);
        });

        $(".mini_sub_total").html(`{{ $currency_icon }}${mini_sub_total}`);
        $(".topbar_cart_qty").html(mini_total_item);
        $(".mini_cart_body_item").html(`{{__('user.Total Item')}}(${mini_total_item})`);

        let mini_empty_cart = `<div class="wsus__menu_cart_header">
                <h5>{{__('user.Your cart is empty')}}</h5>
                <span class="close_cart"><i class="fal fa-times"></i></span>
            </div>
            `;

        if (mini_total_item == 0) {
            $(".wsus__menu_cart_boody").html(mini_empty_cart)
        }
    }

    function load_product_model(product_id) {

        $("#preloader").addClass('preloader')
        $("#preloader").removeClass('d-none')

        $.ajax({
            type: 'get',
            url: "{{ url('/load-product-modal') }}" + "/" + product_id,
            success: function (response) {
                $("#preloader").removeClass('preloader')
                $("#preloader").addClass('d-none')
                $(".load_product_modal_response").html(response)
                $("#cartModal").modal('show');
            },
            error: function (response) {
                toastr.error("{{__('user.Server error occured')}}")
            }
        });
    }

    function add_to_wishlist(id) {
        $.ajax({
            type: 'get',
            url: "{{ url('/add-to-wishlist') }}" + "/" + id,
            success: function (response) {
                toastr.success("{{__('user.Wishlist added successfully')}}");
            },
            error: function (response) {
                if (response.status == 500) {
                    toastr.error("{{__('user.Server error occured')}}")
                }

                if (response.status == 403) {
                    toastr.error(response.responseJSON.message)
                }
            }
        });
    }

    function before_auth_wishlist() {
        toastr.error("{{__('user.Please login first')}}")
    }

</script>


<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/public/sw.js')
                .then(registration => {
                    console.log('Service Worker registered');
                })
                .catch(error => {
                    console.error('Service Worker registration failed:', error);
                });
        });

        window.addEventListener('beforeinstallprompt', (event) => {
            console.log('beforeinstallprompt fired');
            event.preventDefault();
            document.querySelector('.installPWA').style.display = 'inline';
            document.querySelector('.installPWA').addEventListener('click', () => {
                event.prompt();
                document.querySelector('.installPWA').style.display = 'none';
            });
        });
    }
</script>

</body>


</html>
