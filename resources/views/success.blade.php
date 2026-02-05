@extends('layout')

@section('title')
    <title>{{__('user.Orders')}}</title>
@endsection

@section('meta')
    <meta name="description" content="{{__('user.Orders')}}">
    <style>
    @media print {
        body * {
            visibility: hidden;
        }
        .tf__invoice,
        .tf__invoice * {
            visibility: visible;
        }
        .tf__invoice {
            position: absolute;
            left: 0;
            top: 0;
            width: 90%;
        }
    }
    /* Add this to ensure all content is visible in the PDF */
    #pdfContent {
        overflow: visible !important;
    }
    .free-item-coupon {
        background-color: #fff5e6;
        border: 2px dashed #ff7c08;
        border-radius: 10px;
        padding: 15px;
        margin-top: 20px;
        position: relative;
        overflow: hidden;
    }

    .free-item-coupon:before {
        content: 'FREE';
        position: absolute;
        top: 10px;
        right: -30px;
        background: #ff7c08;
        color: white;
        padding: 5px 30px;
        transform: rotate(45deg);
        font-size: 12px;
        font-weight: bold;
    }

    .free-item-coupon h4 {
        color: #ff7c08;
        margin-bottom: 10px;
        font-size: 18px;
    }

    .free-item {
        font-size: 16px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
    }

    .free-item:before {
        content: '✓ ';
        color: #ff7c08;
    }

    .coupon-scissors {
        position: absolute;
        top: -10px;
        left: 10px;
        font-size: 24px;
        color: #ff7c08;
        transform: rotate(-90deg);
    }
</style>
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
                <!-- Print and Home buttons -->
            </div>
        </div>
    </section>
    <!--=============================
        BREADCRUMB END
    ==============================-->

    <!--=========================
        DASHBOARD START
    ==========================-->
    <section class="tf__dashboard mt_10 xs_mt_10 mb_100 xs_mb_70">
        <div class="container">
            <div class="row">
                <div class="col-xl-9 col-lg-8 wow fadeInUp" data-wow-duration="1s">
                    <div class="tf__dashboard_content">
                        <div class="tf_dashboard_body dashboard_review">
                            <div class="tf__invoice">
                            <div class="row justify-content-end mt-3">
                    <div class="col-auto">
                       <!-- <button class="btn btn-secondary me-2" onclick="saveAsPDF()">{{__('user.Save as PDF')}}</button>-->
                        <a class="btn btn-success" href="{{ route('home') }}">{{__('user.Home')}}</a>
                    </div>
                </div>
                                @php
                                    $orderAddress = $order->orderAddress;
                                    $products = $order->orderProducts;
                                @endphp

                                <div class="tf__invoice_header">
                                    <div class="header_address">
                                        <h4>{{__('user.invoice to')}}</h4>
                                        <p>{{ $orderAddress->address }}</p>
                                        <p>{{ $orderAddress->name }}
                                            @if ($orderAddress->phone)
                                                , {{ $orderAddress->phone }}
                                            @endif
                                        </p>
                                        @if ($orderAddress->email)
                                            <p>{{ $orderAddress->email }}</p>
                                        @endif
                                        <br/>
                                       <p><strong>Order status:</strong></p> <span style="color:blue;"><b>Confirmed</b></span>
                                    </div>
                                    <div class="header_details">
                                        <p><b>{{__('user.Order ID')}}:</b> <span> #{{ $order->order_id }}</span></p>
                                        <p><b>{{__('user.date')}}:</b> <span>{{ $order->created_at->format('d M, Y') }}</span></p>
                                        <p><b>{{__('user.Payment')}}:</b> <span>{{ $order->payment_status == 1 ? 'Success' : 'Pending' }}</span></p>
                                    </div>
                                </div>

                                <div class="tf__invoice_body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr class="border_none">
                                                    <th class="sl_no">{{__('user.SL')}}</th>
                                                    <th class="package">{{__('user.item description')}}</th>
                                                    <th class="price">{{__('user.Unit Price')}}</th>
                                                    <th class="qnty">Qt</th>
                                                    <th class="total">{{__('user.Total')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
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
                                                <!-- Add the offer display here -->
                                                @php
                                                    $sub_total = $order->sub_total;
                                                @endphp
                                                @if($sub_total >= 50)
                                                    <!--<tr>
                                                        <td colspan="5">
                                                            <div class="free-item-coupon">
                                                                <div class="coupon-scissors">✂</div>
                                                                <h4>Offer Applied:</h4>
                                                                @if($sub_total >= 150)
                                                                    <label class="free-item">Free (Butter Chicken / Dal Makhni) and Mix Bread Basket Added To Your Order</label>
                                                                @elseif($sub_total >= 100)
                                                                    <label class="free-item">Free Butter Chicken / Dal Makhni Added To Your Order</label>
                                                                @elseif($sub_total >= 80)
                                                                    <label class="free-item">Free Mix Bread Basket Added To Your Order</label>
                                                                @elseif($sub_total >= 60)
                                                                    <label class="free-item">Free Rice Added To Your Order</label>
                                                                @elseif($sub_total >= 50)
                                                                    <label class="free-item">Free Plain Naan Added To Your Order</label>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    -->
                                                @endif
                                                 <!-- Print the variable below the offer -->
                                                 <tr>
                                                    <td colspan="5">
                                                        <p>Additional Instructions: {{ $inst }}</p>
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
    </section>
    <!-- Bottom Navigation Bar -->
<nav class="bottom-nav">
    <a href="{{ route('home') }}" class="bottom-nav-item">
        <i class="fas fa-utensils"></i>
        <span><strong>Menu</strong></span>
    </a>
    <a href="{{ route('reserve-table') }}" class="bottom-nav-item">
        <i class="far fa-calendar-alt"></i>
        <span><strong>Reservation</strong></span>
    </a>
    <a href="{{ route('offers') }}" class="bottom-nav-item">
        <i class="fas fa-percent"></i>
        <span><strong>Offers</strong></span>
    </a>
</nav>

<style>
    .accordion-button {
        background-color: #f8f9fa;
        color: #333;
        font-weight: bold;
    }
    .accordion-button:not(.collapsed) {
        background-color: #e9ecef;
        color: #0056b3;
    }
    .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23333'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }
    .tf__menu_item {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .tf__menu_item_text {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .tf__add_to_cart {
        margin-top: auto;
    }

    /* Bottom Navigation Styles */
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: #fff;
        display: flex;
        justify-content: space-around;
        padding: 10px 0;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }

    .bottom-nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        color: #333;
        text-decoration: none;
    }

    .bottom-nav-item i {
        font-size: 24px;
        margin-bottom: 5px;
    }

    .bottom-nav-item span {
        font-size: 12px;
    }

    /* Adjust main content to account for bottom nav */
    body {
        padding-bottom: 70px;
    }
</style>
@endsection