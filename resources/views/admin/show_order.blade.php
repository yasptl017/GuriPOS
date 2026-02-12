@extends('admin.master_layout')
@section('title')
    <title>{{ __('admin.Invoice') }}</title>
@endsection

<style>
    .order-page-card {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        padding: 22px;
    }

    .order-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 16px;
    }

    .order-head-title {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        line-height: 1.2;
    }

    .order-head-subtitle {
        margin-top: 5px;
        font-size: 13px;
        color: #6b7280;
    }

    .order-number-chip {
        background: #f8fafc;
        border: 1px solid #dbe3ee;
        border-radius: 10px;
        padding: 8px 12px;
        font-weight: 700;
        color: #111827;
        white-space: nowrap;
    }

    .info-box {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #fbfdff;
        padding: 14px 16px;
        height: 100%;
    }

    .info-box-title {
        margin: 0 0 10px;
        font-size: 14px;
        font-weight: 700;
        color: #111827;
    }

    .info-row {
        display: grid;
        grid-template-columns: 130px minmax(0, 1fr);
        gap: 8px;
        padding: 8px 0;
        border-bottom: 1px dashed #e5e7eb;
    }

    .info-row:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .info-key {
        font-size: 12px;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .info-val {
        color: #111827;
        font-weight: 600;
        word-break: break-word;
    }

    .summary-title {
        margin: 18px 0 10px;
        font-size: 15px;
        font-weight: 700;
        color: #111827;
    }

    .summary-wrap {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
    }

    .summary-wrap .table {
        margin-bottom: 0;
    }

    .summary-wrap .table thead th {
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        color: #374151;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 11px 12px;
    }

    .summary-wrap .table td {
        padding: 11px 12px;
        border-top: 1px solid #f1f5f9;
        vertical-align: top;
    }

    .product-link {
        color: #111827;
        font-weight: 700;
    }

    .product-link:hover {
        color: #2563eb;
        text-decoration: none;
    }

    .product-options {
        color: #4b5563;
        font-size: 13px;
        line-height: 1.45;
    }

    .totals-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #fff;
        padding: 14px 16px;
    }

    .totals-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 7px 0;
        color: #1f2937;
    }

    .totals-label {
        color: #4b5563;
    }

    .totals-value {
        font-weight: 700;
    }

    .totals-row-grand {
        margin-top: 6px;
        padding-top: 10px;
        border-top: 1px solid #e5e7eb;
        font-size: 17px;
        color: #111827;
    }

    .action-bar {
        margin-top: 18px;
        padding-top: 14px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 10px;
    }

    @media (max-width: 767.98px) {
        .order-page-card {
            padding: 14px;
        }

        .order-head {
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .order-head-title {
            font-size: 20px;
        }

        .info-row {
            grid-template-columns: 1fr;
            gap: 3px;
        }
    }

    @media print {
        body {
            width: 80mm;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }

        .section-header,
        #sidebar-wrapper,
        .action-bar,
        .main-footer,
        .summary-title,
        .summary-wrap {
            display: none !important;
        }

        .order-page-card {
            border: 0;
            border-radius: 0;
            box-shadow: none;
            padding: 0;
        }

        .print_totel {
            display: block !important;
            margin-left: -100px !important;
        }
    }
</style>

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('admin.Invoice') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('admin.Dashboard') }}</a></div>
                    <div class="breadcrumb-item">{{ __('admin.Invoice') }}</div>
                </div>
            </div>

            <div class="section-body">
                @php
                    $orderAddress = optional($order->orderAddress);
                    $isWalkInOrder = optional($order->user)->email === 'walkingcustjd@pp.co.pp';
                @endphp

                <div class="order-page-card">
                    <div class="order-head">
                        <div>
                            <h2 class="order-head-title">Order Details</h2>
                            <div class="order-head-subtitle">{{ __('admin.Date') }}: {{ optional($order->created_at)->format('d F, Y h:i:s A') }}</div>
                        </div>
                        <div class="order-number-chip">Order #{{ $order->order_id }}</div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div class="info-box">
                                <h6 class="info-box-title">{{ __('admin.Delivery Information') }}</h6>

                                @if ($isWalkInOrder)
                                    <div class="info-row">
                                        <div class="info-key">{{ __('admin.Order Type') }}</div>
                                        <div class="info-val">Dine-in order</div>
                                    </div>
                                @else
                                    <div class="info-row">
                                        <div class="info-key">Name</div>
                                        <div class="info-val">{{ $orderAddress->name ?: 'N/A' }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-key">Email</div>
                                        <div class="info-val">{{ $orderAddress->email ?: 'N/A' }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-key">Phone</div>
                                        <div class="info-val">{{ $orderAddress->phone ?: 'N/A' }}</div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-key">Address</div>
                                        <div class="info-val">{{ $orderAddress->address ?: 'N/A' }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <div class="info-box">
                                <h6 class="info-box-title">{{ __('admin.Order Information') }}</h6>

                                <div class="info-row">
                                    <div class="info-key">{{ __('admin.Date') }}</div>
                                    <div class="info-val">{{ optional($order->created_at)->format('d F, Y h:i:s A') }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-key">{{ __('admin.Order Type') }}</div>
                                    <div class="info-val">{{ $order->order_type ?: 'N/A' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-key">{{ __('admin.Payment Method') }}</div>
                                    <div class="info-val">
                                        @if ($order->payment_method === 'Card')
                                            <span class="badge badge-primary"><i class="fas fa-credit-card"></i> Card</span>
                                        @elseif ($order->payment_method === 'Cash')
                                            <span class="badge badge-success"><i class="fas fa-money-bill-wave"></i> Cash</span>
                                        @elseif ($order->payment_method === 'Unpaid - COD')
                                            <span class="badge badge-danger"><i class="fas fa-clock"></i> Unpaid - COD</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $order->payment_method ?: 'N/A' }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="summary-title">{{ __('admin.Order Summary') }}</div>
                    <div class="table-responsive summary-wrap">
                        <table class="table table-hover table-md">
                            <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="25%">{{ __('admin.Product') }}</th>
                                <th width="30%">{{ __('admin.Size & Optional') }}</th>
                                <th width="13%" class="text-center">{{ __('admin.Unit Price') }}</th>
                                <th width="12%" class="text-center">{{ __('admin.Quantity') }}</th>
                                <th width="15%" class="text-right">{{ __('admin.Total') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($order->orderProducts as $index => $orderProduct)
                                @php
                                    $optionalItems = collect(json_decode($orderProduct->optional_item) ?: []);
                                    $total = ($orderProduct->unit_price * $orderProduct->qty) + $orderProduct->optional_price;
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <a href="{{ route('admin.product.edit', $orderProduct->product_id) }}" class="product-link">
                                            {{ $orderProduct->product_name }}
                                        </a>
                                    </td>
                                    <td class="product-options">
                                        @if ($orderProduct->product_size)
                                            <div>{{ $orderProduct->product_size }}</div>
                                        @endif

                                        @forelse ($optionalItems as $optionalItem)
                                            @if (!empty($optionalItem->item))
                                                <div>{{ $optionalItem->item }} (+{{ $setting->currency_icon }}{{ $optionalItem->price }})</div>
                                            @endif
                                        @empty
                                            <div>-</div>
                                        @endforelse
                                    </td>
                                    <td class="text-center">{{ $setting->currency_icon }}{{ number_format($orderProduct->unit_price, 2) }}</td>
                                    <td class="text-center">{{ $orderProduct->qty }}</td>
                                    <td class="text-right">{{ $setting->currency_icon }}{{ number_format($total, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-3">
                        <div class="col-lg-6 ml-lg-auto">
                            <div class="totals-card">
                                <div class="totals-row">
                                    <span class="totals-label">{{ __('admin.Subtotal') }}</span>
                                    <span class="totals-value">{{ $setting->currency_icon }}{{ number_format($order->sub_total, 2) }}</span>
                                </div>
                                <div class="totals-row">
                                    <span class="totals-label">{{ __('admin.Discount') }} (-)</span>
                                    <span class="totals-value">{{ $setting->currency_icon }}{{ number_format($order->coupon_price, 2) }}</span>
                                </div>
                                <div class="totals-row">
                                    <span class="totals-label">{{ __('admin.Delivery Charge') }}</span>
                                    <span class="totals-value">{{ $setting->currency_icon }}{{ number_format($order->delivery_charge, 2) }}</span>
                                </div>
                                <div class="totals-row totals-row-grand">
                                    <span class="totals-label">{{ __('admin.Grand Total') }}</span>
                                    <span class="totals-value">{{ $setting->currency_icon }}{{ number_format($order->grand_total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="action-bar">
                        <button class="btn btn-info btn-icon icon-left" onclick="viewReceipt({{ $order->id }})">
                            <i class="fas fa-receipt"></i> View Receipt
                        </button>
                        <a href="{{ route('admin.order-receipt-pdf', $order->id) }}" class="btn btn-primary btn-icon icon-left">
                            <i class="fas fa-file-pdf"></i> Save Receipt PDF
                        </a>
                        <button class="btn btn-danger btn-icon icon-left" data-toggle="modal" data-target="#deleteModal" onclick="deleteData({{ $order->id }})">
                            <i class="fas fa-times"></i> {{ __('admin.Delete') }}
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <div class="col-lg-12 print_totel" style="display:none !important">
            <div class="invoice-detail-item">
                <div class="invoice-detail-name">{{ __('admin.Subtotal') }} : {{ $setting->currency_icon }}{{ round($order->sub_total, 2) }}</div>
            </div>
            <div class="invoice-detail-item">
                <div class="invoice-detail-name">{{ __('admin.Discount') }}(-) : {{ $setting->currency_icon }}{{ round($order->coupon_price, 2) }}</div>
            </div>
            <div class="invoice-detail-item">
                <div class="invoice-detail-name">{{ __('admin.Delivery Charge') }} : {{ $setting->currency_icon }}{{ round($order->delivery_charge, 2) }}</div>
            </div>
            <hr class="mt-2 mb-2">
            <div class="invoice-detail-item">
                <div class="invoice-detail-value invoice-detail-value-lg">{{ __('admin.Grand Total') }} : {{ $setting->currency_icon }}{{ round($order->grand_total, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-receipt text-info mr-2"></i>Print Receipt</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 0;">
                    <pre id="receiptContent" style="font-family: 'Courier New', monospace; font-size: 13px; background:#f8f9fa; padding: 16px; margin:0; white-space: pre-wrap; max-height: 70vh; overflow-y: auto;"></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="printReceiptFromModal()">
                        <i class="fas fa-print mr-1"></i> Print to Printer
                    </button>
                    <button type="button" class="btn btn-primary" onclick="browserPrintReceipt()">
                        <i class="fas fa-print mr-1"></i> Print (Browser)
                    </button>
                    <a href="{{ route('admin.order-receipt-pdf', $order->id) }}" class="btn btn-danger">
                        <i class="fas fa-file-pdf mr-1"></i> Save PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url("admin/delete-order/") }}' + "/" + id)
        }

        var _receiptOrderId = null;

        function viewReceipt(orderId) {
            _receiptOrderId = orderId;
            $('#receiptContent').text('Loading...');
            $('#receiptModal').modal('show');
            $.ajax({
                url: '{{ url("admin/order-receipt") }}/' + orderId,
                type: 'GET',
                success: function(response) {
                    if (response.receipt) {
                        $('#receiptContent').text(response.receipt);
                    } else {
                        $('#receiptContent').text('No receipt saved yet.\nUse the Print button to generate and save a receipt.');
                    }
                },
                error: function() {
                    $('#receiptContent').text('Error loading receipt.');
                }
            });
        }

        function printReceiptFromModal() {
            if (!_receiptOrderId) return;
            $.ajax({
                url: '{{ url("admin/order-print") }}/' + _receiptOrderId,
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    toastr.success('Sent to printer successfully');
                    viewReceipt(_receiptOrderId);
                },
                error: function() {
                    toastr.error('Failed to send to printer');
                }
            });
        }

        function browserPrintReceipt() {
            var content = $('#receiptContent').text();
            var w = window.open('', '_blank', 'width=400,height=600');
            w.document.write('<html><head><title>Receipt</title><style>body{font-family:monospace;font-size:13px;white-space:pre;margin:10px;}</style></head><body>' + content.replace(/</g,'&lt;').replace(/>/g,'&gt;') + '</body></html>');
            w.document.close();
            w.focus();
            w.print();
        }
    </script>
@endsection
