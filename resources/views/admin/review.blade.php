@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Contact Message')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Review</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">REVIEW</div>
            </div>
          </div>

        <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                <div>
                        <h1 style="font-size: 2rem; text-align: center;">Most Ordered Food</h1>
                        <div class="toporder">
                            <div class="row">

                                @foreach ($mostOrderedProducts as $product)
                                    <div class="col-md-4">
                                        <div class="card produt_card" onclick="load_product_model({{ $product->id }})">
                                            <div class="w-100">
                                                <img src="{{ asset($product->thumb_image) }}" class="card-img-top" alt="Product">
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $product->name }}</h5>
                                                @if ($product->is_offer)
                                                    <h6 class="price">{{ $currency_icon }}{{ $product->offer_price }}</h6>
                                                @else
                                                    <h6 class="price">{{ $currency_icon }}{{ $product->price }}</h6>
                                                    
                                                @endif
                                                <h6 class="price">Count : {{ $product->count }}</h6>
                                            </div>
                                            
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <br>

                    <h1 style="font-size: 2rem; text-align: center;">Least Ordered Food</h1>
                        <div class="toporder">
                            <div class="row">
                                @foreach ($leastOrderedProducts as $product)
                                    <div class="col-md-4">
                                        <div class="card produt_card" onclick="load_product_model({{ $product->id }})">
                                            <div class="w-100">
                                                <img src="{{ asset($product->thumb_image) }}" class="card-img-top" alt="Product">
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $product->name }}</h5>
                                                @if ($product->is_offer)
                                                    <h6 class="price">{{ $currency_icon }}{{ $product->offer_price }}</h6>
                                                @else
                                                    <h6 class="price">{{ $currency_icon }}{{ $product->price }}</h6>
                                                @endif
                                                <h6 class="price">Count : {{ $product->count }}</h6>
                                            </div>
                                        </div>
                                        
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
          </div>
        </section>
      </div>

      <script>
          
      </script>
@endsection
