<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Exports\OrdersExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /* ─────────────────────────────────────────────────
     |  DAILY REPORT  – orders for a single date
     ──────────────────────────────────────────────── */
    public function daily(Request $request)
    {
        $date       = $request->input('date', Carbon::today()->toDateString());
        $orderType  = $request->input('order_type', '');
        $payMethod  = $request->input('payment_method', '');
        $source     = $request->input('source', '');         // 'pos' | 'web' | ''

        $query = Order::with('orderAddress')
            ->whereDate('created_at', $date);

        $query = $this->applyFilters($query, $orderType, $payMethod, $source);

        $orders  = $query->orderBy('id', 'desc')->get();
        $summary = $this->buildSummary($orders);
        $setting = Setting::first();

        if ($request->input('export')) {
            return $this->export($orders, $request->input('export'),
                "Daily Report – {$date}", $setting);
        }

        return view('admin.reports.index', compact(
            'orders', 'summary', 'setting', 'date',
            'orderType', 'payMethod', 'source'
        ) + [
            'reportType' => 'daily',
            'title'      => "Daily Report – {$date}",
        ]);
    }

    /* ─────────────────────────────────────────────────
     |  MONTHLY REPORT  – full calendar month
     ──────────────────────────────────────────────── */
    public function monthly(Request $request)
    {
        $month      = $request->input('month', Carbon::today()->format('Y-m'));
        $orderType  = $request->input('order_type', '');
        $payMethod  = $request->input('payment_method', '');
        $source     = $request->input('source', '');

        [$year, $mon] = explode('-', $month);
        $start = Carbon::createFromDate($year, $mon, 1)->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        $query = Order::with('orderAddress')
            ->whereBetween('created_at', [$start, $end]);

        $query = $this->applyFilters($query, $orderType, $payMethod, $source);

        $orders  = $query->orderBy('id', 'desc')->get();
        $summary = $this->buildSummary($orders);
        $setting = Setting::first();

        /* Day-wise breakdown for the monthly chart */
        $daywise = $orders->groupBy(fn($o) => Carbon::parse($o->created_at)->format('Y-m-d'))
            ->map(fn($g) => [
                'count' => $g->count(),
                'total' => $g->sum('grand_total'),
            ])->sortKeys();

        if ($request->input('export')) {
            return $this->export($orders, $request->input('export'),
                "Monthly Report – {$month}", $setting);
        }

        return view('admin.reports.index', compact(
            'orders', 'summary', 'setting', 'month',
            'orderType', 'payMethod', 'source', 'daywise'
        ) + [
            'reportType' => 'monthly',
            'title'      => "Monthly Report – " . Carbon::createFromDate($year, $mon, 1)->format('F Y'),
        ]);
    }

    /* ─────────────────────────────────────────────────
     |  DATE-RANGE REPORT
     ──────────────────────────────────────────────── */
    public function range(Request $request)
    {
        $from      = $request->input('from', Carbon::today()->subDays(6)->toDateString());
        $to        = $request->input('to',   Carbon::today()->toDateString());
        $orderType = $request->input('order_type', '');
        $payMethod = $request->input('payment_method', '');
        $source    = $request->input('source', '');

        $query = Order::with('orderAddress')
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to]);

        $query = $this->applyFilters($query, $orderType, $payMethod, $source);

        $orders  = $query->orderBy('id', 'desc')->get();
        $summary = $this->buildSummary($orders);
        $setting = Setting::first();

        /* Day-wise breakdown */
        $daywise = $orders->groupBy(fn($o) => Carbon::parse($o->created_at)->format('Y-m-d'))
            ->map(fn($g) => [
                'count' => $g->count(),
                'total' => $g->sum('grand_total'),
            ])->sortKeys();

        if ($request->input('export')) {
            return $this->export($orders, $request->input('export'),
                "Report {$from} to {$to}", $setting);
        }

        return view('admin.reports.index', compact(
            'orders', 'summary', 'setting', 'from', 'to',
            'orderType', 'payMethod', 'source', 'daywise'
        ) + [
            'reportType' => 'range',
            'title'      => "Report: {$from} to {$to}",
        ]);
    }

    /* ─────────────────────────────────────────────────
     |  HELPERS
     ──────────────────────────────────────────────── */
    private function applyFilters($query, $orderType, $payMethod, $source)
    {
        if ($orderType) {
            $query->whereRaw('LOWER(order_type) = ?', [strtolower($orderType)]);
        }
        if ($payMethod) {
            $query->where('payment_method', $payMethod);
        }
        if ($source === 'pos') {
            $query->where('order_status', 3);
        } elseif ($source === 'web') {
            $query->where('order_status', '!=', 3);
        }
        return $query;
    }

    private function buildSummary($orders): array
    {
        $total      = $orders->sum('grand_total');
        $count      = $orders->count();
        $paid       = $orders->where('payment_status', 1)->sum('grand_total');
        $unpaid     = $orders->where('payment_status', 0)->sum('grand_total');
        $byType     = $orders->groupBy('order_type')
                             ->map(fn($g) => ['count' => $g->count(), 'total' => $g->sum('grand_total')]);
        $byMethod   = $orders->groupBy('payment_method')
                             ->map(fn($g) => ['count' => $g->count(), 'total' => $g->sum('grand_total')]);

        return compact('total', 'count', 'paid', 'unpaid', 'byType', 'byMethod');
    }

    private function export($orders, string $type, string $title, $setting)
    {
        $rows = $orders->map(fn($o) => [
            $o->order_id,
            optional($o->orderAddress)->name ?? '—',
            optional($o->orderAddress)->phone ?? '—',
            $o->order_type,
            $o->payment_method ?? '—',
            $o->payment_status == 1 ? 'Paid' : 'Unpaid',
            number_format($o->sub_total, 2),
            number_format($o->coupon_price, 2),
            $o->coupon_name ?? '—',
            number_format($o->delivery_charge, 2),
            number_format($o->grand_total, 2),
            Carbon::parse($o->created_at)->format('d/m/Y H:i'),
        ])->values()->toArray();

        $headings = [
            'Order ID', 'Customer', 'Phone',
            'Order Type', 'Payment Method', 'Payment Status',
            'Subtotal', 'Discount', 'Coupon',
            'Delivery', 'Grand Total', 'Date',
        ];

        if ($type === 'excel') {
            return Excel::download(
                new OrdersExport($rows, $headings),
                'report-' . now()->format('Ymd-His') . '.xlsx'
            );
        }

        // PDF
        $pdf = new Dompdf();
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $pdf->setOptions($options);
        $html = view('admin.reports.pdf', compact('rows', 'headings', 'title', 'setting'))->render();
        $pdf->loadHtml($html);
        $pdf->render();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'report-' . now()->format('Ymd-His') . '.pdf');
    }
}
