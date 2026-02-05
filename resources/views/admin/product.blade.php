@extends('admin.master_layout')
@section('title')
<title>{{ __('admin.Products') }}</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __('admin.Products') }}</h1>
        </div>
        <div class="section-body">
            <div class="mb-3">
                <a href="{{ route('admin.product.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> {{ __('admin.Add New') }}
                </a>
            </div>
            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.product.index') }}" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('Search Products') }}" value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">
                            {{ __('admin.Search') }}
                        </button>
                    </div>
                </div>
            </form>
            <div class="row mt-4">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-invoice">
                                <table class="table table-striped" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>{{ __('admin.SN') }}</th>
                                            <th>{{ __('admin.Name') }}</th>
                                            <th>{{ __('admin.Price') }}</th>
                                            <th>{{ __('admin.Today Special') }}</th>
                                            <th>{{ __('admin.Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $index => $product)
                                        <tr>
                                            <td>{{ $products->firstItem() + $index }}</td>
                                            <td>
                                                <a target="_blank" href="#">{{ $product->name }}</a>
                                            </td>
                                            <td>{{ $setting->currency_icon }}{{ $product->price }}</td>
                                            <td>
                                                @if ($product->today_special)
                                                <span class="badge badge-success">{{ __('admin.Yes') }}</span>
                                                @else
                                                <span class="badge badge-danger">{{ __('admin.No') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                                </a>
                                                @php
                                                    $existOrder = $orderProducts->where('product_id', $product->id)->count();
                                                @endphp
                                                @if ($existOrder == 0)
                                                <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $product->id }})">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </a>
                                                @else
                                                <a href="javascript:;" data-toggle="modal" data-target="#canNotDeleteModal" class="btn btn-danger btn-sm" disabled>
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </a>
                                                @endif
                                                <div class="dropdown d-inline">
                                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-cog"></i>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                                        <a class="dropdown-item has-icon" href="{{ route('admin.product-gallery', $product->id) }}">
                                                            <i class="far fa-image"></i> {{ __('admin.Image Gallery') }}
                                                        </a>
                                                        <a class="dropdown-item has-icon" href="{{ route('admin.product-variant', $product->id) }}">
                                                            <i class="fas fa-cog"></i> {{ __('admin.Size / Optional Item') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- Custom Pagination -->
                                <div class="d-flex justify-content-center">
                                    {{ $products->links('custom_paginator') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </section>
</div>

<!-- Modal for delete restriction -->
<div class="modal fade" id="canNotDeleteModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                {{ __('admin.You can not delete this product. Because there are one or more order has been created in this product.') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('admin.Close') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteData(id) {
        $("#deleteForm").attr("action", '{{ url("admin/product/") }}' + "/" + id);
    }
    function changeProductStatus(id) {
        var isDemo = "{{ env('APP_MODE') }}";
        if(isDemo == 0) {
            toastr.error('This Is Demo Version. You Can Not Change Anything');
            return;
        }
        $.ajax({
            type: "put",
            data: { _token : '{{ csrf_token() }}' },
            url: "{{ url('/admin/product-status/') }}" + "/" + id,
            success: function(response) {
                toastr.success(response);
            },
            error: function(err) {
                // handle error if needed
            }
        });
    }
</script>
@endsection
