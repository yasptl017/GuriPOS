<div class="row">
    @foreach ($products as $product_index => $product)
        <div class="col-md-4">
            <div class="card produt_card" onclick="load_product_model({{ $product->id }})">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    @if ($product->is_offer)
                        <h6 class="price">{{ $currency_icon }}{{ $product->offer_price }} </h6>
                    @else
                        <h6 class="price">{{ $currency_icon }}{{ $product->price }}</h6>
                    @endif

                </div>
            </div>
        </div>
    @endforeach

</div>

{{ $products->links('pos::ajax_pagination') }}


