@extends('admin.master_layout')
@section('title')
    <title>{{__('admin.Invoice')}}</title>
@endsection
<style>
    @media print {
        /* Set paper width to 80mm for thermal printer */
        body {
            width: 80mm;
            font-size: 10px; /* Adjust font size as needed */
            margin: 0; /* Remove default margins */
            padding: 0; /* Remove default padding */
        }

        /* Hide elements not needed for printing */
        .section-header,
        .order-status,
        #sidebar-wrapper,
        .print-area,
        .main-footer,
        .additional_info,
        .invoice-print .invoice-title img, /* Hide logo */
            /* Hide logo */
        .invoice-print .section-title, /* Hide Order Summary section title */
        .invoice-print .table-responsive, /* Hide Order Summary table */
        .invoice-print .text-md-right, /* Hide Print and Delete buttons */
        .invoice-print .section-title /* Hide Order Status section title */
        { /* Hide all rows except the last one */
            display: none !important;
        }

        .print_totel {
            display: block !important;
            margin-left: -100px !important;
        }

        .invoice-container {
            width: 100%;
            box-sizing: border-box;
        }

        .invoice-print .invoice-title .invoice-number {
            float: left !important;
        }

        .invoice-detail-item {
            margin-bottom: 10px;
        }

        .invoice-detail-name {
            float: left;
        }

        .invoice-detail-value {
            float: left;
        }

        .invoice-detail-value-lg {
            font-size: larger;
        }

        .invoice-detail-item::after {
            content: "";
            display: table;
            clear: both;
        }

    }
</style>
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{__('admin.Invoice')}}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a
                            href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
                    <div class="breadcrumb-item">{{__('admin.Invoice')}}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="invoice">
                    <div class="invoice-print">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="invoice-title">
                                    <h2><img src="{{ asset($setting->logo) }}" alt="" width="120px"></h2>
                                    <div class="invoice-number">Order #{{ $order->order_id }}</div>
                                </div>
                                <hr>
                                @if ($order->user)
                                    @php
                                        $orderAddress = $order->user;
                                    @endphp
                                    <div class="row">
                                        <div class="col-md-6" style="display: block !important">
                                            <address>
                                                @if ($orderAddress->email !== 'walkingcustjd@pp.co.pp')
                                                    <strong>{{__('admin.Delivery Information')}}:</strong><br>
                                                    {{ optional($order->orderAddress)->name }}<br>
                                                    @if (optional($order->orderAddress)->email)
                                                        {{ optional($order->orderAddress)->email }}<br>
                                                    @endif
                                                    @if (optional($order->orderAddress)->phone)
                                                        {{ optional($order->orderAddress)->phone }}<br>
                                                    @endif
                                                    @if (optional($order->orderAddress)->address)
                                                        {{ optional($order->orderAddress)->address }}<br>
                                                    @endif
                                                    <br>
                                                @else
                                                    <strong>Order Type: Dine-in order</strong>
                                                @endif
                                            </address>
                                        </div>

                                        @else
                                            <div class="row">
                                                <div class="col-md-6" style="display: block !important">
                                                    <address>
                                                        {{ $order->order_type }}
                                                    </address>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <address>
                                                        <strong>{{__('admin.Order Information')}}:</strong><br>
                                                        {{__('admin.Date')}}: {{ $order->created_at->format('d F, Y h:i:s A') }}
                                                        <br>
                                                        <strong>{{__('admin.Order Type')}}: </strong>
                                                        {{ $order->order_type }}
                                                       <!-- {{__('admin.Status')}} :
                                                        @if ($order->order_status == 1)
                                                            <span
                                                                class="badge badge-success">{{__('admin.Pregress')}} </span>
                                                        @elseif ($order->order_status == 2)
                                                            <span
                                                                class="badge badge-success">{{__('admin.Delivered')}} </span>
                                                        @elseif ($order->order_status == 3)
                                                            <span
                                                                class="badge badge-success">{{__('admin.Completed')}} </span>
                                                        @elseif ($order->order_status == 4)
                                                            <span
                                                                class="badge badge-danger">{{__('admin.Declined')}} </span>
                                                        @else
                                                            <span
                                                                class="badge badge-danger">{{__('admin.Pending')}}</span>
                                                        @endif
                                                        -->
                                                    </address>
                                                </div>

                                            </div>
                                    </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="section-title">{{__('admin.Order Summary')}}</div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-md">
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="25%">{{__('admin.Product')}}</th>
                                                <th width="20%">{{__('admin.Size & Optional')}}</th>
                                                <th width="10%" class="text-center">{{__('admin.Unit Price')}}</th>
                                                <th width="10%" class="text-center">{{__('admin.Quantity')}}</th>
                                                <th width="10%" class="text-right">{{__('admin.Total')}}</th>
                                            </tr>

                                            @foreach ($order->orderProducts as $index => $orderProduct)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.product.edit', $orderProduct->product_id) }}">{{ $orderProduct->product_name }}</a>
                                                    </td>
                                                    <td>
                                                        {{ $orderProduct->product_size }}
                                                        <br>
                                                        @php
                                                            $optional_items = json_decode($orderProduct->optional_item);
                                                        @endphp
                                                        @foreach ($optional_items as $optional_item)
                                                            {{ $optional_item->item }}
                                                            (+{{ $currency_icon }}{{ $optional_item->price }})
                                                            <br>
                                                        @endforeach

                                                    </td>

                                                    <td class="text-center">{{ $setting->currency_icon }}{{ $orderProduct->unit_price }}</td>
                                                    <td class="text-center">{{ $orderProduct->qty }}</td>
                                                    @php
                                                        $total = ($orderProduct->unit_price * $orderProduct->qty)  + $orderProduct->optional_price
                                                    @endphp
                                                    <td class="text-right">{{ $setting->currency_icon }}{{ $total }}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-lg-6 order-status">
                                            <div class="section-title">{{__('admin.Order Status')}}</div>
                                            <form action="{{ route('admin.update-order-status',$order->id) }}"
                                                  method="POST">
                                                @csrf
                                                @method("PUT")
                                                <div class="form-group">
                                                    <label for="">{{__('admin.Payment')}}</label>
                                                    <select name="payment_status" id="" class="form-control">
                                                        <option
                                                            {{ $order->payment_status == 0 ? 'selected' : '' }} value="0">{{__('admin.Pending')}}</option>
                                                        <option
                                                            {{ $order->payment_status == 1 ? 'selected' : '' }} value="1">{{__('admin.Success')}}</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="">{{__('admin.Order')}}</label>
                                                    <select name="order_status" id="" class="form-control">
                                                        <option
                                                            {{ $order->order_status == 0 ? 'selected' : '' }} value="0">{{__('admin.Pending')}}</option>
                                                        <option
                                                            {{ $order->order_status == 1 ? 'selected' : '' }} value="1">{{__('admin.In Progress')}}</option>
                                                        <option
                                                            {{ $order->order_status == 2 ? 'selected' : '' }}  value="2">{{__('admin.Delivered')}}</option>
                                                        <option
                                                            {{ $order->order_status == 3 ? 'selected' : '' }} value="3">{{__('admin.Completed')}}</option>
                                                        <option
                                                            {{ $order->order_status == 4 ? 'selected' : '' }} value="4">{{__('admin.Declined')}}</option>
                                                    </select>
                                                </div>

                                                <button class="btn btn-primary"
                                                        type="submit">{{__('admin.Update Status')}}</button>
                                            </form>
                                        </div>

                                        <div class="col-lg-6 text-right">

                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">{{__('admin.Subtotal')}}
                                                    : {{ $setting->currency_icon }}{{ round($order->sub_total, 2) }}</div>
                                            </div>
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">{{__('admin.Discount')}}(-)
                                                    : {{ $setting->currency_icon }}{{ round($order->coupon_price, 2) }}</div>
                                            </div>
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">{{__('admin.Delivery Charge')}}
                                                    : {{ $setting->currency_icon }}{{ round($order->delivery_charge, 2) }}</div>
                                            </div>

                                            <hr class="mt-2 mb-2">
                                            <div class="invoice-detail-item">
                                                <div
                                                    class="invoice-detail-value invoice-detail-value-lg">{{__('admin.Grand Total')}}
                                                    : {{ $setting->currency_icon }}{{ round($order->grand_total, 2) }}</div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-md-right print-area">
                            <hr>
                            <button class="btn btn-success btn-icon icon-left print_btn" data-toggle="modal"
                                    data-target="#printModal" onclick="printData({{ $order->id }})"><i
                                    class="fas fa-print"></i> {{__('admin.Print')}}</button>
                            <button class="btn btn-danger btn-icon icon-left" data-toggle="modal"
                                    data-target="#deleteModal" onclick="deleteData({{ $order->id }})"><i
                                    class="fas fa-times"></i> {{__('admin.Delete')}}</button>
                        </div>
                    </div>
                </div>

        </section>
        <div class="col-lg-12 print_totel" style="display:none !important">

            <div class="invoice-detail-item">
                <div class="invoice-detail-name">{{__('admin.Subtotal')}}
                    : {{ $setting->currency_icon }}{{ round($order->sub_total, 2) }}</div>
            </div>
            <div class="invoice-detail-item">
                <div class="invoice-detail-name">{{__('admin.Discount')}}(-)
                    : {{ $setting->currency_icon }}{{ round($order->coupon_price, 2) }}</div>
            </div>
            <div class="invoice-detail-item">
                <div class="invoice-detail-name">{{__('admin.Delivery Charge')}}
                    : {{ $setting->currency_icon }}{{ round($order->delivery_charge, 2) }}</div>
            </div>

            <hr class="mt-2 mb-2">
            <div class="invoice-detail-item">
                <div class="invoice-detail-value invoice-detail-value-lg">{{__('admin.Grand Total')}}
                    : {{ $setting->currency_icon }}{{ round($order->grand_total, 2) }}</div>
            </div>
        </div>
    </div>
    <script>
        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url("admin/delete-order/") }}' + "/" + id)
        }

        function printData(id) {
            console.log(id)
            $("#printForm").attr("action", '{{ url("admin/order-print/") }}' + "/" + id)
        }

        // (function($) {
        //     "use strict";
        //     $(document).ready(function() {

        //         $(".print_btn").on("click", function(){
        //             $(".custom_click").click();
        //             window.print()
        //         })

        //     });
        // })(jQuery);

    </script>

@endsection
