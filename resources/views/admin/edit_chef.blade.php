@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Edit Chef')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Edit Chef')}}</h1>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.our-chef.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('admin.Our Chef')}}</a>
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.our-chef.update', $chef->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">

                                <div class="form-group col-12">
                                    <label>{{__('admin.Existing Image')}}</label>
                                    <div>
                                        <img src="{{ asset($chef->image) }}" alt="" width="150px">
                                    </div>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Image')}} </label>
                                    <input type="file" class="form-control-file"  name="image">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="name" class="form-control"  name="name" value="{{ $chef->name }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Desgination')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="designation" class="form-control"  name="designation" value="{{ $chef->designation }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Facebook')}}</label>
                                    <input type="text" class="form-control"  name="facebook" value="{{ $chef->facebook }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Twitter')}}</label>
                                    <input type="text" class="form-control"  name="twitter" value="{{ $chef->twitter }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Linkedin')}}</label>
                                    <input type="text" class="form-control"  name="linkedin" value="{{ $chef->linkedin }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Instagram')}}</label>
                                    <input type="text" class="form-control"  name="instagram" value="{{ $chef->instagram }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control">
                                        <option  {{ $chef->status == 1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                        <option  {{ $chef->status == 0 ? 'selected' : '' }} value="0">{{__('admin.Inactive')}}</option>
                                    </select>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary">{{__('admin.Save')}}</button>
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
