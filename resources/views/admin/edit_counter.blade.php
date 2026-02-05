@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Edit Counter')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Edit Counter')}}</h1>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.counter.index') }}" class="btn btn-primary"><i class="fas fa-backward"></i> {{__('admin.Go Back')}}</a>
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.counter.update', $counter->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">

                                <div class="form-group col-12">
                                    <label>{{__('admin.Icon')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="icon" class="form-control custom-icon-picker" autocomplete="off" value="{{ $counter->icon }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Title')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" value="{{ $counter->title }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Quantity')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="quantity" class="form-control" value="{{ $counter->quantity }}">
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
