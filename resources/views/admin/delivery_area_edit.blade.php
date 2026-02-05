@extends('admin.master_layout')
@section('title')
    <title>{{__('admin.Delivery area edit')}}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{__('admin.Delivery area edit')}}</h1>

            </div>

            <div class="section-body">
                <a href="{{ route('admin.delivery-area.index') }}" class="btn btn-primary"><i
                        class="fas fa-list"></i> {{__('admin.Delivery Area')}}</a>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.delivery-area.update', $area->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">


                                        <div class="form-group col-md-6">
                                            <label>{{__('Min Range')}} ({{__('kilometers')}}) <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="min_range"
                                                   value="{{ $area->min_range }}">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>{{__('Max Range')}} ({{__('Kilometers')}}) <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="max_range"
                                                   value="{{ $area->max_range }}">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>{{__('admin.Minimum delivery time')}} ({{__('admin.Minutes')}}) <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="min_time"
                                                   value="{{ $area->min_time }}">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>{{__('admin.Maximum delivery time')}} ({{__('admin.Minutes')}}) <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="max_time"
                                                   value="{{ $area->max_time }}">
                                        </div>


                                        <div class="form-group col-md-6">
                                            <label>{{__('admin.Delivery Fee')}} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="delivery_fee"
                                                   value="{{ $area->delivery_fee }}">
                                        </div>


                                        <div class="form-group col-md-6">
                                            <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                            <select name="status" class="form-control">
                                                <option
                                                    {{ $area->status == 1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                                <option
                                                    {{ $area->status == 0 ? 'selected' : '' }} value="0">{{__('admin.Inactive')}}</option>
                                            </select>
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
