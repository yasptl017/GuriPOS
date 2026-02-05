@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Footer')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Footer')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Footer')}}</div>
            </div>
          </div>

          <div class="section-body">
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.footer.update', $footer->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">

                                <div class="form-group col-12">
                                    <label>{{__('admin.Background Image One')}}</label>
                                    <div>
                                        <img src="{{ asset($footer->footer_background) }}" alt="" class="w_300">
                                    </div>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.New Image')}}</label>
                                    <input type="file" name="footer_background" class="form-control-file" >
                                </div>

                                <div class="form-group col-12 d-none">
                                    <label>{{__('admin.Background Image Two')}}</label>
                                    <div>
                                        <img src="{{ asset($footer->footer_background_2) }}" alt="" class="w_300">
                                    </div>
                                </div>

                                <div class="form-group col-12 d-none">
                                    <label>{{__('admin.New Image')}}</label>
                                    <input type="file" name="footer_background_2" class="form-control-file" >
                                </div>



                                <div class="form-group col-12">
                                    <label>{{__('admin.About Us')}} <span class="text-danger">*</span></label>
                                    <textarea name="about_us" id="" cols="30" rows="10" class="form-control text-area-5">{{ $footer->about_us }}</textarea>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Email')}} <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="{{ $footer->email }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Phone')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control" value="{{ $footer->phone }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Address')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="address" class="form-control" value="{{ $footer->address }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Copyright')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="copyright" class="form-control" value="{{ $footer->copyright }}">
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
@endsection
