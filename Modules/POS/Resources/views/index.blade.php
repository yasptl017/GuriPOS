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

/* ── POS top controls row ────────────────────────── */
.pos-top-row {
    display: flex;
    gap: 8px;
    background: #f1f3f5;
    border-radius: 12px;
    padding: 8px 10px;
    margin-bottom: 8px;
}
.pos-btn-group-wrap {
    flex: 1;
}
.pos-btn-group-wrap label.group-label {
    font-size: 10px;
    font-weight: 700;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    margin-bottom: 4px;
    display: block;
}
.pos-btn-group-wrap + .pos-btn-group-wrap {
    border-left: 1px solid #dee2e6;
    padding-left: 8px;
}
.pos-btn-row {
    display: flex;
    gap: 5px;
}
.pos-btn-row .pos-pill { flex: 1; }

.pos-pill {
    padding: 7px 6px 5px;
    border: 2px solid #dee2e6;
    border-radius: 10px;
    background: #fff;
    cursor: pointer;
    text-align: center;
    font-size: 11px;
    font-weight: 700;
    color: #495057;
    transition: border-color .15s, background .15s, color .15s;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 3px;
    line-height: 1.1;
    white-space: nowrap;
}
.pos-pill i { font-size: 17px; }

.pos-pill:hover { border-color: #adb5bd; background: #f8f9fa; }

/* active variants */
.pos-pill.active-dinein  { border-color: #6f42c1; background: #6f42c1; color:#fff; }
.pos-pill.active-pickup  { border-color: #fd7e14; background: #fd7e14; color:#fff; }
.pos-pill.active-delivery{ border-color: #20c997; background: #20c997; color:#fff; }
.pos-pill.active-card    { border-color: #007bff; background: #007bff; color:#fff; }
.pos-pill.active-cash    { border-color: #28a745; background: #28a745; color:#fff; }
.pos-pill.active-unpaid  { border-color: #dc3545; background: #dc3545; color:#fff; }

/* ── Customer button (opens modal) ───────────────── */
.customer-trigger-btn {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border: 2px solid #dee2e6;
    border-radius: 10px;
    background: #fff;
    cursor: pointer;
    text-align: left;
    transition: border-color .15s;
    margin-bottom: 6px;
}
.customer-trigger-btn:hover { border-color: #007bff; }
.customer-trigger-btn .cust-avatar {
    width: 38px; height: 38px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: #6c757d; flex-shrink: 0;
}
.customer-trigger-btn .cust-avatar.selected {
    background: #007bff; color: #fff;
}
.customer-trigger-btn .cust-info { flex: 1; min-width: 0; }
.customer-trigger-btn .cust-name {
    font-size: 14px; font-weight: 600; color: #212529;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.customer-trigger-btn .cust-sub {
    font-size: 11px; color: #6c757d;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.customer-trigger-btn .cust-chevron { color: #adb5bd; font-size: 14px; }

/* ── Customer modal inner ────────────────────────── */
#customerModal .modal-dialog { max-width: 540px; }
.cust-modal-tabs { display: flex; border-bottom: 2px solid #e9ecef; margin-bottom: 16px; }
.cust-modal-tab {
    flex: 1; text-align: center; padding: 10px 0;
    font-size: 13px; font-weight: 600; color: #6c757d;
    cursor: pointer; border-bottom: 3px solid transparent; margin-bottom: -2px;
    transition: color .15s, border-color .15s;
}
.cust-modal-tab.active { color: #007bff; border-bottom-color: #007bff; }
.cust-tab-pane { display: none; }
.cust-tab-pane.active { display: block; }

/* customer search list */
.cust-search-list { max-height: 280px; overflow-y: auto; margin-top: 8px; }
.cust-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px; border-radius: 8px; cursor: pointer;
    transition: background .12s;
}
.cust-item:hover { background: #f0f7ff; }
.cust-item.selected { background: #e8f0fe; }
.cust-item .ci-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: #dee2e6; display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 700; color: #495057; flex-shrink: 0;
    text-transform: uppercase;
}
.cust-item .ci-info .ci-name { font-size: 14px; font-weight: 600; color: #212529; }
.cust-item .ci-info .ci-sub  { font-size: 11px; color: #6c757d; }
.cust-item .ci-check { margin-left: auto; color: #007bff; display: none; }
.cust-item.selected .ci-check { display: block; }

/* selected customer detail card inside modal */
.cust-detail-card {
    background: #f8f9fa; border-radius: 10px; padding: 14px 16px; margin-bottom: 12px;
}
.cust-detail-card .cd-row { display: flex; gap: 8px; margin-bottom: 4px; align-items: flex-start; }
.cust-detail-card .cd-label { font-size: 11px; color: #6c757d; font-weight: 600; min-width: 60px; }
.cust-detail-card .cd-value { font-size: 13px; color: #212529; font-weight: 500; flex: 1; }
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
                            <div class="card-header" style="padding: 10px 14px 6px; display: block;">

                                <!-- Hidden selects for Livewire sync -->
                                <select id="order_option" style="display:none;">
                                    <option value="DineIn" selected id="order_type_dine_in">Dine In</option>
                                    <option value="Pickup">Pick up</option>
                                    <option value="Delivery">Delivery</option>
                                </select>
                                <select id="payment_option" style="display:none;">
                                    <option value="unpaid">Unpaid</option>
                                    <option value="paid" selected id="payment_option_paid">Paid</option>
                                </select>
                                {{-- Hidden customer select for Livewire customer_id sync --}}
                                <select id="customer_id" style="display:none;">
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                                id="{{'customer_' . $customer->id}}"
                                                data-order-count="{{ $customer->orderCount }}"
                                                data-address="{{ $customer->address }}"
                                                data-distance="{{ $customer->address_distance }}"
                                                data-phone="{{ $customer->phone }}"
                                                data-email="{{ $customer->email ?? '' }}"
                                        >
                                            {{ $customer->name }}{{ $customer->phone ? ' - ' . $customer->phone : '' }}
                                        </option>
                                    @endforeach
                                </select>

                                <!-- ORDER TYPE + PAYMENT METHOD in one row/box -->
                                <div class="pos-top-row">
                                    <div class="pos-btn-group-wrap">
                                        <label class="group-label">Order Type</label>
                                        <div class="pos-btn-row">
                                            <button type="button" class="pos-pill active-dinein" id="ot_dinein" onclick="selectOrderType('DineIn')">
                                                <i class="fas fa-utensils"></i><span>Dine In</span>
                                            </button>
                                            <button type="button" class="pos-pill" id="ot_pickup" onclick="selectOrderType('Pickup')">
                                                <i class="fas fa-shopping-bag"></i><span>Pick Up</span>
                                            </button>
                                            <button type="button" class="pos-pill" id="ot_delivery" onclick="selectOrderType('Delivery')">
                                                <i class="fas fa-motorcycle"></i><span>Delivery</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="pos-btn-group-wrap">
                                        <label class="group-label">Payment</label>
                                        <div class="pos-btn-row">
                                            <button type="button" class="pos-pill active-card" id="pm_card" onclick="selectPaymentMethod('card')">
                                                <i class="fas fa-credit-card"></i><span>Card</span>
                                            </button>
                                            <button type="button" class="pos-pill" id="pm_cash" onclick="selectPaymentMethod('cash')">
                                                <i class="fas fa-money-bill-wave"></i><span>Cash</span>
                                            </button>
                                            <button type="button" class="pos-pill" id="pm_unpaid" onclick="selectPaymentMethod('unpaid')">
                                                <i class="fas fa-clock"></i><span>Unpaid</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- CUSTOMER trigger button -->
                                <button type="button" class="customer-trigger-btn" id="customerTriggerBtn" data-toggle="modal" data-target="#customerModal">
                                    <div class="cust-avatar" id="custAvatarIcon"><i class="fas fa-user"></i></div>
                                    <div class="cust-info">
                                        <div class="cust-name" id="custDisplayName">Select Customer</div>
                                        <div class="cust-sub" id="custDisplaySub">Tap to search or add new</div>
                                    </div>
                                    <i class="fas fa-chevron-right cust-chevron"></i>
                                </button>

                                <!-- DISCOUNT inline row -->
                                <div style="display:flex;align-items:center;gap:8px;background:#f1f3f5;border-radius:10px;padding:7px 12px;">
                                    <label for="discount" style="font-size:11px;font-weight:700;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;margin:0;white-space:nowrap;">
                                        <i class="fas fa-tag text-warning mr-1"></i>{{__('admin.Discount')}} (%)
                                    </label>
                                    <input type="number" id="discount" class="form-control form-control-sm"
                                           value="{{ env('DEFAULTS_DISCOUNT') }}" oninput="updateTotal()"
                                           style="width:80px;border-radius:7px;border:2px solid #ced4da;">
                                </div>

                            </div>
                            <div class="card-body" style="padding-top: 8px;">
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
                                    <textarea id="customer-input" name="_customer_details_display" style="display:none;"></textarea>
                                    <input type="hidden" name="customer_id" id="order_customer_id">
                                    <input type="hidden" name="address_id" id="order_address_id">
                                    <input type="hidden" value="0.00" name="coupon_price" id="coupon_price">
                                    <input type="hidden" value="0.00" name="delivery_fee" id="order_delivery_fee">
                                    <input type="hidden" value="{{ $sub_total }}" name="total_fee" id="order_total_fee">
                                    <input type="hidden" value="card" name="payment_method" id="order_payment_method">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Customer Modal (search + details + new customer) -->
    <div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="padding: 14px 18px 10px;">
                    <h5 class="modal-title"><i class="fas fa-user-circle text-primary mr-2"></i>Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 14px 18px;">

                    <!-- Tabs -->
                    <div class="cust-modal-tabs">
                        <div class="cust-modal-tab active" id="tabSearchBtn" onclick="switchCustTab('search')">
                            <i class="fas fa-search mr-1"></i> Find Customer
                        </div>
                        <div class="cust-modal-tab" id="tabNewBtn" onclick="switchCustTab('new')">
                            <i class="fas fa-user-plus mr-1"></i> New Customer
                        </div>
                    </div>

                    <!-- SEARCH TAB -->
                    <div class="cust-tab-pane active" id="custTabSearch">
                        <!-- selected customer detail card -->
                        <div class="cust-detail-card" id="custDetailCard" style="display:none;">
                            <div class="cd-row">
                                <span class="cd-label"><i class="fas fa-user fa-fw text-primary"></i></span>
                                <span class="cd-value" id="cd_name"></span>
                            </div>
                            <div class="cd-row">
                                <span class="cd-label"><i class="fas fa-phone fa-fw text-success"></i></span>
                                <span class="cd-value" id="cd_phone"></span>
                            </div>
                            <div class="cd-row" id="cd_email_row">
                                <span class="cd-label"><i class="fas fa-envelope fa-fw text-warning"></i></span>
                                <span class="cd-value" id="cd_email"></span>
                            </div>
                            <div class="cd-row" id="cd_address_row">
                                <span class="cd-label"><i class="fas fa-map-marker-alt fa-fw text-danger"></i></span>
                                <span class="cd-value" id="cd_address"></span>
                            </div>
                        </div>

                        <!-- Search input -->
                        <div style="position:relative; margin-bottom:6px;">
                            <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#adb5bd;pointer-events:none;"></i>
                            <input type="text" id="custSearchInput" class="form-control" placeholder="Search by name or phone…"
                                   style="padding-left:36px; border-radius:8px;" oninput="filterCustomers()">
                        </div>

                        <!-- Customer list -->
                        <div class="cust-search-list" id="custSearchList">
                            @foreach ($customers as $customer)
                                <div class="cust-item"
                                     data-id="{{ $customer->id }}"
                                     data-name="{{ $customer->name }}"
                                     data-phone="{{ $customer->phone ?? '' }}"
                                     data-email="{{ $customer->email ?? '' }}"
                                     data-address="{{ $customer->address ?? '' }}"
                                     data-distance="{{ $customer->address_distance ?? 0 }}"
                                     data-ordercount="{{ $customer->orderCount }}"
                                     onclick="selectCustomerFromModal(this)">
                                    <div class="ci-avatar">{{ strtoupper(substr($customer->name, 0, 1)) }}</div>
                                    <div class="ci-info">
                                        <div class="ci-name">{{ $customer->name }}</div>
                                        <div class="ci-sub">{{ $customer->phone ?? 'No phone' }}{{ $customer->address ? ' · '.Str::limit($customer->address, 30) : '' }}</div>
                                    </div>
                                    <i class="fas fa-check-circle ci-check"></i>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- NEW CUSTOMER TAB -->
                    <div class="cust-tab-pane" id="custTabNew">
                        <form id="createNewUserForm" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>{{__('admin.Name')}} <span class="text-danger">*</span></label>
                                <input type="text" name="name" autocomplete="off" class="form-control" placeholder="Full name">
                            </div>
                            <div class="form-group">
                                <label>{{__('admin.Phone')}} <span class="text-danger">*</span></label>
                                <input type="text" name="phone" autocomplete="off" class="form-control" placeholder="Phone number">
                            </div>
                            <div class="form-group">
                                <label>{{__('admin.Email')}}</label>
                                <input type="email" name="email" value="no@email.com" autocomplete="off" class="form-control" placeholder="Email (optional)">
                            </div>
                            <x-address-input>
                                <div class="form-group">
                                    <label>{{__('admin.Address')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="address" id="address-input" class="form-control" placeholder="Start typing address…">
                                </div>
                            </x-address-input>
                            <button class="btn btn-primary btn-block" type="submit">
                                <i class="fas fa-user-plus mr-1"></i> {{__('Save Customer')}}
                            </button>
                        </form>
                    </div>

                </div>
                <div class="modal-footer" style="padding: 10px 18px;">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="confirmCustomerBtn" onclick="confirmCustomerSelection()" style="display:none;">
                        <i class="fas fa-check mr-1"></i> Confirm
                    </button>
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

        /* ── Order Type ─────────────────────────────── */
        function selectOrderType(type) {
            $('#ot_dinein').removeClass('active-dinein');
            $('#ot_pickup').removeClass('active-pickup');
            $('#ot_delivery').removeClass('active-delivery');

            if (type === 'DineIn') {
                $('#ot_dinein').addClass('active-dinein');
            } else if (type === 'Pickup') {
                $('#ot_pickup').addClass('active-pickup');
            } else if (type === 'Delivery') {
                $('#ot_delivery').addClass('active-delivery');
            }
            // sync hidden select for Livewire
            $('#order_option').val(type).trigger('change');
        }

        /* ── Payment Method ──────────────────────────── */
        function selectPaymentMethod(method) {
            $('#pm_card').removeClass('active-card');
            $('#pm_cash').removeClass('active-cash');
            $('#pm_unpaid').removeClass('active-unpaid');

            if (method === 'card') {
                $('#pm_card').addClass('active-card');
                $('#order_payment_method').val('card');
                $('#payment_option').val('paid').trigger('change');
            } else if (method === 'cash') {
                $('#pm_cash').addClass('active-cash');
                $('#order_payment_method').val('cash');
                $('#payment_option').val('paid').trigger('change');
            } else if (method === 'unpaid') {
                $('#pm_unpaid').addClass('active-unpaid');
                $('#order_payment_method').val('unpaid');
                $('#payment_option').val('unpaid').trigger('change');
            }
            document.dispatchEvent(new CustomEvent('payment-method-changed', { detail: method }));
        }

        /* ── Customer Modal ──────────────────────────── */
        var _selectedCustomerId = null;

        function switchCustTab(tab) {
            if (tab === 'search') {
                $('#tabSearchBtn').addClass('active');
                $('#tabNewBtn').removeClass('active');
                $('#custTabSearch').addClass('active');
                $('#custTabNew').removeClass('active');
            } else {
                $('#tabNewBtn').addClass('active');
                $('#tabSearchBtn').removeClass('active');
                $('#custTabNew').addClass('active');
                $('#custTabSearch').removeClass('active');
            }
        }

        function filterCustomers() {
            var q = $('#custSearchInput').val().toLowerCase();
            $('#custSearchList .cust-item').each(function () {
                var name  = $(this).data('name').toLowerCase();
                var phone = String($(this).data('phone')).toLowerCase();
                $(this).toggle(name.includes(q) || phone.includes(q));
            });
        }

        function selectCustomerFromModal(el) {
            var $el = $(el);
            // toggle selection
            $('.cust-item').removeClass('selected');
            $el.addClass('selected');

            _selectedCustomerId = $el.data('id');
            var name    = $el.data('name');
            var phone   = $el.data('phone') || '';
            var email   = $el.data('email') || '';
            var address = $el.data('address') || '';

            // fill detail card
            $('#cd_name').text(name);
            $('#cd_phone').text(phone || '—');
            if (email && email !== 'no@email.com') {
                $('#cd_email').text(email);
                $('#cd_email_row').show();
            } else {
                $('#cd_email_row').hide();
            }
            if (address) {
                $('#cd_address').text(address);
                $('#cd_address_row').show();
            } else {
                $('#cd_address_row').hide();
            }
            $('#custDetailCard').show();
            $('#confirmCustomerBtn').show();
        }

        function confirmCustomerSelection() {
            if (!_selectedCustomerId) return;

            var $opt = $('#customer_id option[value="' + _selectedCustomerId + '"]');
            var name    = $opt.data ? $opt.text().split(' - ')[0] : '';
            var phone   = $opt.attr('data-phone') || '';
            var email   = $opt.attr('data-email') || '';
            var address = $opt.attr('data-address') || '';

            // update hidden select + trigger Livewire
            $('#customer_id').val(_selectedCustomerId).trigger('change');

            // update trigger button
            $('#custAvatarIcon').addClass('selected');
            $('#custDisplayName').text($('#cd_name').text());
            var sub = phone || '';
            if (address) sub += (sub ? ' · ' : '') + address.substring(0, 28) + (address.length > 28 ? '…' : '');
            $('#custDisplaySub').text(sub || 'Customer selected');

            // update customer details for form submission
            var details = 'Name: ' + $('#cd_name').text() + '\n';
            if (phone)   details += 'Phone: ' + phone + '\n';
            if (address) details += 'Address: ' + address;
            $('#customer-input').val(details);
            $('#customerInput').val(details);
            updateTotal();

            $('#customerModal').modal('hide');
        }

        // Reset modal state when closed
        $('#customerModal').on('hidden.bs.modal', function () {
            $('#custSearchInput').val('');
            filterCustomers();
        });

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
