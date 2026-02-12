@extends('layout')

@section('title')
    <title>{{__('user.Orders')}}</title>
@endsection

@section('meta')
    <meta name="description" content="{{__('user.Orders')}}">
    <style>
    /* ── Success page layout ───────────────────────── */
    .suc-page {
        padding: 170px 0 100px;
        background: #f7f8fa;
        min-height: 80vh;
    }
    /* Banner */
    .suc-banner {
        text-align: center;
        padding: 36px 20px 28px;
        margin-bottom: 24px;
    }
    .suc-check {
        width: 72px; height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, #28a745, #20c997);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 32px; color: #fff;
        margin-bottom: 16px;
        box-shadow: 0 8px 24px rgba(40,167,69,.30);
    }
    .suc-banner h2 {
        font-size: 26px; font-weight: 700; color: #1a1a1a; margin-bottom: 6px;
    }
    .suc-banner > p {
        color: #555; font-size: 15px; margin-bottom: 18px;
    }
    .suc-meta {
        display: inline-flex; flex-wrap: wrap; gap: 10px;
        justify-content: center;
    }
    .suc-meta span {
        background: #fff; border: 1px solid #e0e0e0;
        border-radius: 20px; padding: 5px 14px;
        font-size: 13px; color: #333; font-weight: 500;
    }
    .suc-meta span i { margin-right: 5px; color: #ff7c08; }

    /* Card */
    .suc-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0,0,0,.07);
        overflow: hidden;
        margin-bottom: 20px;
    }
    .suc-card-header {
        display: flex; flex-wrap: wrap; gap: 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .suc-addr {
        display: flex; align-items: flex-start; gap: 12px;
        padding: 18px 24px; flex: 1; min-width: 220px;
    }
    .suc-addr + .suc-addr { border-left: 1px solid #f0f0f0; }
    .suc-addr-icon {
        width: 38px; height: 38px; border-radius: 50%;
        background: #fff7f0; display: flex; align-items: center;
        justify-content: center; color: #ff7c08; font-size: 15px; flex-shrink: 0;
    }
    .suc-addr-label { font-size: 11px; font-weight: 700; color: #aaa; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 3px; }
    .suc-addr-val { font-size: 14px; color: #333; font-weight: 500; }

    /* Table */
    .suc-table {
        width: 100%; border-collapse: collapse;
    }
    .suc-table thead tr {
        background: #f8f9fa;
    }
    .suc-table th {
        font-size: 11px; font-weight: 700; color: #888;
        text-transform: uppercase; letter-spacing: .5px;
        padding: 12px 16px; border-bottom: 1px solid #f0f0f0;
    }
    .suc-table td {
        padding: 14px 16px; border-bottom: 1px solid #fafafa;
        font-size: 14px; color: #333; vertical-align: top;
    }
    .suc-table tbody tr:last-child td { border-bottom: none; }
    .suc-item-name { font-weight: 600; display: block; }
    .suc-badge {
        display: inline-block; background: #f0f4ff; color: #4a6cf7;
        border-radius: 4px; font-size: 11px; padding: 1px 7px; margin-top: 3px; font-weight: 600;
    }
    .suc-opt {
        display: block; font-size: 12px; color: #888; margin-top: 2px;
    }
    .suc-qty {
        display: inline-block; background: #f8f9fa; border-radius: 6px;
        padding: 2px 10px; font-weight: 700; font-size: 13px;
    }

    /* Totals */
    .suc-totals {
        padding: 16px 24px 20px;
        border-top: 1px solid #f0f0f0;
        max-width: 340px; margin-left: auto;
    }
    .suc-total-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 6px 0; font-size: 14px; color: #555;
    }
    .suc-discount { color: #28a745; }
    .suc-grand {
        font-size: 17px; font-weight: 700; color: #1a1a1a;
        border-top: 2px solid #eee; padding-top: 12px; margin-top: 4px;
    }
    .suc-coupon-pill {
        display: inline-block; background: #e8f8ee; color: #1a7a3c;
        border-radius: 10px; font-size: 11px; padding: 1px 8px;
        font-weight: 700; margin-left: 4px;
    }
    .suc-instructions {
        margin-top: 12px; padding: 10px 14px;
        background: #fffbf0; border-radius: 8px; border-left: 3px solid #ff7c08;
        font-size: 13px; color: #555;
    }

    /* Action buttons */
    .suc-actions {
        display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;
        padding: 4px 0 8px;
    }
    .suc-btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 12px 28px; border-radius: 50px;
        font-size: 15px; font-weight: 600; cursor: pointer;
        transition: all .2s; text-decoration: none;
        border: 2px solid transparent;
    }
    .suc-btn-primary {
        background: #ff7c08; color: #fff; border-color: #ff7c08;
    }
    .suc-btn-primary:hover { background: #e06c00; color: #fff; border-color: #e06c00; }
    .suc-btn-outline {
        background: #fff; color: #ff7c08; border-color: #ff7c08;
    }
    .suc-btn-outline:hover { background: #fff7f0; }
    #receiptImageBtn { display: none; }

    @media (max-width: 576px) {
        .suc-page { padding-top: 150px; }
        .suc-addr + .suc-addr { border-left: none; border-top: 1px solid #f0f0f0; }
        .suc-totals { max-width: 100%; }
        .suc-table th:nth-child(3),
        .suc-table td:nth-child(3) { display: none; }
    }
    </style>
@endsection

@section('public-content')
@php
    $orderAddress = $order->orderAddress;
    $products     = $order->orderProducts;
@endphp

<section class="suc-page">
    <div class="container">

        {{-- ── Success banner ─────────────────────────── --}}
        <div class="suc-banner">
            <div class="suc-check"><i class="fas fa-check"></i></div>
            <h2>Order Confirmed!</h2>
            <p>Thank you, <strong>{{ $orderAddress->name }}</strong>. Your order has been received.</p>
            <div class="suc-meta">
                <span><i class="fas fa-hashtag"></i> {{ $order->order_id }}</span>
                <span><i class="fas fa-calendar-alt"></i> {{ $order->created_at->format('d M Y, h:i A') }}</span>
                <span>
                    <i class="fas fa-{{ $order->order_type === 'Pickup' ? 'shopping-bag' : 'motorcycle' }}"></i>
                    {{ $order->order_type }}
                </span>
                <span>
                    @if($order->payment_status == 1)
                        <i class="fas fa-check-circle text-success"></i> Paid
                    @else
                        <i class="fas fa-clock text-warning"></i> Payment Pending
                    @endif
                </span>
            </div>
        </div>

        {{-- ── Invoice card ────────────────────────────── --}}
        <div class="suc-card" id="invoiceCard">
            {{-- header: delivery / customer info --}}
            <div class="suc-card-header">
                <div class="suc-addr">
                    <div class="suc-addr-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <div class="suc-addr-label">{{ $order->order_type === 'Pickup' ? 'Pickup' : 'Delivery Address' }}</div>
                        <div class="suc-addr-val">{{ $orderAddress->address }}</div>
                        @if($orderAddress->phone)
                            <div class="suc-addr-val">{{ $orderAddress->phone }}</div>
                        @endif
                    </div>
                </div>
                @if($order->payment_method)
                <div class="suc-addr">
                    <div class="suc-addr-icon"><i class="fas fa-credit-card"></i></div>
                    <div>
                        <div class="suc-addr-label">Payment Method</div>
                        <div class="suc-addr-val">{{ $order->payment_method }}</div>
                    </div>
                </div>
                @endif
            </div>

            {{-- items table --}}
            <div class="table-responsive">
                <table class="suc-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th class="text-right">Price</th>
                            <th class="text-center">Qty</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $i => $product)
                        @php $optional_items = json_decode($product->optional_item); @endphp
                        <tr>
                            <td class="text-muted">{{ $i + 1 }}</td>
                            <td>
                                <span class="suc-item-name">{{ $product->product_name }}</span>
                                @if($product->product_size && strtolower($product->product_size) !== 'regular')
                                    <span class="suc-badge">{{ $product->product_size }}</span>
                                @endif
                                @foreach ($optional_items as $opt)
                                    <span class="suc-opt">+ {{ $opt->item }} ({{ $currency_icon }}{{ $opt->price }})</span>
                                @endforeach
                            </td>
                            <td class="text-right text-muted">{{ $currency_icon }}{{ number_format($product->unit_price, 2) }}</td>
                            <td class="text-center"><span class="suc-qty">{{ $product->qty }}</span></td>
                            <td class="text-right"><strong>{{ $currency_icon }}{{ number_format(($product->qty * $product->unit_price) + $product->optional_price, 2) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- totals --}}
            <div class="suc-totals">
                <div class="suc-total-row">
                    <span>Subtotal</span>
                    <span>{{ $currency_icon }}{{ number_format($order->sub_total, 2) }}</span>
                </div>
                @if($order->coupon_price > 0)
                <div class="suc-total-row suc-discount">
                    <span>
                        <i class="fas fa-tag mr-1"></i>
                        Discount
                        @if(!empty($order->coupon_name))
                            <span class="suc-coupon-pill">{{ $order->coupon_name }}</span>
                        @endif
                    </span>
                    <span>− {{ $currency_icon }}{{ number_format($order->coupon_price, 2) }}</span>
                </div>
                @endif
                @if($order->delivery_charge > 0)
                <div class="suc-total-row">
                    <span>Delivery Charge</span>
                    <span>{{ $currency_icon }}{{ number_format($order->delivery_charge, 2) }}</span>
                </div>
                @endif
                <div class="suc-total-row suc-grand">
                    <span>Grand Total</span>
                    <span>{{ $currency_icon }}{{ number_format($order->grand_total, 2) }}</span>
                </div>
                @if(!empty($inst))
                <div class="suc-instructions">
                    <i class="fas fa-sticky-note mr-1"></i> <strong>Note:</strong> {{ $inst }}
                </div>
                @endif
            </div>
        </div>

        {{-- ── Action buttons ──────────────────────────── --}}
        <div class="suc-actions">
            <button id="receiptImageBtn" class="suc-btn suc-btn-outline" onclick="downloadReceiptImage()">
                <i class="fas fa-download"></i> Download Receipt
            </button>
            <a href="{{ route('home') }}" class="suc-btn suc-btn-primary">
                <i class="fas fa-utensils"></i> Back to Menu
            </a>
        </div>

    </div>
</section>

<script>
(function () {
    var RECEIPT_TEXT = @json($order->print_receipt ?? '');
    var ORDER_ID     = @json($order->order_id ?? 'receipt');

    /* ── Draw receipt text onto a Canvas and return it ── */
    function buildCanvas(text) {
        var FONT_SIZE  = 14;          // px
        var LINE_H     = FONT_SIZE * 1.45;
        var PAD_X      = 20;
        var PAD_Y      = 20;
        var FONT       = FONT_SIZE + 'px "Courier New", Courier, monospace';
        var WIDTH      = 420;         // px (≈ 80-col thermal width at this font size)

        /* Measure / split lines */
        var lines = text.split('\n');

        /* Create a temp canvas just to measure width accurately */
        var tmp = document.createElement('canvas');
        var tmpCtx = tmp.getContext('2d');
        tmpCtx.font = FONT;
        var maxW = 0;
        lines.forEach(function (l) {
            var w = tmpCtx.measureText(l).width;
            if (w > maxW) maxW = w;
        });
        WIDTH = Math.max(WIDTH, maxW + PAD_X * 2);

        var height = PAD_Y * 2 + lines.length * LINE_H;

        /* Real canvas */
        var canvas = document.createElement('canvas');
        /* 2× for retina sharpness */
        canvas.width  = WIDTH  * 2;
        canvas.height = height * 2;
        canvas.style.width  = WIDTH  + 'px';
        canvas.style.height = height + 'px';

        var ctx = canvas.getContext('2d');
        ctx.scale(2, 2);

        /* Background */
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, WIDTH, height);

        /* Text */
        ctx.fillStyle = '#000000';
        ctx.font = FONT;
        ctx.textBaseline = 'top';

        lines.forEach(function (line, i) {
            ctx.fillText(line, PAD_X, PAD_Y + i * LINE_H);
        });

        return canvas;
    }

    var _canvas = null;

    function getCanvas() {
        if (!_canvas) {
            _canvas = buildCanvas(RECEIPT_TEXT || 'No receipt available.');
        }
        return _canvas;
    }

    window.downloadReceiptImage = function () {
        triggerDownload(getCanvas());
    };

    function triggerDownload(canvas) {
        var link = document.createElement('a');
        link.download = 'receipt-' + ORDER_ID + '.png';
        link.href = canvas.toDataURL('image/png');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    /* Auto-download + show button once page is ready */
    document.addEventListener('DOMContentLoaded', function () {
        var btn = document.getElementById('receiptImageBtn');
        if (btn) btn.style.display = '';

        if (RECEIPT_TEXT) {
            /* Small delay so the page visually settles first */
            setTimeout(function () {
                triggerDownload(getCanvas());
            }, 700);
        }
    });
})();
</script>
@endsection
