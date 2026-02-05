<form id="modal_add_to_cart_form" method="POST">
    @csrf

    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <input type="hidden" name="price" value="{{ $product->price }}" id="modal_price">
    <input type="hidden" name="variant_price" value="{{ $size_variants[0]->price ?? $product->price }}" id="modal_variant_price">

    <div class="tf__cart_popup_text">
        <a href="#" class="title">{{ $product->name }}</a>

        @if ($product->is_offer)
            <h3 class="price">{{ $currency_icon }}{{ number_format($product->offer_price, 2) }} <del>{{ $currency_icon }}{{ number_format($product->price, 2) }}</del></h3>
        @else
            <h3 class="price">{{ $currency_icon }}{{ number_format($product->price, 2) }} </h3>
        @endif

        <div class="details_size">
            @foreach ($size_variants as $index => $size_variant)
                <div class="form-check">
                    <input name="size_variant" class="form-check-input" type="radio" id="large-{{ $index }}" value="{{ $size_variant->size }}(::){{ $size_variant->price }}" data-variant-price="{{ $size_variant->price }}" data-variant-size="{{ $size_variant->size }}" {{ $index === 0 ? 'checked' : '' }}>
                    <label class="form-check-label" for="large-{{ $index }}">
                        {{ $size_variant->size }} <span>- {{ $currency_icon }}{{ number_format($size_variant->price, 2) }}</span>
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
                        {{ $optional_item->item }} <span>+ {{ $currency_icon }}{{ number_format($optional_item->price, 2) }}</span>
                    </label>
                </div>
            @endforeach
        </div>
        @endif

        <div class="details_quentity">
            <h5>{{__('user.select quantity')}}</h5>
            <div class="quentity_btn_area d-flex flex-wrapa align-items-center">
                <div class="quentity_btn">
                    <button type="button" class="btn btn-danger modal_decrement_qty_detail_page"><i class="fal fa-minus"></i></button>
                    <input type="text" value="1" name="qty" class="modal_product_qty" readonly>
                    <button type="button" class="btn btn-success modal_increment_qty_detail_page"><i class="fal fa-plus"></i></button>
                </div>
                <h3>{{ $currency_icon }} <span class="modal_grand_total">{{ number_format($size_variants[0]->price ?? $product->price, 2) }}</span></h3>
            </div>
        </div>
        <ul class="details_button_area d-flex flex-wrap">
            <li><a id="modal_add_to_cart" class="common_btn" href="javascript:;">{{__('user.add to cart')}}</a></li>
        </ul>
    </div>
</form>

<script>
(function($) {
    "use strict";
    $(document).ready(function () {
        let initialVariantPrice = $("input[name='size_variant']:checked").data('variant-price') || {{ $product->price }};
        let initialPrice = parseFloat(initialVariantPrice).toFixed(2);
        $(".modal_grand_total").html(initialPrice);
        $("#modal_variant_price").val(initialVariantPrice);
        $("#modal_price").val(initialPrice);

        $("#modal_add_to_cart").on("click", function(e){
            e.preventDefault();
            if ($("input[name='size_variant']").is(":checked") || $("input[name='size_variant']").length == 0) {
                $.ajax({
                    type: 'get',
                    data: $('#modal_add_to_cart_form').serialize(),
                    url: "{{ url('/add-to-cart') }}",
                    success: function (response) {
                        console.log(response);
                        let html_response = `<div>
                            <div class="wsus__menu_cart_header">
                                <h5 class="mini_cart_body_item">{{__('user.Total Item')}}(0)</h5>
                                <span class="close_cart"><i class="fal fa-times"></i></span>
                            </div>
                            <ul class="mini_cart_list"></ul>
                            <p class="subtotal">{{__('user.Sub Total')}} <span class="mini_sub_total">{{ $currency_icon }}0.00</span></p>
                            <div class="btn_area">
                                <a href="{{ route('cart') }}" class="common_btn">{{__('user.view cart')}}</a>
                                <a href="{{ route('checkout') }}" class="common_btn">{{__('user.checkout')}}</a>
                            </div>
                        </div>`;
                        $('#header_cart_body').html(html_response);
                        toastr.success(response.message);
                       location.reload();
                    },
                    error: function(xhs, status, error){
                        console.log(xhs.responseText);
                    }
                })
            } else {
                toastr.error("{{__('user.Please Select variant')}}")
            }
        });

        $(".form-check-input").on("change", function() {
            recalculateTotal();
        });

        $(".check_optional_item").on("change", function() {
            recalculateTotal();
        });

        $(".modal_increment_qty_detail_page").on("click", function() {
            let quantity = parseInt($(".modal_product_qty").val());
            $(".modal_product_qty").val(quantity + 1);
            recalculateTotal();
        });

        $(".modal_decrement_qty_detail_page").on("click", function() {
            let quantity = parseInt($(".modal_product_qty").val());
            if (quantity > 1) {
                $(".modal_product_qty").val(quantity - 1);
                recalculateTotal();
            }
        });

        function recalculateTotal() {
            let variantPrice = parseFloat($("input[name='size_variant']:checked").data("variant-price")) || {{ $product->price }};
            let optionalItemPrice = 0;
            $(".check_optional_item:checked").each(function() {
                optionalItemPrice += parseFloat($(this).data("optional-item"));
            });

            let quantity = parseInt($(".modal_product_qty").val());
            let totalPrice = ((variantPrice + optionalItemPrice) * quantity).toFixed(2);
            $(".modal_grand_total").html(totalPrice);
            $("#modal_variant_price").val(variantPrice.toFixed(2));
            $("#modal_price").val(totalPrice);
        }
    });
})(jQuery);
 
</script>
