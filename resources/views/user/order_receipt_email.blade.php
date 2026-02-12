<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt</title>
</head>
<body style="margin:0;padding:20px;background:#f5f7fb;font-family:Arial,sans-serif;color:#111827;">
<div style="max-width:760px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;">
    <div style="padding:20px 24px;background:#0f172a;color:#ffffff;">
        <h2 style="margin:0;font-size:22px;">Order Receipt</h2>
        <p style="margin:6px 0 0;font-size:14px;opacity:.9;">Order #{{ $order->order_id }}</p>
    </div>

    <div style="padding:20px 24px;">
        @if(!empty($introMessage))
            <div style="margin-bottom:18px;font-size:14px;line-height:1.6;">
                {!! clean($introMessage) !!}
            </div>
        @endif

        @php
            $address = $order->orderAddress;
            $products = $order->orderProducts;
        @endphp

        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:16px;">
            <tr>
                <td style="vertical-align:top;padding-right:8px;">
                    <h3 style="margin:0 0 6px;font-size:16px;">Customer</h3>
                    <p style="margin:0 0 2px;font-size:14px;">{{ $address->name ?? 'Guest' }}</p>
                    @if(!empty($address?->phone))
                        <p style="margin:0 0 2px;font-size:14px;">{{ $address->phone }}</p>
                    @endif
                    @if(!empty($address?->email))
                        <p style="margin:0 0 2px;font-size:14px;">{{ $address->email }}</p>
                    @endif
                    @if(!empty($address?->address))
                        <p style="margin:0;font-size:14px;">{{ $address->address }}</p>
                    @endif
                </td>
                <td style="vertical-align:top;text-align:right;">
                    <h3 style="margin:0 0 6px;font-size:16px;">Order Details</h3>
                    <p style="margin:0 0 2px;font-size:14px;">Date: {{ optional($order->created_at)->format('d M, Y h:i A') }}</p>
                    <p style="margin:0 0 2px;font-size:14px;">Type: {{ $order->order_type }}</p>
                    <p style="margin:0;font-size:14px;">Payment: {{ $order->payment_status == 1 ? 'Success' : 'Pending' }}</p>
                </td>
            </tr>
        </table>

        <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse:collapse;border:1px solid #e5e7eb;">
            <thead>
            <tr style="background:#f3f4f6;">
                <th align="left" style="font-size:13px;border-bottom:1px solid #e5e7eb;">Item</th>
                <th align="center" style="font-size:13px;border-bottom:1px solid #e5e7eb;">Qty</th>
                <th align="right" style="font-size:13px;border-bottom:1px solid #e5e7eb;">Unit</th>
                <th align="right" style="font-size:13px;border-bottom:1px solid #e5e7eb;">Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td style="font-size:14px;border-bottom:1px solid #f1f5f9;">
                        {{ $product->product_name }}
                        @if(!empty($product->product_size))
                            <div style="font-size:12px;color:#64748b;">Size: {{ $product->product_size }}</div>
                        @endif
                    </td>
                    <td align="center" style="font-size:14px;border-bottom:1px solid #f1f5f9;">{{ $product->qty }}</td>
                    <td align="right" style="font-size:14px;border-bottom:1px solid #f1f5f9;">{{ $currencyIcon }}{{ number_format($product->unit_price, 2) }}</td>
                    <td align="right" style="font-size:14px;border-bottom:1px solid #f1f5f9;">{{ $currencyIcon }}{{ number_format(($product->qty * $product->unit_price) + $product->optional_price, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <table width="100%" cellpadding="6" cellspacing="0" style="margin-top:12px;">
            <tr>
                <td align="right" style="font-size:14px;">Subtotal:</td>
                <td align="right" style="width:130px;font-size:14px;">{{ $currencyIcon }}{{ number_format($order->sub_total, 2) }}</td>
            </tr>
            <tr>
                <td align="right" style="font-size:14px;">Discount:</td>
                <td align="right" style="font-size:14px;">{{ $currencyIcon }}{{ number_format($order->coupon_price, 2) }}</td>
            </tr>
            <tr>
                <td align="right" style="font-size:14px;">Delivery Charge:</td>
                <td align="right" style="font-size:14px;">{{ $currencyIcon }}{{ number_format($order->delivery_charge, 2) }}</td>
            </tr>
            <tr>
                <td align="right" style="font-size:16px;font-weight:700;">Grand Total:</td>
                <td align="right" style="font-size:16px;font-weight:700;">{{ $currencyIcon }}{{ number_format($order->grand_total, 2) }}</td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
