@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Error Page')}}</title>
@endsection
@section('admin-content')
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Error Page')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Error Page')}}</div>
            </div>
          </div>

          <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.error-page.update',$errorpage->id) }}" method="POST" enctype="multipart/form-data">
                                    @method('PATCH')
                                    @csrf

                                    <div class="form-group">
                                        <label for="">{{__('admin.Existing Image')}}</label>
                                        <div>
                                            <img width="200px" src="{{ asset($errorpage->image) }}" alt="">
                                        </div>
                                    </div>

                                    <div class="form-group col-12">
                                        <label>{{__('admin.New Image')}}</label>
                                        <input type="file" name="image" class="form-control-file">
                                    </div>

                                    <div class="form-group">
                                        <label for="">{{__('admin.Header')}}</label>
                                        <input type="text" name="header" class="form-control" value="{{ $errorpage->header }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="">{{__('admin.Description')}}</label>
                                        <input type="text" name="description" class="form-control" value="{{ $errorpage->description }}">
                                    </div>

                                    <button type="submit" class="btn btn-primary">{{__('admin.Update')}}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
          </div>

        </section>

      </div>

@endsection

