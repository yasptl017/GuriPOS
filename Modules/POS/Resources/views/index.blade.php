@extends('admin.master_layout')
@section('title')
    <title>{{__('admin.POS')}}</title>
@endsection
@section('admin-content')
    <style>
        /* Base table styles */
.table td, .table th {
    padding: .35rem !important;
}

/* Number input styles */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type=number] {
    -moz-appearance: textfield;
}

select option {
            height: 40px;
            line-height: 50px;
            padding: 0 15px;
            font-size: 30px;
        }

        /* Or, target the select element and then target the option elements */
        select#category_id {
            font-size: 20px; /* Increase the font-size to make the options larger */
        }

        select#category_id option {
            padding: 15px; /* Add padding around the options */
        }
/* Base select styles */
select {
    font-size: 18px;
    height: 50px;
    padding: 8px 15px;
    width: 100%;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-image: url("data:image/svg+xml;utf8,<svg fill='black' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/><path d='M0 0h24v24H0z' fill='none'/></svg>");
    background-repeat: no-repeat;
    background-position-x: 98%;
    background-position-y: 50%;
    border: 2px solid #007bff;
    border-radius: 8px;
}

/* Select2 specific styles */
.select2-container {
    width: 100% !important;
}

.select2-container .select2-selection--single {
    height: 60px !important;
   
    font-size: 22px !important;
    border: 3px solid #007bff !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 45px !important;
    font-size: 22px !important;
    color: #333 !important;
    font-weight: 500 !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 68px !important;
    width: 40px !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-width: 8px 8px 0 8px !important;
    margin-left: -10px !important;
    margin-top: 4px !important;
}

/* Dropdown styles */
.select2-results__option {
    padding: 15px 20px !important;
    font-size: 20px !important;
    line-height: 1.5 !important;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #007bff !important;
}

.select2-dropdown {
    border: 3px solid #007bff !important;
    border-radius: 10px !important;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important;
}

.select2-search--dropdown .select2-search__field {
    padding: 12px !important;
    font-size: 18px !important;
    border: 2px solid #ddd !important;
    border-radius: 6px !important;
}

.tablePad{
    padding-top: 16px !important; 
    padding-bottom: 16px !important;
}

/* Mobile/touch device optimization */
@media (hover: none) and (pointer: coarse) {
    .select2-container .select2-selection--single {
        height: 80px !important;
        font-size: 24px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 55px !important;
        font-size: 24px !important;
    }

    .select2-results__option {
        padding: 20px !important;
        font-size: 22px !important;
    }
    
    .select2-search--dropdown .select2-search__field {
        font-size: 20px !important;
        padding: 15px !important;
    }
}
    </style>


    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div
                class="section-body">

                <div>
                    <livewire:table-manager/>
                </div>

                <div class="row">
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header">
                                <form id="product_search_form">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="name"
                                                   placeholder="{{__('admin.Search here..')}}" autocomplete="off"
                                                   value="{{ request()->get('name') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <select name="category_id" id="category_id" class="form-control"
                                                    onchange="submitForm()">
                                                <option value="">{{__('admin.Select Category')}}</option>
                                                @if (request()->has('category_id'))
                                                    @foreach ($categories as $category)
                                                        <option
                                                            {{ request()->get('category_id') == $category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                @else
                                                    @foreach ($categories as $category)
                                                        <option
                                                            value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-primary"
                                                    id="search_btn_text">{{__('admin.Search')}}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body product_body">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-header">
                                <select name="order_type" id="order_option" class="form-control ">
                                    <option value="default"
                                            disabled
                                            style=" display:none;"
                                    >{{__('Order Type')}}</option>

                                    <option value="DineIn"
                                            selected
                                            id="order_type_dine_in"
                                    >Dine In
                                    </option>
                                    <option value="Pickup">Pick up</option>
                                    <option value="Delivery">Delivery</option>

                                </select>
                                <select name="order_type" id="payment_option" class="form-control" style="margin-left: 5px;">
                                    <option value="unpaid"
                                    >Unpaid
                                    </option>

                                    <option
                                        selected
                                        value="paid"
                                        id="payment_option_paid"
                                    >
                                        Paid
                                    </option>
                                </select>
                                <!-- <h5 style="display: flex; align-items:center; gap:0.5rem;" class="w-100">
                                    <a href="{{ route('admin.pendingorder') }}" class="btn btn-danger"
                                       style="margin-left: auto;" id="pendingOrderLink">
                                        Pending Orders: <span id="pendingOrderCount">{{ $pendingOrderCount }}</span>
                                    </a>
                                </h5>-->
                            </div>
                            <div class="card-header">
                                <div class="row w-100">
                                    <div class="col-md-8">
                                        <select name="customer_id" id="customer_id" class="form-control select2">
                                            <option value="">{{__('admin.Select Customer')}}</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                        id="{{'customer_' . $customer->id}}"
                                                        data-order-count="{{ $customer->orderCount }}"
                                                        data-address="{{ $customer->address }}"
                                                        data-distance="{{ $customer->address_distance }}"
                                                >
                                                    {{ $customer->name }}{{ $customer->phone ? ' - ' . $customer->phone : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button data-toggle="modal" data-target="#createNewUser" type="button"
                                                class="btn btn-primary w-100 tablePad"><i class="fa fa-plus"
                                                                                 aria-hidden="true"></i>{{__('admin.New')}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group" style="margin-bottom: 2px !important;">
                                    <label for="customer-input">{{__('Customer Details')}}:</label>
                                    <textarea id="customer-input" class="form-control" value="walking"
                                              oninput="updateTotal()" rows="4"></textarea>
                                </div>
                                <br/>
                                <div class="row" style="margin-left: 0px !important; margin-bottom:0px !important;">
                                    <div class="form-group">
                                        <label for="discount">{{__('admin.Discount')}} (%):</label>
                                        <input type="number" id="discount" class="form-control"
                                               value="{{ env('DEFAULTS_DISCOUNT') }}" oninput="updateTotal()">
                                    </div>
                                    {{--                                    <div class="form-group" style="margin-left: 5px !important;">--}}
                                    {{--                                        <label for="delivery">{{__('admin.Delivery')}}:</label>--}}
                                    {{--                                        <input type="number" id="delivery" class="form-control" value="0"--}}
                                    {{--                                               oninput="updateTotal()">--}}
                                    {{--                                    </div>--}}
                                </div>
                                <div class="shopping-card-body">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>{{__('admin.Item')}}</th>
                                            <th>{{__('admin.Qty')}}</th>
                                            <th>{{__('admin.Price')}}</th>
                                            <th>{{__('admin.Action')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $sub_total = 0;
                                            $coupon_price = 0.00;
                                        @endphp
                                        @foreach ($cart_contents as $cart_index => $cart_content)
                                            <tr>
                                                <td>
                                                    <p style="line-height: 0 !important;">{{ substr($cart_content['name'], 0, 20) }}
                                                        @if (!empty($cart_content['options']['size']) || strtolower($cart_content['options']['size']) !== 'regular')
                                                            ({{ $cart_content['options']['size'] }})
                                                        @endif
                                                    </p>

                                                </td>
                                                <td data-rowid="{{ $cart_content['id'] }}">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <button class="btn btn-danger btn-sm qty-btn minus-btn">-
                                                            </button>
                                                        </div>
                                                        <input min="1" type="number" value="{{ $cart_content['qty'] }}"
                                                               class="form-control pos_input_qty">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-primary btn-sm qty-btn plus-btn">+
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                @php
                                                    $item_price = $cart_content['price'] * $cart_content['qty'];
                                                    $item_total = $item_price + $cart_content['options']['optional_item_price'];
                                                    $sub_total += $item_total;
                                                @endphp
                                                <td>{{ $currency_icon }}{{ $item_total }}</td>
                                                <td>
                                                    <a href="javascript:"
                                                       onclick="removeCartItem('{{ $cart_content['id'] }}')"><i
                                                            class="fa fa-trash" aria-hidden="true"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <div>
                                        <p><span>{{__('admin.Subtotal')}}</span> :
                                            <span>{{ $currency_icon }}{{ $sub_total }}</span></p>
                                        <p><span>{{__('Discount (-)')}}</span> : <span id="report_coupon_price">{{ $currency_icon }}0.00</span>
                                        </p>
                                        <p><span>{{__('admin.Delivery')}}</span> : <span
                                                id="report_delivery_fee">{{ $currency_icon }}0.00
                                            </span>
                                        </p>
                                        <p><span>{{__('admin.Total')}}</span> : <span
                                                id="report_total_fee">{{ $currency_icon }}{{ $sub_total - $coupon_price}}</span>
                                        </p>
                                    </div>
                                    <input type="hidden" id="cart_sub_total" value="{{ $sub_total }}">
                                </div>
                                <br>
                                <div id="order_count_display" style="display:none;">
                                    <form id="coupon_form">
                                        <div class="input-group w-50">
                                            <input name="coupon" type="text" placeholder="{{__('user.Coupon Code')}}"
                                                   class="form-control rounded-3 mr-2">
                                            <button type="submit" class="btn btn-success">{{__('user.apply')}}</button>
                                        </div>
                                    </form>
                                </div>
                                <br>
                                <div>


                                    <form action="{{ route('admin.print.order2') }}"
                                          id="printOrderForm"
                                          style="display: inline-flex !important;" method="POST"
                                    >
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-primary">Update Kitchen
                                        </button>
                                    </form>
                                    <button id="placeOrderBtn"
                                            class="btn btn-success">{{__('admin.Place order')}}</button>
                                    <a href="{{ route('admin.cart-clear') }}"
                                       class="btn btn-danger">{{__('admin.Reset')}}</a>
                                    <button id="receiveBtn" class="btn btn-primary" data-toggle="modal"
                                            data-target="#receiveModal">
                                        <i class="fas fa-calculator"></i>
                                    </button>
                                </div>
                                <form id="placeOrderForm" action="{{ route('admin.place-order') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="0" name="order_type" id="order_type">
                                    <input type="hidden" value="{{ $sub_total }}" name="sub_total" id="order_sub_total">
                                    <input type="hidden" value="walking" name="customerDetails" id="customerInput">
                                    <input type="hidden" name="customer_id" id="order_customer_id">
                                    <input type="hidden" name="address_id" id="order_address_id">
                                    <input type="hidden" value="0.00" name="coupon_price" id="coupon_price">
                                    <input type="hidden" value="0.00" name="delivery_fee" id="order_delivery_fee">
                                    <input type="hidden" value="{{ $sub_total }}" name="total_fee" id="order_total_fee">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Create new user modal -->
    <div class="modal fade" id="createNewUser" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('admin.Create new customer')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <form id="createNewUserForm" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="">{{__('admin.Name')}} <span class="text-danger">*</span></label>
                                <input type="text" name="name" autocomplete="off" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="">{{__('admin.Email')}} <span class="text-danger">*</span></label>
                                <input type="email" name="email" value="no@email.com" autocomplete="off" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="">{{__('admin.Phone')}} <span class="text-danger">*</span></label>
                                <input type="text" name="phone" autocomplete="off" class="form-control">
                            </div>

                            <x-address-input>
                                <div class="form-group">
                                    <label for="">{{__('admin.Address')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="address" id="address-input" class="form-control">
                                </div>
                            </x-address-input>
                            {{--                            <div class="form-group">--}}
                            {{--                                <label for="">{{__('admin.Address')}} <span class="text-danger">*</span></label>--}}
                            {{--                                <input type="text" name="address" autocomplete="off" class="form-control">--}}
                            {{--                            </div>--}}
                            <button class="btn btn-primary" type="submit">{{__('Save')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Receive Modal -->
    <div class="modal fade" id="receiveModal" tabindex="-1" role="dialog" aria-labelledby="receiveModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="receiveModalLabel">{{__('admin.Receive Payment')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="receivePaymentForm">
                        <div class="form-group">
                            <label for="paymentAmount">{{__('admin.Payment Amount')}}</label>
                            <input type="number" id="paymentAmount" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="changeAmount">{{__('admin.Change Amount')}}</label>
                            <input type="number" id="changeAmount" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="totalAmount">Total Amount</label>
                            <p id="totalAmount"></p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('admin.Close')}}</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Product Modal -->
    <div class="tf__dashboard_cart_popup">
        <div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="load_product_modal_response">

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>


        (function ($) {
            "use strict";
            $(document).ready(function () {
                $("#coupon_form").on("submit", function (e) {
                    e.preventDefault();

                    $.ajax({
                        type: 'get',
                        data: $('#coupon_form').serialize(),
                        url: "{{ url('/apply-coupon') }}",
                        success: function (response) {
                            toastr.success(response.message)
                            $("#coupon_form").trigger("reset");

                            $("#couon_price").val(response.discount);
                            $("#couon_offer_type").val(response.offer_type);

                            calculateTotalFee();
                        },
                        error: function (response) {
                            if (response.status == 422) {
                                if (response.responseJSON.errors.coupon) toastr.error(response.responseJSON.errors.coupon[0])
                            }

                            if (response.status == 500) {
                                toastr.error("{{__('user.Server error occured')}}")
                            }

                            if (response.status == 403) {
                                toastr.error(response.responseJSON.message)
                            }
                        }
                    });
                })
                loadProudcts()
                $(".pos_input_qty").on("change keyup", function (e) {

                    let quantity = $(this).val();
                    let parernt_td = $(this).parents('td');
                    let rowid = parernt_td.data('rowid')

                    $.ajax({
                        type: 'get',
                        data: {rowid, quantity},
                        url: "{{ route('admin.cart-quantity-update') }}",
                        success: function (response) {
                            $(".shopping-card-body").html(response)
                            calculateTotalFee();
                        },
                        error: function (response) {
                            if (response.status == 500) {
                                toastr.error("{{__('admin.Server error occured')}}")
                            }

                            if (response.status == 403) {
                                toastr.error("{{__('admin.Server error occured')}}")
                            }
                        }
                    });

                });

                function updateOrderCount() {
                    var selectedIndex = this.selectedIndex;
                    var selectedOption = this.options[selectedIndex];
                    var orderCount = selectedOption.getAttribute('data-order-count');

                    // Adding the form for coupon code
                    if (orderCount > 0) {
                        $("#order_count_display").show();
                    } else {
                        $("#order_count_display").hide();
                    }
                }

                function


                updateCustomerID() {
                    var customer_id = $(this).val();
                    $("#address_customer_id").val(customer_id);
                    $("#order_customer_id").val(customer_id);

                    var selectedIndex = this.selectedIndex;
                    var selectedOption = this.options[selectedIndex];
                    var customerName = selectedOption.text.split(" - ")[0];
                    var customerPhone = selectedOption.text.split(" - ")[1];
                    var customerAddress = selectedOption.getAttribute('data-address');

                    var customerDetails = "Name: " + customerName + "\n";
                    if (customerPhone) {
                        customerDetails += "Phone: " + customerPhone + "\n";
                    }
                    if (customerAddress) {
                        customerDetails += "Address: " + customerAddress;
                    }
                    $("#customer-input").val(customerDetails);
                }

                $("#customer_id").on("change", updateCustomerID);
                $("#customer_id").on("change", updateOrderCount);

                function updateDeliverCharge() {
                    const orderType = $("#order_option").val();
                    if (orderType === "Pickup" || orderType === "DineIn") {
                        $('#order_delivery_fee').val(0);
                    } else {
                        const selectedIndex = this.selectedIndex;
                        const selectedOption = this.options[selectedIndex];
                        const areas = {{Js::from($delivery_areas)}};
                        const deliveryDistance = selectedOption.getAttribute('data-distance');

                        $('#order_delivery_fee').val(0);
                        areas.forEach(area => {
                            if (area.min_range <= deliveryDistance / 1000 && area.max_range >= deliveryDistance / 1000) {
                                document.querySelector("#order_delivery_fee").value = area.delivery_fee;
                            }
                        });
                    }
                    calculateTotalFee();
                }

                $("#customer_id").on("change", updateDeliverCharge)
                $("#createNewAddressBtn").on("click", function () {
                    let customer_id = $("#customer_id").val();
                    console.log("Update 1")
                    if (customer_id) {
                        $("#newAddress").modal('show');
                    } else {
                        toastr.error("{{__('admin.Please select a customer')}}")
                    }
                })

                $("#customer_id").on("change", function () {
                    updateCustomerID.call(this);
                    updateOrderCount.call(this);
                    updateDeliverCharge.call(this);
                });

                $("#placeOrderBtn").on("click", function (e) {
                    e.preventDefault()
                    let customer_id = $("#order_customer_id").val();
                    if (!customer_id) {
                        toastr.error("{{__('admin.Please select a customer')}}")
                        return;
                    }
                    // append the option delivery type to the form
                    let order_type = $("#order_option").val();
                    if (!order_type) {
                        toastr.error("{{__('admin.Please select order type')}}")
                        return;
                    }
                    $("#order_type").val(order_type);
                    $("#placeOrderForm").submit();
                })


                $("#order_option").on("change", function () {
                    let orderType = $(this).val();
                    if (orderType === "Pickup") {
                        $("#order_delivery_fee").val(0);
                        calculateTotalFee();
                    } else {
                        updateDeliverCharge.call($("#customer_id")[0]);
                    }
                });

                $("#createNewUserForm").on("submit", function (e) {
                    e.preventDefault();


                    $.ajax({
                        type: 'POST',
                        data: $('#createNewUserForm').serialize(),
                        url: "{{ route('admin.create-new-customer') }}",
                        success: function (response) {
                            toastr.success(response.message)
                            // $("#createNewUserForm").trigger("reset");
                            // $("#createNewUser").modal('hide');
                            // $("#customer_id").html(response.customer_html)
                            // reload
                            location.reload();
                        },

                        error: function (response) {
                            if (response.status == 422) {
                                if (response.responseJSON.errors.name) toastr.error(response.responseJSON.errors.name[0])
                                if (response.responseJSON.errors.email) toastr.error(response.responseJSON.errors.email[0])
                                if (response.responseJSON.errors.phone) toastr.error(response.responseJSON.errors.phone[0])
                                if (response.responseJSON.errors.address) toastr.error(response.responseJSON.errors.address[0])
                            }

                            if (response.status == 500) {
                                toastr.error("{{__('admin.Server error occured')}}")
                            }

                            if (response.status == 403) {
                                toastr.error(response.responseJSON.message);
                            }

                        }
                    });

                })

                $("#product_search_form").on("submit", function (e) {
                    e.preventDefault();

                    $("#search_btn_text").html(`{{__('admin.Searching...')}}`)

                    $.ajax({
                        type: 'get',
                        data: $('#product_search_form').serialize(),
                        url: "{{ route('admin.load-products') }}",
                        success: function (response) {
                            $("#search_btn_text").html(`{{__('admin.Search')}}`)
                            $(".product_body").html(response)
                        },
                        error: function (response) {
                            $("#search_btn_text").html(`{{__('admin.Search')}}`)

                            if (response.status == 500) {
                                toastr.error("{{__('admin.Server error occured')}}")
                            }

                            if (response.status == 403) {
                                toastr.error(response.responseJSON.message);
                            }

                        }
                    });
                })

                function fetchPendingOrderCount() {
                    $.ajax({
                        url: '{{ route('admin.pendingOrderCount') }}',
                        type: 'GET',
                        success: function (response) {
                            $('#pendingOrderCount').text(response.count);
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching pending order count:', error);
                        }
                    });
                }

                // Fetch pending order count every 5 seconds
                setInterval(fetchPendingOrderCount, 5000);

                // Initial fetch on page load
                fetchPendingOrderCount();

            });
        })(jQuery);

        function load_product_model(product_id) {

            $.ajax({
                type: 'get',
                url: "{{ url('admin/pos/load-product-modal') }}" + "/" + product_id,
                success: function (response) {
                    $(".load_product_modal_response").html(response)
                    $("#cartModal").modal('show');
                },
                error: function (response) {
                    toastr.error("{{__('user.Server error occured')}}")
                }
            });
        }

        function removeCartItem(rowId) {

            $.ajax({
                type: 'get',
                url: "{{ url('admin/pos/remove-cart-item') }}" + "/" + rowId,
                success: function (response) {
                    $(".shopping-card-body").html(response)

                    calculateTotalFee();
                    toastr.success("{{__('admin.Remove successfully')}}")
                },
                error: function (response) {
                    toastr.error("{{__('user.Server error occured')}}")
                }
            });
        }

        function calculateTotalFee() {
            let order_delivery_fee = $("#order_delivery_fee").val();
            let cart_sub_total = $("#cart_sub_total").val();
            let coupon_price = $("#couon_price").val();
            let couon_offer_type = $("#couon_offer_type").val();

            let apply_coupon_price = 0.00;
            if (couon_offer_type == 1) {
                let percentage = parseInt(coupon_price) / parseInt(100)
                apply_coupon_price = (parseFloat(percentage) * parseFloat(sub_total));
            } else {
                apply_coupon_price = coupon_price;
            }

            let order_total_fee = parseInt(order_delivery_fee) + parseInt(cart_sub_total) - parseInt(coupon_price);
            $("#order_total_fee").val(cart_sub_total);
            $("#coupon_price").val(coupon_price);
            let order_sub_total = $("#order_sub_total").val();

            $("#report_delivery_fee").html(`{{ $currency_icon }}${order_delivery_fee}`);
            $("#report_couon_price").html(`{{ $currency_icon }}${coupon_price}`);
            $("#report_total_fee").html(`{{ $currency_icon }}${order_total_fee}`);
            this.updateTotal();
        }

        function loadProudcts() {
            $.ajax({
                type: 'get',
                url: "{{ route('admin.load-products') }}",
                success: function (response) {
                    $(".product_body").html(response)
                },
                error: function (response) {
                    toastr.error("{{__('user.Server error occured')}}")
                }
            });
        }

        function loadPagination(url) {
            $.ajax({
                type: 'get',
                url: url,
                success: function (response) {
                    $(".product_body").html(response)
                },
                error: function (response) {
                    toastr.error("{{__('user.Server error occured')}}")
                }
            });
        }

        function submitForm() {
            $("#product_search_form").on("submit", function (e) {
                e.preventDefault();

                $("#search_btn_text").html(`{{__('admin.Searching...')}}`);

                $.ajax({
                    type: 'get',
                    data: $('#product_search_form').serialize(),
                    url: "{{ route('admin.load-products') }}",
                    success: function (response) {
                        $("#search_btn_text").html(`{{__('admin.Search')}}`);
                        $(".product_body").html(response);
                    },
                    error: function (response) {
                        $("#search_btn_text").html(`{{__('admin.Search')}}`);

                        if (response.status == 500) {
                            toastr.error("{{__('admin.Server error occured')}}");
                        }

                        if (response.status == 403) {
                            toastr.error(response.responseJSON.message);
                        }
                    }
                });
            });


            $("#product_search_form").submit();
        }

        function updateTotal() {
            let customerInput = $('#customer-input').val();
            let subTotal = parseFloat($('#cart_sub_total').val());
            let discountPercentage = parseFloat($('#discount').val());
            let deliveryFee = parseFloat($('#order_delivery_fee').val());

            // Calculate the actual discount amount
            let discountAmount = (discountPercentage / 100) * subTotal;

            let total = subTotal - discountAmount + deliveryFee;

            $('#report_sub_total').text("{{ $currency_icon }}" + subTotal.toFixed(2));
            $('#report_coupon_price').text("{{ $currency_icon }}" + discountAmount.toFixed(2));
            $('#report_delivery_fee').text("{{ $currency_icon }}" + deliveryFee.toFixed(2));
            $('#report_total_fee').text("{{ $currency_icon }}" + total.toFixed(2));

            $('#order_sub_total').val(subTotal.toFixed(2));
            $('#coupon_price').val(discountAmount.toFixed(2));
            $('#order_delivery_fee').val(deliveryFee.toFixed(2));
            $('#order_total_fee').val(total.toFixed(2));
            $('#customerInput').val(customerInput);
        }

        document.getElementById('paymentAmount').addEventListener('input', function () {
            let paymentAmount = parseFloat(this.value);
            const totalAmountElement = document.getElementById('totalAmount');
            const totalAmount = parseFloat(totalAmountElement.innerText.replace(/[^0-9.-]+/g, ""));

            // Round the paymentAmount to two decimal places
            paymentAmount = paymentAmount.toFixed(2);

            const changeAmount = paymentAmount - totalAmount;
            document.getElementById('changeAmount').value = changeAmount > 0 ? changeAmount.toFixed(2) : 0;
        });

        // Function to bind event listeners for plus and minus buttons
        function bindQuantityButtons() {
            // Unbind any existing event listeners to prevent duplicate bindings
            $(document).off('click', '.plus-btn');
            $(document).off('click', '.minus-btn');

            // Event listener for the plus button
            $(document).on('click', '.plus-btn', function (e) {
                e.preventDefault(); // Prevent default action of the button
                let inputField = $(this).closest('.input-group').find('.pos_input_qty');
                let currentVal = parseInt(inputField.val());
                inputField.val(currentVal + 1).trigger('change'); // Trigger change event after increment
            });

            // Event listener for the minus button
            $(document).on('click', '.minus-btn', function (e) {
                e.preventDefault(); // Prevent default action of the button
                let inputField = $(this).closest('.input-group').find('.pos_input_qty');
                let currentVal = parseInt(inputField.val());
                if (currentVal > 1) {
                    inputField.val(currentVal - 1).trigger('change'); // Trigger change event after decrement
                }
            });
        }

        // Call bindQuantityButtons initially when the document is ready
        $(document).ready(function () {
            bindQuantityButtons();
            // Existing code for quantity change ...
            $(".pos_input_qty").on("change keyup", function (e) {
                let quantity = $(this).val();
                let parent_td = $(this).parents('td');
                let rowid = parent_td.data('rowid');

                $.ajax({
                    type: 'get',
                    data: {rowid, quantity},
                    url: "{{ route('admin.cart-quantity-update') }}",
                    success: function (response) {
                        $(".shopping-card-body").html(response);
                        calculateTotalFee();

                        // After updating the DOM, re-bind event listeners
                        bindQuantityButtons();
                    },
                    error: function (response) {
                        if (response.status == 500) {
                            toastr.error("{{__('admin.Server error occured')}}");
                        }

                        if (response.status == 403) {
                            toastr.error("{{__('admin.Server error occured')}}");
                        }
                    }
                });
            });
        });
        document.getElementById('receiveBtn').addEventListener('click', function () {
            const totalAmount = document.getElementById('report_total_fee').innerText;
            document.getElementById('totalAmount').innerText = totalAmount;
        });

        $('#receiveModal').on('hide.bs.modal', function () {
            // Reset paymentAmount and changeAmount input fields
            $('#paymentAmount').val(0);
            $('#changeAmount').val(0);
        });
    </script>
@endsection
