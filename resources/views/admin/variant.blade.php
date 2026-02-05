@extends('admin.master_layout')
@section('title')
<title>{{ $product->name }}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{ $product->name }}</h1>
          </div>
          <div class="section-body">
            <a href="{{ route('admin.product.index') }}" class="btn btn-primary"><i class="fas fa-backward"></i> {{__('admin.Go Back')}}</a>
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                      <div class="card-header">
                          <h1>{{__('admin.Product Size')}}</h1>
                      </div>
                        <div class="card-body">
                            <form action="{{ route('admin.store-product-variant', $product->id) }}" method="POST">
                                @csrf
                                <div id="size_box">
                                    @foreach ($size_variant as $index => $size)
                                        <div class="row size_box_hidden_area">

                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="">{{__('admin.Size')}}</label>
                                                    <input type="text" name="sizes[]" class="form-control" value="{{ $size->size }}">
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="">{{__('admin.Price')}}</label>
                                                    <input type="text" name="prices[]" class="form-control" value="{{ $size->price }}">
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <button type="button" class="btn btn-danger plus_btn remove_size_box"> <i class="fa fa-trash" aria-hidden="true"></i> {{__('admin.Remove')}}</button>
                                            </div>

                                        </div>
                                    @endforeach
                                    <div class="row">

                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="">{{__('admin.Size')}}</label>
                                                <input type="text" name="sizes[]" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="">{{__('admin.Price')}}</label>
                                                <input type="text" name="prices[]" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <button type="button" id="addNewSize" class="btn btn-success plus_btn"> <i class="fa fa-plus" aria-hidden="true"></i> {{__('admin.Add New')}}</button>
                                        </div>

                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">{{__('admin.Save')}}</button>
                            </form>
                        </div>
                  </div>
                </div>
            </div>


            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                      <div class="card-header">
                          <h1>{{__('admin.Optional Item')}}</h1>
                      </div>
                        <div class="card-body">
                            <form action="{{ route('admin.store-optional-item', $product->id) }}" method="POST">
                                @csrf
                                <div id="optional_box">
                                    @foreach ($optional_item as $index => $item)
                                        <div class="row optional_box_hidden_area">

                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="">{{__('admin.Item Name')}}</label>
                                                    <input type="text" name="item_names[]" class="form-control" value="{{ $item->item }}">
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="">{{__('admin.Price')}}</label>
                                                    <input type="text" name="item_prices[]" class="form-control" value="{{ $item->price }}">
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <button type="button" class="btn btn-danger plus_btn remove_optional_box"> <i class="fa fa-trash" aria-hidden="true"></i> {{__('admin.Remove')}}</button>
                                            </div>

                                        </div>
                                    @endforeach
                                    <div class="row">

                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="">{{__('admin.Item Name')}}</label>
                                                <input type="text" name="item_names[]" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="">{{__('admin.Price')}}</label>
                                                <input type="text" name="item_prices[]" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <button type="button" id="addNewOptionalItem" class="btn btn-success plus_btn"> <i class="fa fa-plus" aria-hidden="true"></i> {{__('admin.Add New')}}</button>
                                        </div>

                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">{{__('admin.Save')}}</button>
                            </form>
                        </div>
                  </div>
                </div>
            </div>
          </div>

        </section>
      </div>



      <div id="hidden_size_box" class="d-none">
        <div class="row size_box_hidden_area">

            <div class="col-4">
                <div class="form-group">
                    <label for="">{{__('admin.Size')}}</label>
                    <input type="text" name="sizes[]" class="form-control">
                </div>
            </div>

            <div class="col-4">
                <div class="form-group">
                    <label for="">{{__('admin.Price')}}</label>
                    <input type="text" name="prices[]" class="form-control">
                </div>
            </div>

            <div class="col-4">
                <button type="button" class="btn btn-danger plus_btn remove_size_box"> <i class="fa fa-trash" aria-hidden="true"></i> {{__('admin.Remove')}}</button>
            </div>

        </div>
      </div>

      <div id="hidden_optional_box" class="d-none">
        <div class="row optional_box_hidden_area">

            <div class="col-4">
                <div class="form-group">
                    <label for="">{{__('admin.Item Name')}}</label>
                    <input type="text" name="item_names[]" class="form-control">
                </div>
            </div>

            <div class="col-4">
                <div class="form-group">
                    <label for="">{{__('admin.Price')}}</label>
                    <input type="text" name="item_prices[]" class="form-control">
                </div>
            </div>

            <div class="col-4">
                <button type="button" class="btn btn-danger plus_btn remove_optional_box"> <i class="fa fa-trash" aria-hidden="true"></i> {{__('admin.Remove')}}</button>
            </div>

        </div>
      </div>

      <script>
        (function($) {
            "use strict";
            $(document).ready(function () {

                $("#addNewSize").on('click',function(){
                    var html = $("#hidden_size_box").html();
                    $("#size_box").append(html);
                })

                $(document).on('click', '.remove_size_box', function () {
                    $(this).closest('.size_box_hidden_area').remove();
                });

                $("#addNewOptionalItem").on('click',function(){
                    var html = $("#hidden_optional_box").html();
                    $("#optional_box").append(html);
                })

                $(document).on('click', '.remove_optional_box', function () {
                    $(this).closest('.optional_box_hidden_area').remove();
                });

            });
        })(jQuery);

    </script>
@endsection
