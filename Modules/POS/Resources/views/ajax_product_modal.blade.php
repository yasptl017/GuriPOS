    <style>
        .size-variant-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .size-variant-buttons .form-check {
            flex: 1 1 calc(33.33% - 10px);
        }

        .size-variant-button {
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }

        .size-variant-button input[type="radio"] {
            display: none;
        }

        .size-variant-button:hover,
        .size-variant-button input[type="radio"]:checked + label {
            border-color: #007bff;
        }

        .size-variant-button label {
            margin: 0;
            width: 100%;
            text-align: center;
        }

        .size-variant-button span {
            display: block;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <form id="modal_add_to_cart_form" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" name="price" value="{{ $product->price }}" id="modal_price">
        <input type="hidden" name="variant_price" value="{{ $product->price }}" id="modal_variant_price">
        <div class="wsus__cart_popup_text">
            <a href="#" class="title" style="font-size: 20px !important;">{{ $product->name }}</a>
            <div class="details_size">
                <strong>{{ __('admin.Select Size') }}</strong>
                <div class="size-variant-buttons">
                    @if(count($size_variants) > 0)
                        @foreach ($size_variants as $index => $size_variant)
                            <div class="form-check">
                                <input name="size_variant" class="form-check-input" type="radio" id="large-{{ $index }}" value="{{ $size_variant->size }}(::){{ $size_variant->price }}" data-variant-price="{{ $size_variant->price }}" data-variant-size="{{ $size_variant->size }}" @if($index == 0) checked @endif>
                                <label class="size-variant-button" for="large-{{ $index }}">
                                    <span>{{ $size_variant->size }}</span>
                                    <span>{{ $size_variant->price }}</span>
                                </label>
                            </div>
                        @endforeach
                    @else
                        <div class="form-check">
                            <input name="size_variant" class="form-check-input" type="radio" id="default-size" value="Regular(::){{ $product->price }}" data-variant-price="{{ $product->price }}" data-variant-size="Regular" checked>
                            <label class="size-variant-button" for="default-size">
                                <span>Regular</span>
                                <span>{{ $product->price }}</span>
                            </label>
                        </div>
                    @endif
                </div>
            </div>

            @if (count($optional_items) > 0)
                <div class="details_extra_item">
                    <h5>{{ __('admin.Select Addon') }} <span>({{ __('admin.optional') }})</span></h5>
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
                <h5>{{ __('admin.Select Quantity') }}</h5>
                <div class="quentity_btn_area d-flex flex-wrap align-items-center">
                    <div class="quentity_btn">
                        <button type="button" class="btn btn-danger modal_decrement_qty_detail_page"><i class="fa fa-minus"></i></button>
                        <input type="text" value="1" name="qty" class="modal_product_qty" readonly>
                        <button type="button" class="btn btn-success modal_increment_qty_detail_page"><i class="fa fa-plus"></i></button>
                    </div>
                    <h3>{{ $currency_icon }} <span class="modal_grand_total">{{ number_format($product->price, 2) }}</span></h3>
                </div>
            </div>
            <ul class="details_button_area d-flex flex-wrap">
                <li><a id="modal_add_to_cart" class="btn btn-primary" href="javascript:;">{{ __('admin.Add now') }}</a></li>
            </ul>
        </div>
    </form>

    <script>
        $(document).ready(function () {
            // Set initial values based on the first variant or default size
            const initialVariantPrice = $("input[name='size_variant']:checked").data('variant-price') || parseFloat("{{ $product->price }}");
            $("#modal_variant_price").val(initialVariantPrice);
            calculateModalPrice();

            $("#modal_add_to_cart").on("click", function(e){
                e.preventDefault();
                if ($("input[name='size_variant']").is(":checked")) {
                    $.ajax({
                        type: 'get',
                        data: $('#modal_add_to_cart_form').serialize(),
                        url: "{{ url('/admin/pos/add-to-cart') }}",
                        success: function (response) {
                            $(".shopping-card-body").html(response)
                            toastr.success("{{ __('admin.Item added successfully') }}")
                            calculateTotalFee();
                            $("#cartModal").modal('hide');
                        },
                        error: function(response) {
                            if(response.status == 500){
                                toastr.error("{{ __('admin.Server error occured') }}")
                            }

                            if(response.status == 403){
                                toastr.error(response.responseJSON.message)
                            }
                        }
                    });
                } else {
                    toastr.error("{{ __('admin.Please select a size') }}")
                }
            });

            $("input[name='size_variant']").on("change", function(){
                $("#modal_variant_price").val($(this).data('variant-price') || parseFloat("{{ $product->price }}"));
                calculateModalPrice();
            });

            $("input[name='optional_items[]']").change(function() {
                calculateModalPrice();
            });

            $(".modal_increment_qty_detail_page").on("click", function(){
                let product_qty = $(".modal_product_qty").val();
                let new_qty = parseInt(product_qty) + 1;
                $(".modal_product_qty").val(new_qty);
                calculateModalPrice();
            });

            $(".modal_decrement_qty_detail_page").on("click", function(){
                let product_qty = $(".modal_product_qty").val();
                if(product_qty == 1) return;
                let new_qty = parseInt(product_qty) - 1;
                $(".modal_product_qty").val(new_qty);
                calculateModalPrice();
            });

            function calculateModalPrice(){
                let optional_price = 0;
                let product_qty = $(".modal_product_qty").val();
                $("input[name='optional_items[]']:checked").each(function() {
                    let checked_value = $(this).data('optional-item');
                    optional_price += parseFloat(checked_value);
                });

                let variant_price = parseFloat($("#modal_variant_price").val()) || parseFloat("{{ $product->price }}");
                let main_price = variant_price * parseInt(product_qty);

                let total = main_price + optional_price;
                let formattedTotal = total.toFixed(2);

                $(".modal_grand_total").html(formattedTotal);
                $("#modal_price").val(formattedTotal);
            }
        });
    </script>
