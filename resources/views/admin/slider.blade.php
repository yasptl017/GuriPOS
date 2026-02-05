@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Intro')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Intro')}}</h1>
          </div>
          <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.update-slider-image') }}" method="POST" enctype="multipart/form-data">
                          @csrf

                          <div class="form-group">
                            <label for="">{{__('admin.Intro Background')}}</label>
                            <div>
                              <img src="{{ asset($setting->slider_background) }}" alt="" class="w_300">
                            </div>
                          </div>

                          <div class="form-group">
                            <label for="">{{__('admin.New Background')}}</label>
                            <input type="file" name="background_image" class="form-control-file">
                          </div>

                          <div class="form-group">
                            <label for="">{{__('admin.Offer Image')}}</label>
                            <div>
                              <img src="{{ asset($setting->slider_offer_image) }}" alt="" class="category_image ">
                            </div>
                          </div>

                          <div class="form-group">
                            <label for="">{{__('admin.New Image')}}</label>
                            <input type="file" name="slider_offer_image" class="form-control-file">
                          </div>

                          <div class="form-group">
                            <label for="">{{__('admin.Header one')}}</label>
                            <input type="text" name="slider_header_one" class="form-control" value="{{ $setting->slider_header_one }}">
                          </div>

                          <div class="form-group">
                            <label for="">{{__('admin.Header two')}}</label>
                            <input type="text" name="slider_header_two" class="form-control" value="{{ $setting->slider_header_two }}">
                          </div>

                          <div class="form-group">
                            <label for="">{{__('admin.Description')}}</label>
                            <input type="text" name="slider_description" class="form-control" value="{{ $setting->slider_description }}">
                          </div>

                          <div class="form-group">
                            <label for="">{{__('admin.Offer text')}}</label>
                            <input type="text" name="slider_offer_text" class="form-control" value="{{ $setting->slider_offer_text }}">
                          </div>

                          <button type="submit" class="btn btn-success">{{__('admin.Update')}}</button>
                        </form>
                    </div>
                  </div>
                </div>
            </div>
          </div>


        </section>
      </div>


@endsection
