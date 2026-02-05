@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Breadcrumb Image')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Breadcrumb Image')}}</h1>
          </div>
          <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.update-breadcrumb-image') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="">{{__('admin.Existing Image')}}</label>
                                <div>
                                    <img src="{{ asset($breadcrumb_image) }}" class="w_300" alt="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="">{{__('admin.Existing Image')}}</label>
                                <input type="file" class="form-control-file" name="image">
                            </div>

                            <button class="btn btn-primary">{{__('admin.Update')}}</button>

                        </form>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>
@endsection
