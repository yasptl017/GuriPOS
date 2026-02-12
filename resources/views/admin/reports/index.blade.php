@extends('admin.master_layout')
@section('title')
    <title>{{ $title }}</title>
@endsection
@section('admin-content')
<style>
.rpt-card { background:#fff; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,.07); padding:20px 24px; margin-bottom:20px; }
.rpt-stat { border-radius:12px; padding:18px 20px; color:#fff; display:flex; align-items:center; gap:14px; }
.rpt-stat .rpt-icon { font-size:28px; opacity:.85; }
.rpt-stat .rpt-val { font-size:22px; font-weight:700; line-height:1; }
.rpt-stat .rpt-lbl { font-size:12px; opacity:.85; margin-top:3px; }
.rpt-stat-green  { background:linear-gradient(135deg,#28a745,#20c997); }
.rpt-stat-blue   { background:linear-gradient(135deg,#007bff,#17a2b8); }
.rpt-stat-orange { background:linear-gradient(135deg,#ff7c08,#fd7e14); }
.rpt-stat-red    { background:linear-gradient(135deg,#dc3545,#c0392b); }
.rpt-stat-purple { background:linear-gradient(135deg,#6f42c1,#9b59b6); }
.filter-tab { padding:8px 20px; border-radius:20px; font-weight:600; font-size:13px;
    cursor:pointer; border:2px solid #dee2e6; background:#fff; color:#495057; transition:.15s; }
.filter-tab.active { background:#007bff; border-color:#007bff; color:#fff; }
.rpt-table thead th { font-size:12px; font-weight:700; color:#888; text-transform:uppercase;
    letter-spacing:.4px; padding:10px 12px; border-bottom:2px solid #f0f0f0; background:#f8f9fa; }
.rpt-table tbody td { font-size:13px; padding:10px 12px; vertical-align:middle; border-bottom:1px solid #fafafa; }
.rpt-table tbody tr:last-child td { border-bottom:none; }
.badge-dinein   { background:#6f42c1; }
.badge-pickup   { background:#fd7e14; }
.badge-delivery { background:#20c997; }
.badge-card     { background:#007bff; }
.badge-cash     { background:#28a745; }
.badge-unpaid   { background:#dc3545; }
.breakdown-item { display:flex; justify-content:space-between; align-items:center;
    padding:8px 0; border-bottom:1px solid #f5f5f5; font-size:13px; }
.breakdown-item:last-child { border-bottom:none; }
.breakdown-bar { height:6px; border-radius:3px; margin-top:4px; background:#007bff; transition:.3s; }
</style>

<div class="main-content">
<section class="section">
    <div class="section-header">
        <h1><i class="fas fa-chart-bar mr-2 text-primary"></i>Reports</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Reports</div>
            <div class="breadcrumb-item">{{ ucfirst($reportType ?? 'daily') }}</div>
        </div>
    </div>

    {{-- ── Filter Card ── --}}
    <div class="rpt-card">
        {{-- Report type tabs --}}
        <div class="d-flex gap-2 mb-3 flex-wrap" style="gap:8px;">
            <a href="{{ route('admin.report.daily') }}"
               class="filter-tab {{ $reportType==='daily' ? 'active' : '' }}">
                <i class="fas fa-calendar-day mr-1"></i> Daily
            </a>
            <a href="{{ route('admin.report.monthly') }}"
               class="filter-tab {{ $reportType==='monthly' ? 'active' : '' }}">
                <i class="fas fa-calendar-alt mr-1"></i> Monthly
            </a>
            <a href="{{ route('admin.report.range') }}"
               class="filter-tab {{ $reportType==='range' ? 'active' : '' }}">
                <i class="fas fa-calendar-week mr-1"></i> Date Range
            </a>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.report.' . ($reportType ?? 'daily')) }}" id="reportForm">
            <div class="row align-items-end">
                @if($reportType === 'daily')
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">Date</label>
                    <input type="date" name="date" class="form-control form-control-sm"
                           value="{{ $date ?? now()->toDateString() }}">
                </div>
                @elseif($reportType === 'monthly')
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">Month</label>
                    <input type="month" name="month" class="form-control form-control-sm"
                           value="{{ $month ?? now()->format('Y-m') }}">
                </div>
                @else
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">From</label>
                    <input type="date" name="from" class="form-control form-control-sm"
                           value="{{ $from ?? now()->subDays(6)->toDateString() }}">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">To</label>
                    <input type="date" name="to" class="form-control form-control-sm"
                           value="{{ $to ?? now()->toDateString() }}">
                </div>
                @endif

                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">Source</label>
                    <select name="source" class="form-control form-control-sm">
                        <option value=""   {{ ($source??'')=== ''    ? 'selected':'' }}>All Orders</option>
                        <option value="pos" {{ ($source??'')==='pos'  ? 'selected':'' }}>POS Only</option>
                        <option value="web" {{ ($source??'')==='web'  ? 'selected':'' }}>Web Only</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">Order Type</label>
                    <select name="order_type" class="form-control form-control-sm">
                        <option value=""         {{ ($orderType??'')=== ''         ? 'selected':'' }}>All Types</option>
                        <option value="Pickup"   {{ ($orderType??'')==='Pickup'    ? 'selected':'' }}>Pickup</option>
                        <option value="Delivery" {{ ($orderType??'')==='Delivery'  ? 'selected':'' }}>Delivery</option>
                        <option value="Dine-in"  {{ ($orderType??'')==='Dine-in'   ? 'selected':'' }}>Dine-in</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">Payment Method</label>
                    <select name="payment_method" class="form-control form-control-sm">
                        <option value=""           {{ ($payMethod??'')=== ''          ? 'selected':'' }}>All Methods</option>
                        <option value="Card"        {{ ($payMethod??'')==='Card'       ? 'selected':'' }}>Card</option>
                        <option value="Cash"        {{ ($payMethod??'')==='Cash'       ? 'selected':'' }}>Cash</option>
                        <option value="Stripe"      {{ ($payMethod??'')==='Stripe'     ? 'selected':'' }}>Stripe</option>
                        <option value="Unpaid - COD" {{ ($payMethod??'')==='Unpaid - COD' ? 'selected':'' }}>Unpaid/COD</option>
                    </select>
                </div>
                <div class="col-md-auto mb-2 d-flex gap-1" style="gap:6px;">
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="fas fa-search mr-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.report.' . ($reportType ?? 'daily')) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>

        {{-- Export buttons --}}
        <div class="mt-2 d-flex flex-wrap" style="gap:8px;">
            <form method="GET" action="{{ route('admin.report.' . ($reportType ?? 'daily')) }}">
                @foreach(request()->except('export') as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <input type="hidden" name="export" value="excel">
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                </button>
            </form>
            <form method="GET" action="{{ route('admin.report.' . ($reportType ?? 'daily')) }}">
                @foreach(request()->except('export') as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <input type="hidden" name="export" value="pdf">
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf mr-1"></i> Export PDF
                </button>
            </form>
        </div>
    </div>

    {{-- ── Summary Stats ── --}}
    <div class="row">
        <div class="col-md-3 col-6 mb-3">
            <div class="rpt-stat rpt-stat-blue">
                <div class="rpt-icon"><i class="fas fa-receipt"></i></div>
                <div>
                    <div class="rpt-val">{{ $summary['count'] }}</div>
                    <div class="rpt-lbl">Total Orders</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="rpt-stat rpt-stat-green">
                <div class="rpt-icon"><i class="fas fa-dollar-sign"></i></div>
                <div>
                    <div class="rpt-val">{{ $setting->currency_icon }}{{ number_format($summary['total'], 2) }}</div>
                    <div class="rpt-lbl">Total Revenue</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="rpt-stat rpt-stat-orange">
                <div class="rpt-icon"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="rpt-val">{{ $setting->currency_icon }}{{ number_format($summary['paid'], 2) }}</div>
                    <div class="rpt-lbl">Paid</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="rpt-stat rpt-stat-red">
                <div class="rpt-icon"><i class="fas fa-clock"></i></div>
                <div>
                    <div class="rpt-val">{{ $setting->currency_icon }}{{ number_format($summary['unpaid'], 2) }}</div>
                    <div class="rpt-lbl">Unpaid/Pending</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Breakdown + Day-wise chart (monthly / range only) ── --}}
    <div class="row">
        {{-- Order Type Breakdown --}}
        <div class="col-md-4 mb-3">
            <div class="rpt-card h-100">
                <h6 class="font-weight-bold text-muted mb-3"><i class="fas fa-utensils mr-1"></i>By Order Type</h6>
                @forelse($summary['byType'] as $type => $data)
                @php
                    $pct = $summary['total'] > 0 ? ($data['total']/$summary['total']*100) : 0;
                    $color = match(strtolower($type)) {
                        'pickup'   => '#fd7e14',
                        'delivery' => '#20c997',
                        default    => '#6f42c1',
                    };
                @endphp
                <div class="breakdown-item">
                    <div>
                        <span class="badge badge-pill text-white" style="background:{{ $color }}">{{ $type ?: 'Unknown' }}</span>
                        <small class="text-muted ml-1">{{ $data['count'] }} orders</small>
                    </div>
                    <strong>{{ $setting->currency_icon }}{{ number_format($data['total'], 2) }}</strong>
                </div>
                <div class="breakdown-bar" style="width:{{ number_format($pct,1) }}%;background:{{ $color }};"></div>
                @empty
                <p class="text-muted small">No data</p>
                @endforelse
            </div>
        </div>

        {{-- Payment Method Breakdown --}}
        <div class="col-md-4 mb-3">
            <div class="rpt-card h-100">
                <h6 class="font-weight-bold text-muted mb-3"><i class="fas fa-credit-card mr-1"></i>By Payment Method</h6>
                @forelse($summary['byMethod'] as $method => $data)
                @php
                    $pct = $summary['total'] > 0 ? ($data['total']/$summary['total']*100) : 0;
                    $color = match(strtolower($method)) {
                        'card', 'stripe' => '#007bff',
                        'cash'           => '#28a745',
                        default          => '#dc3545',
                    };
                @endphp
                <div class="breakdown-item">
                    <div>
                        <span class="badge badge-pill text-white" style="background:{{ $color }}">{{ $method ?: 'Unknown' }}</span>
                        <small class="text-muted ml-1">{{ $data['count'] }} orders</small>
                    </div>
                    <strong>{{ $setting->currency_icon }}{{ number_format($data['total'], 2) }}</strong>
                </div>
                <div class="breakdown-bar" style="width:{{ number_format($pct,1) }}%;background:{{ $color }};"></div>
                @empty
                <p class="text-muted small">No data</p>
                @endforelse
            </div>
        </div>

        {{-- Day-wise mini chart (monthly/range) or avg (daily) --}}
        <div class="col-md-4 mb-3">
            <div class="rpt-card h-100">
                @if(!empty($daywise) && $daywise->count() > 1)
                <h6 class="font-weight-bold text-muted mb-2"><i class="fas fa-chart-line mr-1"></i>Daily Trend</h6>
                <canvas id="trendChart" height="160"></canvas>
                @else
                <h6 class="font-weight-bold text-muted mb-3"><i class="fas fa-calculator mr-1"></i>Quick Stats</h6>
                @php $avg = $summary['count'] > 0 ? $summary['total']/$summary['count'] : 0; @endphp
                <div class="breakdown-item"><span class="text-muted">Avg Order Value</span><strong>{{ $setting->currency_icon }}{{ number_format($avg,2) }}</strong></div>
                <div class="breakdown-item"><span class="text-muted">Paid Orders</span><strong>{{ $orders->where('payment_status',1)->count() }}</strong></div>
                <div class="breakdown-item"><span class="text-muted">Unpaid Orders</span><strong>{{ $orders->where('payment_status',0)->count() }}</strong></div>
                <div class="breakdown-item"><span class="text-muted">Total Items Sold</span><strong>{{ $orders->sum('product_qty') }}</strong></div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Orders Table ── --}}
    <div class="rpt-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="font-weight-bold mb-0"><i class="fas fa-list mr-1"></i>{{ $title }} <span class="badge badge-secondary ml-1">{{ $summary['count'] }} orders</span></h6>
        </div>
        <div class="table-responsive">
            <table class="table rpt-table" id="rptDataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Type</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th class="text-right">Subtotal</th>
                        <th class="text-right">Discount</th>
                        <th class="text-right">Delivery</th>
                        <th class="text-right">Grand Total</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $i => $order)
                    <tr>
                        <td class="text-muted">{{ $i+1 }}</td>
                        <td><code>{{ $order->order_id }}</code></td>
                        <td>
                            <div style="font-weight:600;">{{ optional($order->orderAddress)->name ?? '—' }}</div>
                            <small class="text-muted">{{ optional($order->orderAddress)->phone }}</small>
                        </td>
                        <td>
                            @php
                                $tc = match(strtolower($order->order_type ?? '')) {
                                    'pickup' => '#fd7e14', 'delivery' => '#20c997', default => '#6f42c1'
                                };
                            @endphp
                            <span class="badge text-white" style="background:{{ $tc }}">{{ $order->order_type }}</span>
                        </td>
                        <td>
                            @php
                                $mc = match(strtolower($order->payment_method ?? '')) {
                                    'card', 'stripe' => '#007bff', 'cash' => '#28a745', default => '#dc3545'
                                };
                            @endphp
                            <span class="badge text-white" style="background:{{ $mc }}">{{ $order->payment_method ?? '—' }}</span>
                        </td>
                        <td>
                            @if($order->payment_status == 1)
                                <span class="badge badge-success">Paid</span>
                            @else
                                <span class="badge badge-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td class="text-right">{{ $setting->currency_icon }}{{ number_format($order->sub_total,2) }}</td>
                        <td class="text-right text-success">
                            @if($order->coupon_price > 0)
                                −{{ $setting->currency_icon }}{{ number_format($order->coupon_price,2) }}
                                @if($order->coupon_name)
                                    <br><small class="text-muted">{{ $order->coupon_name }}</small>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-right">{{ $setting->currency_icon }}{{ number_format($order->delivery_charge,2) }}</td>
                        <td class="text-right"><strong>{{ $setting->currency_icon }}{{ number_format($order->grand_total,2) }}</strong></td>
                        <td>
                            <small>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</small><br>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}</small>
                        </td>
                        <td>
                            <a href="{{ route('admin.order-show', $order->id) }}" class="btn btn-xs btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="12" class="text-center text-muted py-4">No orders found for selected filters.</td></tr>
                    @endforelse
                </tbody>
                @if($orders->count() > 0)
                <tfoot>
                    <tr class="table-light font-weight-bold">
                        <td colspan="6" class="text-right">Totals:</td>
                        <td class="text-right">{{ $setting->currency_icon }}{{ number_format($orders->sum('sub_total'),2) }}</td>
                        <td class="text-right text-success">−{{ $setting->currency_icon }}{{ number_format($orders->sum('coupon_price'),2) }}</td>
                        <td class="text-right">{{ $setting->currency_icon }}{{ number_format($orders->sum('delivery_charge'),2) }}</td>
                        <td class="text-right">{{ $setting->currency_icon }}{{ number_format($orders->sum('grand_total'),2) }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

</section>
</div>

@if(!empty($daywise) && $daywise->count() > 1)
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var daywise = @json($daywise);
    var labels  = Object.keys(daywise).map(function(d) {
        var p = d.split('-');
        return p[2]+'/'+p[1];
    });
    var counts = Object.values(daywise).map(function(v){ return v.count; });
    var totals = Object.values(daywise).map(function(v){ return parseFloat(v.total); });

    new Chart(document.getElementById('trendChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Revenue',
                    data: totals,
                    backgroundColor: 'rgba(0,123,255,.25)',
                    borderColor: '#007bff',
                    borderWidth: 2,
                    yAxisID: 'y',
                    type: 'line',
                    fill: true,
                    tension: .4,
                    pointRadius: 3,
                },
                {
                    label: 'Orders',
                    data: counts,
                    backgroundColor: 'rgba(255,124,8,.70)',
                    borderRadius: 4,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { labels: { font: { size: 11 } } } },
            scales: {
                y:  { position: 'left',  ticks: { font:{size:10} } },
                y1: { position: 'right', grid:{ drawOnChartArea:false }, ticks: { font:{size:10} } },
                x:  { ticks: { font:{size:10}, maxRotation:45 } }
            }
        }
    });
});
</script>
@endif

<script>
$(document).ready(function() {
    $('#rptDataTable').DataTable({
        paging: true,
        pageLength: 25,
        order: [],
        columnDefs: [{ orderable: false, targets: [11] }],
        language: { search: 'Search orders:' }
    });
});
</script>
@endsection
