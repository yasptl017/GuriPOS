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
                                <div class="tf__invoice">
                                    <a class="go_back" href="{{ route('orders') }}"><i
                                            class="fas fa-long-arrow-alt-left"></i> {{__('user.go back')}}</a>

                                    <div class="tf__track_order">
                                        <ul>
                                            @if ($order->order_status == 1)
                                                <li class="active">{{__('user.order pending')}}</li>
                                                <li class="active">{{__('user.order accept')}}</li>
                                                <li class="active">{{__('user.order process')}}</li>
                                                <li>{{__('user.on the way')}}</li>
                                                <li>{{__('user.Completed')}}</li>
                                            @elseif ($order->order_status == 2)
                                                <li class="active">{{__('user.order pending')}}</li>
                                                <li class="active">{{__('user.order accept')}}</li>
                                                <li class="active">{{__('user.order process')}}</li>
                                                <li class="active">{{__('user.on the way')}}</li>
                                                <li>{{__('user.Completed')}}</li>
                                            @elseif ($order->order_status == 3)
                                                <li class="active">{{__('user.order pending')}}</li>
                                                <li class="active">{{__('user.order accept')}}</li>
                                                <li class="active">{{__('user.order process')}}</li>
                                                <li class="active">{{__('user.on the way')}}</li>
                                                <li class="active">{{__('user.Completed')}}</li>
                                            @elseif ($order->order_status == 4)
                                                <li class="active">{{__('user.order declined')}}</li>
                                                <li>{{__('user.order accept')}}</li>
                                                <li>{{__('user.order process')}}</li>
                                                <li>{{__('user.on the way')}}</li>
                                                <li>{{__('user.Completed')}}</li>
                                            @else
                                                <li class="active">{{__('user.order pending')}}</li>
                                                <li>{{__('user.order accept')}}</li>
                                                <li>{{__('user.order process')}}</li>
                                                <li>{{__('user.on the way')}}</li>
                                                <li>{{__('user.Completed')}}</li>
                                            @endif
                                        </ul>
                                    </div>

                                    @php
                                        $orderAddress = $order->orderAddress;
                                    @endphp
                       

                                    <div class="tf__invoice_header">
                                        <div class="header_address">


                                        </div>
                                        <div class="header_address">
                                            <p><b>{{__('user.Order ID')}}:</b> <span> #{{ $order->order_id }}</span></p>
                                            <p><b>{{__('user.date')}}:</b> <span>{{ $order->created_at->format('d M, Y') }}</span></p>
                                            <p><b>{{__('user.Payment')}}:</b> <span>{{ $order->payment_status == 1 ? 'Success' : 'Pending' }}</span></p>
                                        </div>
                                    </div>
                                    <div class="tf__invoice_body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <tbody>
                                                    <tr class="border_none">
                                                        <th class="sl_no">{{__('user.SL')}}</th>
                                                        <th class="package">{{__('user.item description')}}</th>
                                                        <th class="price">{{__('user.Unit Price')}}</th>
                                                        <th class="qnty">{{__('user.Quantity')}}</th>
                                                        <th class="total">{{__('user.Total')}}</th>
                                                    </tr>
                                                    @php
                                                        $products = $order->orderProducts;
                                                    @endphp
                                                    @foreach ($products as $index => $product)
                                                        <tr>
                                                            <td class="sl_no">{{ ++$index }}</td>
                                                            <td class="package">
                                                                <p>{{ $product->product_name }}</p>
                                                                <span class="size">{{ $product->product_size }}</span>
                                                                @php
                                                                    $optional_items = json_decode($product->optional_item);
                                                                @endphp
                                                                @foreach ($optional_items as $optional_item)
                                                                    <span class="coca_cola">{{ $optional_item->item }}(+{{ $currency_icon }}{{ $optional_item->price }})</span>
                                                                @endforeach
                                                            </td>
                                                            <td class="price">
                                                                <b>{{ $currency_icon }}{{ $product->unit_price }}</b>
                                                            </td>
                                                            <td class="qnty">
                                                                <b>{{ $product->qty }}</b>
                                                            </td>
                                                            <td class="total">
                                                                <b>{{ $currency_icon }}{{ ($product->qty * $product->unit_price) + $product->optional_price }}</b>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td class="package" colspan="3">
                                                            <b>{{__('user.sub total')}}</b>
                                                        </td>
                                                        <td class="qnty">
                                                            <b>{{ $order->product_qty }}</b>
                                                        </td>
                                                        <td class="total">
                                                            <b>{{ $currency_icon }}{{ $order->sub_total }}</b>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="package coupon" colspan="3">
                                                            <b>(-) {{__('user.Discount coupon')}}</b>
                                                        </td>
                                                        <td class="qnty">
                                                            <b></b>
                                                        </td>
                                                        <td class="total coupon">
                                                            <b>{{ $currency_icon }}{{ $order->coupon_price }}</b>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="package coast" colspan="3">
                                                            <b>(+) {{__('user.Delivery Charge')}}</b>
                                                        </td>
                                                        <td class="qnty">
                                                            <b></b>
                                                        </td>
                                                        <td class="total coast">
                                                            <b>{{ $currency_icon }}{{ $order->delivery_charge }}</b>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="package" colspan="3">
                                                            <b>{{__('user.Grand Total')}}</b>
                                                        </td>
                                                        <td class="qnty">
                                                            <b></b>
                                                        </td>
                                                        <td class="total">
                                                            <b>{{ $currency_icon }}{{ $order->grand_total }}</b>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
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
