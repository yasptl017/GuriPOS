@extends('admin.master_layout')
@section('title')
<title>Top Products</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Top Products</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
                <div class="breadcrumb-item">Top Products</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card top-products-card">
                <div class="card-body">
                    <div class="top-products-toolbar">
                        <ul class="nav nav-pills top-products-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ $period === 'week' ? 'active' : '' }}"
                                   href="{{ route('admin.review', array_filter(['period' => 'week', 'category_id' => $categoryId])) }}">
                                    Last Week
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $period === 'month' ? 'active' : '' }}"
                                   href="{{ route('admin.review', array_filter(['period' => 'month', 'category_id' => $categoryId])) }}">
                                    Last Month
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $period === 'overall' ? 'active' : '' }}"
                                   href="{{ route('admin.review', array_filter(['period' => 'overall', 'category_id' => $categoryId])) }}">
                                    Overall
                                </a>
                            </li>
                        </ul>

                        <form method="GET" action="{{ route('admin.review') }}" class="category-filter-form">
                            <input type="hidden" name="period" value="{{ $period }}">
                            <select name="category_id" class="form-control" onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ (string) $categoryId === (string) $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped top-products-table">
                            <thead>
                                <tr>
                                    <th width="8%">#</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th width="15%">Price</th>
                                    <th width="15%">Total Qty</th>
                                    <th width="15%">Orders</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topOrderedProducts as $index => $product)
                                    <tr>
                                        <td><span class="rank-badge">{{ $index + 1 }}</span></td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->category_name }}</td>
                                        <td>{{ $setting->currency_icon }}{{ number_format((float) $product->price, 2) }}</td>
                                        <td><strong>{{ (int) $product->total_qty }}</strong></td>
                                        <td>{{ (int) $product->total_orders }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No product order data found for the selected filter.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .top-products-card {
        border: 1px solid #ebe7df;
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
    }

    .top-products-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .top-products-tabs .nav-link {
        border-radius: 999px;
        padding: 8px 14px;
        font-weight: 600;
        color: #6b7280;
        border: 1px solid #e5e7eb;
        margin-right: 8px;
        background: #fff;
    }

    .top-products-tabs .nav-link.active {
        background: #111827;
        color: #fff;
        border-color: #111827;
    }

    .category-filter-form {
        min-width: 240px;
    }

    .top-products-table thead th {
        background: #f8fafc;
    }

    .rank-badge {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f59e0b;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
    }
</style>
@endsection
