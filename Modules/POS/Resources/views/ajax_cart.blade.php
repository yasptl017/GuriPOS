<table class="table">
    <thead>
    <th>{{__('admin.Item')}}</th>
    <th>{{__('admin.Qty')}}</th>
    <th>{{__('admin.Price')}}</th>
    <th>{{__('admin.Action')}}</th>
    </thead>
    <tbody>
    @php
        $sub_total = 0;
        $coupon_price = 0.00;
    @endphp
    @foreach ($cart_contents as $cart_index => $cart_content)
        <tr>
            <td>
                <p>
                    {{ $cart_content['name'] }}
                    @if (!empty($cart_content['options']['size']) || strtolower($cart_content['options']['size']) !== 'regular')
                        ({{ $cart_content['options']['size'] }})
                    @endif
                </p>

            </td>
            <td data-rowid="{{ $cart_content['id'] }}">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button class="btn btn-danger btn-sm qty-btn minus-btn">-</button>
                    </div>
                    <input min="1" type="number" value="{{ $cart_content['qty'] }}" class="form-control pos_input_qty">
                    <div class="input-group-append">
                        <button class="btn btn-primary btn-sm qty-btn plus-btn">+</button>
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
                <a href="javascript:" onclick="removeCartItem('{{ $cart_content['id'] }}')"><i class="fa fa-trash"
                                                                                               aria-hidden="true"></i></a>
            </td>
        </tr>
    @endforeach

    </tbody>
</table>

<div>
    <p><span>{{__('admin.Subtotal')}}</span> : <span>{{ $currency_icon }}{{ $sub_total }}</span></p>
    <p><span>{{__('Discount (-)')}}</span> : <span id="report_coupon_price">{{ $currency_icon }}0.00</span></p>
    <p><span>{{__('admin.Delivery')}}</span> : <span id="report_delivery_fee">{{ $currency_icon }}0.00</span></p>
    <p><span>{{__('admin.Total')}}</span> : <span id="report_total_fee">{{ $currency_icon }}{{ $sub_total }}</span></p>
</div>

<input type="hidden" id="cart_sub_total" value="{{ $sub_total }}">


<script>
    (function ($) {
        "use strict";
        $(document).ready(function () {
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
        });
    })(jQuery);

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
</script>

