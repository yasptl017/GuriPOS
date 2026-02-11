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
            <form method="GET" action="{{ route('admin.product.index') }}" class="mb-3" id="product-search-form">
                <div class="input-group">
                    <input type="text" name="search" id="product-live-search" class="form-control" placeholder="{{ __('Search Products') }}" value="{{ request('search') }}" autocomplete="off">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" id="product-search-button">
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
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('admin.SN') }}</th>
                                            <th>{{ __('admin.Name') }}</th>
                                            <th>{{ __('admin.Price') }}</th>
                                            <th>{{ __('admin.Today Special') }}</th>
                                            <th>{{ __('admin.Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product-table-body">
                                        @include('admin.partials.product_table_rows', ['products' => $products, 'orderedProductIds' => $orderedProductIds, 'setting' => $setting])
                                    </tbody>
                                </table>
                                <!-- Custom Pagination -->
                                <div class="d-flex justify-content-end" id="product-pagination">
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
    (function ($) {
        "use strict";

        const $searchInput = $('#product-live-search');
        const $searchButton = $('#product-search-button');
        const $tbody = $('#product-table-body');
        const $pagination = $('#product-pagination');
        const endpoint = "{{ route('admin.product.index') }}";
        let debounceTimer = null;

        function fetchProducts(pageUrl = null) {
            const url = pageUrl || endpoint;
            const keyword = $searchInput.val() || '';

            $searchButton.prop('disabled', true).text('Searching...');

            $.ajax({
                url: url,
                type: 'GET',
                data: { search: keyword },
                success: function (response) {
                    $tbody.html(response.rows);
                    $pagination.html(response.pagination);
                },
                complete: function () {
                    $searchButton.prop('disabled', false).text("{{ __('admin.Search') }}");
                }
            });
        }

        $searchInput.on('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function () {
                fetchProducts();
            }, 300);
        });

        $('#product-search-form').on('submit', function (e) {
            e.preventDefault();
            fetchProducts();
        });

        $(document).on('click', '#product-pagination a', function (e) {
            e.preventDefault();
            fetchProducts($(this).attr('href'));
        });
    })(jQuery);

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
