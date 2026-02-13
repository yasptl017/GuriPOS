@extends('admin.master_layout')
@section('title')
<title>Printer Settings</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Printer Settings</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('admin.Dashboard') }}</a></div>
                <div class="breadcrumb-item">Printer Settings</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row mt-4">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.update-printer-setting') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="kitchen_printer">Kitchen Printer Name</label>
                                    <input
                                        type="text"
                                        id="kitchen_printer"
                                        name="kitchen_printer"
                                        class="form-control"
                                        value="{{ old('kitchen_printer', optional($setting)->kitchen_printer) }}"
                                        placeholder="e.g. EPSON_TM_T82_KITCHEN"
                                    >
                                    <small class="text-muted">Used for kitchen ticket printing.</small>
                                </div>

                                <div class="form-group">
                                    <label for="desk_printer">Desk Printer Name</label>
                                    <input
                                        type="text"
                                        id="desk_printer"
                                        name="desk_printer"
                                        class="form-control"
                                        value="{{ old('desk_printer', optional($setting)->desk_printer) }}"
                                        placeholder="e.g. EPSON_TM_T82_DESK"
                                    >
                                    <small class="text-muted">Used for front desk receipt printing.</small>
                                </div>

                                <button type="submit" class="btn btn-primary">{{ __('admin.Update') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
