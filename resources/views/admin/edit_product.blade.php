@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Products')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Edit Product')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Edit Product')}}</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.product.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('admin.Products')}}</a>
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.product.update',$product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">

                                <div class="form-group col-12">
                                    <label>{{__('admin.Thumbnail Image Preview')}}</label>
                                    <div>
                                        <img id="preview-img" class="admin-img" src="{{ asset($product->thumb_image) }}" alt="">
                                    </div>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Thumnail Image')}} <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control-file"  name="thumb_image" onchange="previewThumnailImage(event)">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="name" class="form-control"  name="name" value="{{ $product->name }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Slug')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="slug" class="form-control"  name="slug" value="{{ $product->slug }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Category')}} <span class="text-danger">*</span></label>
                                    <select name="category" class="form-control select2" id="category">
                                        <option value="">{{__('admin.Select Category')}}</option>
                                        @foreach ($categories as $category)
                                            <option {{ $product->category_id == $category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Price')}} <span class="text-danger">*</span></label>
                                   <input type="text" class="form-control" name="price" value="{{ $product->price }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Offer Price')}} </label>
                                   <input type="text" class="form-control" name="offer_price" value="{{ $product->offer_price }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Short Description') }} <span class="text-danger">*</span></label>
                                    <textarea name="short_description" id="" cols="30" rows="10" class="form-control text-area-5">{{ $product->short_description }}</textarea>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Long Description')}} <span class="text-danger">*</span></label>
                                    <textarea name="long_description" id="" cols="30" rows="10" class="summernote">{{ $product->long_description }}</textarea>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Today Special')}} <span class="text-danger">*</span></label>
                                    <select name="today_special" class="form-control">
                                        <option {{ $product->today_special == 1 ? 'selected' : '' }} value="1">{{__('admin.Yes')}}</option>
                                        <option {{ $product->today_special == 0 ? 'selected' : '' }} value="0">{{__('admin.No')}}</option>
                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control">
                                        <option {{ $product->status == 1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                        <option {{ $product->status == 0 ? 'selected' : '' }} value="0">{{__('admin.Inactive')}}</option>
                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.SEO Title')}}</label>
                                   <input type="text" class="form-control" name="seo_title" value="{{ $product->seo_title }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.SEO Description')}}</label>
                                    <textarea name="seo_description" id="" cols="30" rows="10" class="form-control text-area-5">{{ $product->seo_description }}</textarea>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>


<script>
    (function($) {
        "use strict";
        var specification = '{{ $product->is_specification == 1 ? true : false }}';
        $(document).ready(function () {
            $("#name").on("focusout",function(e){
                $("#slug").val(convertToSlug($(this).val()));
            })
        });
    })(jQuery);

    function convertToSlug(Text){
            return Text
                .toLowerCase()
                .replace(/[^\w ]+/g,'')
                .replace(/ +/g,'-');
    }

    function previewThumnailImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('preview-img');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    };

</script>


@endsection
