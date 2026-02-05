@extends('layout')
@section('title')
    <title>{{__('user.Orders')}}</title>
@endsection
@section('meta')
    <meta name="description" content="{{__('user.Orders')}}">
@endsection

@section('public-content')


    <!--=============================
        BREADCRUMB START
    ==============================-->
    <section class="tf__breadcrumb" style="background: url({{ asset($breadcrumb) }});">
        <div class="tf__breadcrumb_overlay">
            <div class="container">
                <div class="tf__breadcrumb_text">
                    <h1>{{__('user.Orders')}}</h1>
                    <ul>
                        <li><a href="{{ route('home') }}">{{__('user.Home')}}</a></li>
                        <li><a href="javascript:;">{{__('user.Orders')}}</a></li>
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
                                <h3>{{__('user.Orders')}}</h3>
                                <div class="tf_dashboard_order">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr class="t_header">
                                                    <th>{{__('user.Order')}}</th>
                                                    <th>{{__('user.Date')}}</th>
                                                    <th>{{__('user.Status')}}</th>
                                                    <th>{{__('user.Amount')}}</th>
                                                    <th>{{__('user.Action')}}</th>
                                                </tr>
                                                @foreach ($orders as $index => $order)
                                                    <tr>
                                                        <td>
                                                            <h5>#{{ $order->order_id }}</h5>
                                                        </td>
                                                        <td>
                                                            <p>{{ $order->created_at->format('d M Y') }}</p>
                                                        </td>
                                                        <td>

                                                        @if ($order->order_status == 1)
                                                            <span class="complete">{{__('user.Processing')}}</span>
                                                        @elseif ($order->order_status == 2)
                                                            <span class="complete">{{__('user.On the way')}}</span>
                                                        @elseif ($order->order_status == 3)
                                                            <span class="complete">{{__('user.Completed')}}</span>
                                                        @elseif ($order->order_status == 4)
                                                            <span class="cancel">{{__('user.Declined')}}</span>
                                                        @else
                                                            <span class="cancel">{{__('user.Pending')}}</span>
                                                        @endif

                                                        </td>
                                                        <td>
                                                            <h5>{{ $currency_icon }}{{ $order->grand_total }}</h5>
                                                        </td>
                                                        <td><a href="{{ route('single-order', $order->order_id) }}" class="view_invoice" data-order-id="{{ $order->id }}">{{__('user.View Details')}}</a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
