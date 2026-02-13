<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Exports\OrdersExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderProduct;
use App\Models\Reservation;
use App\Models\Setting;
use App\Services\PrinterService;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use stdClass;

class OrderController extends Controller
{
    protected $printerService;

    public function __construct(PrinterService $printerService)
    {
        $this->middleware('auth:admin');
        $this->printerService = $printerService;
    }

    public function index(Request $request)
    {
        $ordersQuery = Order::with('user', 'orderAddress')
            ->orderBy('id', 'desc')
            ->where('order_status', 3);

        $ordersQuery = $this->applyOrderTypeFilter($ordersQuery, $request->get('order_type'));
        $orders = $ordersQuery->paginate(50)->appends($request->query());

        $title = trans('POS Orders');
        $setting = Setting::first();
        $orderStatus = 3;
        $orderTypeFilter = $request->get('order_type', '');

        return view('admin.posorder', compact('orders', 'title', 'orderStatus', 'setting', 'orderTypeFilter'));
    }

    public function webOrder(Request $request)
    {
        $ordersQuery = Order::with('user', 'orderAddress')
            ->orderBy('id', 'desc')
            ->where('order_status', 0);

        $ordersQuery = $this->applyOrderTypeFilter($ordersQuery, $request->get('order_type'));
        $orders = $ordersQuery->paginate(50)->appends($request->query());

        $title = trans('Web Orders');
        $setting = Setting::first();
        $orderStatus = 0;
        $orderTypeFilter = $request->get('order_type', '');

        return view('admin.order', compact('orders', 'title', 'orderStatus', 'setting', 'orderTypeFilter'));
    }
    
    

    public function pregressOrder()
    {
        $orders = Order::with('user')->orderBy('id', 'desc')->where('order_status', 1)->get();
        $title = trans('admin_validation.Pregress Orders');
        $setting = Setting::first();
        $orderStatus = 1;

        return view('admin.order', compact('orders', 'title', 'orderStatus', 'setting'));
    }

    public function deliveredOrder()
    {
        $orders = Order::with('user')->orderBy('id', 'desc')->where('order_status', 2)->get();
        $title = trans('admin_validation.Delivered Orders');
        $setting = Setting::first();
        $orderStatus = 2;

        return view('admin.order', compact('orders', 'title', 'orderStatus', 'setting'));
    }

    public function completedOrder()
    {
        $orders = Order::with('user')->orderBy('id', 'desc')->where('order_status', 3)->get();
        $title = trans('admin_validation.Completed Orders');
        $setting = Setting::first();
        $orderStatus = 3;
        return view('admin.order', compact('orders', 'title', 'orderStatus', 'setting'));
    }

    public function declinedOrder()
    {
        $orders = Order::with('user')->orderBy('id', 'desc')->where('order_status', 4)->get();
        $title = trans('admin_validation.Declined Orders');
        $setting = Setting::first();
        $orderStatus = 4;
        return view('admin.order', compact('orders', 'title', 'orderStatus', 'setting'));
    }

    public function cashOnDelivery()
    {
        $orders = Order::with('user')->orderBy('id', 'desc')->where('cash_on_delivery', 1)->get();
        $title = trans('admin_validation.Cash On Delivery');
        $setting = Setting::first();
        $orderStatus = 5;
        return view('admin.order', compact('orders', 'title', 'orderStatus', 'setting'));
    }

    public function show($id)
    {
        $order = Order::with('user', 'orderProducts', 'orderAddress')->find($id);
        $setting = Setting::first();
        return view('admin.show_order', compact('order', 'setting'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $rules = [
            'order_status' => 'required',
            'payment_status' => 'required',
        ];
        $this->validate($request, $rules);

        $order = Order::find($id);
        if ($request->order_status == 0) {
            $order->order_status = 0;
            $order->save();
        } else if ($request->order_status == 1) {
            $order->order_status = 1;
            $order->order_approval_date = date('Y-m-d');
            $order->save();
        } else if ($request->order_status == 2) {
            $order->order_status = 2;
            $order->order_delivered_date = date('Y-m-d');
            $order->save();
        } else if ($request->order_status == 3) {
            $order->order_status = 3;
            $order->order_completed_date = date('Y-m-d');
            $order->save();
        } else if ($request->order_status == 4) {
            $order->order_status = 4;
            $order->order_declined_date = date('Y-m-d');
            $order->save();
        }

        if ($request->payment_status == 0) {
            $order->payment_status = 0;
            $order->save();
        } elseif ($request->payment_status == 1) {
            $order->payment_status = 1;
            $order->payment_approval_date = date('Y-m-d');
            $order->save();
        }

        $notification = trans('admin_validation.Order Status Updated successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }


    public function destroy($id)
    {
        $order = Order::find($id);
        
        $order->delete();
        $orderProducts = OrderProduct::where('order_id', $id)->get();
        $orderAddress = OrderAddress::where('order_id', $id)->first();
        OrderAddress::where('order_id', $id)->delete();

        $notification = trans('admin_validation.Delete successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.all-order')->with($notification);
    }

    public function reservation()
    {
        if (Schema::hasColumn('reservations', 'admin_viewed_at')) {
            Reservation::whereNull('admin_viewed_at')->update([
                'admin_viewed_at' => now(),
            ]);
        }

        $reservations = Reservation::with('user')->orderBy('id', 'desc')->get();

        return view('admin.reservation', compact('reservations'));
    }

    public function reservationNotifications()
    {
        $today = Carbon::today();
        $nextThreeDays = Carbon::today()->addDays(2);
        $threeDaysAgo = Carbon::now()->subDays(2)->startOfDay();

        $relevantQuery = Reservation::with('user')
            ->where(function ($query) use ($today, $nextThreeDays, $threeDaysAgo) {
                $query->whereBetween('reserve_date', [$today->toDateString(), $nextThreeDays->toDateString()])
                    ->orWhere('created_at', '>=', $threeDaysAgo);
            })
            ->orderBy('id', 'desc');

        $relevantCount = (clone $relevantQuery)->count();

        if (Schema::hasColumn('reservations', 'admin_viewed_at')) {
            $unviewedQuery = (clone $relevantQuery)->whereNull('admin_viewed_at');
        } else {
            $unviewedQuery = clone $relevantQuery;
        }

        $unviewedCount = (clone $unviewedQuery)->count();
        $latestUnviewed = $unviewedQuery->take(8)->get();

        $items = $latestUnviewed->map(function ($reservation) {
            return [
                'id' => $reservation->id,
                'name' => $reservation->name ?: optional($reservation->user)->name ?: 'Guest',
                'person_qty' => $reservation->person_qty,
                'reserve_date' => $reservation->reserve_date,
                'reserve_time' => $reservation->reserve_time,
                'created_at_human' => optional($reservation->created_at)->diffForHumans(),
            ];
        });

        return response()->json([
            'relevant_count' => $relevantCount,
            'unviewed_count' => $unviewedCount,
            'items' => $items,
        ]);
    }

    public function reservationPopupData()
    {
        $today = Carbon::today();
        $nextThreeDays = Carbon::today()->addDays(2);
        $threeDaysAgo = Carbon::now()->subDays(2)->startOfDay();

        $reservations = Reservation::with('user')
            ->where(function ($query) use ($today, $nextThreeDays, $threeDaysAgo) {
                $query->whereBetween('reserve_date', [$today->toDateString(), $nextThreeDays->toDateString()])
                    ->orWhere('created_at', '>=', $threeDaysAgo);
            })
            ->orderBy('reserve_date', 'asc')
            ->orderBy('reserve_time', 'asc')
            ->orderBy('id', 'desc')
            ->take(40)
            ->get()
            ->map(function ($reservation) {
                return [
                    'id' => $reservation->id,
                    'name' => $reservation->name ?: optional($reservation->user)->name ?: 'Guest',
                    'phone' => $reservation->phone,
                    'person_qty' => $reservation->person_qty,
                    'reserve_date' => $reservation->reserve_date,
                    'reserve_time' => $reservation->reserve_time,
                    'created_at_human' => optional($reservation->created_at)->diffForHumans(),
                ];
            });

        return response()->json([
            'items' => $reservations,
        ]);
    }

    public function markReservationNotificationsViewed()
    {
        if (Schema::hasColumn('reservations', 'admin_viewed_at')) {
            Reservation::whereNull('admin_viewed_at')->update([
                'admin_viewed_at' => now(),
            ]);
        }

        return response()->json(['status' => 'ok']);
    }

    public function update_reservation_status(Request $request, $id)
    {

        $reservation = Reservation::find($id);
        $reservation->reserve_status = $request->reserve_status;
        $reservation->save();

        $notification = trans('admin_validation.Status updated successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function delete_reservation($id)
    {

        $reservation = Reservation::find($id);
        $reservation->delete();

        $notification = trans('admin_validation.Deleted successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $orderStatus = $request->input('order_status');
        $setting = Setting::first();
        if ($orderStatus == -1) {
            $orders = Order::with('user')->whereBetween('created_at', [$startDate, $endDate])->get();
        } elseif ($orderStatus >= 0 && $orderStatus <= 4) {
            $orders = Order::with('user')->whereBetween('created_at', [$startDate, $endDate])->where('order_status', $orderStatus)->get();
        } else {
            $orders = Order::with('user')->whereBetween('created_at', [$startDate, $endDate])->where('cash_on_delivery', $orderStatus)->get();
        }
        $memberGrid = [];
        foreach ($orders as $key => $member) {
            $memberGrid[$key] = [
                $member->id,
                $member->user ? $member->user->name : 'This user has been deleted',
                $member->grand_total,
                $member->created_at,
            ];
        }
        $headings = ['Order ID', 'Customer Name', 'Total Amount', 'Created At'];

        // Check the export type
        $exportType = $request->input('export_type');

        if ($exportType == 'pdf') {
            $pdf = new Dompdf();
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $pdf->setOptions($options);
            $pdf->loadHtml(view('admin.orders_pdf', compact('memberGrid', 'headings', 'setting'))->render());
            $pdf->render();

            // Return the PDF as a download response
            return response()->streamDownload(function () use ($pdf, $startDate, $endDate) {
                echo $pdf->output();
            }, 'orders-' . $startDate . '_to_' . $endDate . '.pdf');
        } elseif ($exportType == 'xlsx') {
            // Return the XLSX download response
            $fname = 'orders-' . $startDate . '_to_' . $endDate . '.xlsx';
            return Excel::download(new OrdersExport($memberGrid, $headings), $fname);
        } else {
            // Invalid export type
            return redirect()->back()->with('error', 'Invalid export type selected.');
        }

    }

    public function printOrder(Request $request, $id)
    {
        $orderDetails = $this->getOrderDetails($id);
        try {
            $receipt = $this->printerService->getFormattedReceipt($orderDetails);

            // Save receipt to order record
            Order::where('id', $id)->update(['print_receipt' => $receipt]);

            // Print to desk
            $this->printerService->printToDesk($orderDetails);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Printed successfully', 'receipt' => $receipt]);
            }

            $notification = trans('Print successfully');
            $notification = array('messege' => $notification . $id, 'alert-type' => 'success');
            return redirect()->back()->with($notification);
        } catch (Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Printing failed: ' . $e->getMessage()], 500);
            }
            return response()->json(['message' => 'Printing failed: ' . $e->getMessage()], 500);
        }
    }

    public function viewReceipt($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['receipt' => null, 'message' => 'Order not found'], 404);
        }
        return response()->json(['receipt' => $order->print_receipt]);
    }

    public function downloadReceiptPdf($id)
    {
        $order = Order::find($id);
        if (!$order) {
            abort(404, 'Order not found');
        }

        if (empty($order->print_receipt)) {
            try {
                $orderDetails = $this->getOrderDetails($id);
                $order->print_receipt = $this->printerService->getFormattedReceipt($orderDetails);
                $order->save();
            } catch (Exception $e) {
                $order->print_receipt = 'Order #' . $order->order_id . "\n"
                    . 'Date: ' . optional($order->created_at)->format('d M Y h:i A') . "\n"
                    . 'Type: ' . ($order->order_type ?: 'N/A') . "\n"
                    . 'Payment: ' . ($order->payment_method ?: 'N/A') . "\n"
                    . 'Total: ' . $order->grand_total;
            }
        }

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $pdf = new Dompdf($options);

        $receiptText = e($order->print_receipt ?: 'No receipt data available.');
        $html = '
            <!doctype html>
            <html>
            <head>
                <meta charset="utf-8">
                <title>Receipt #' . e($order->order_id) . '</title>
                <style>
                    body { font-family: "Courier New", monospace; font-size: 11px; margin: 12px; color: #111827; }
                    .receipt-wrap {
                        margin: 0;
                        white-space: pre;
                        line-height: 1.35;
                    }
                </style>
            </head>
            <body>
                <pre class="receipt-wrap">' . $receiptText . '</pre>
            </body>
            </html>
        ';

        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        $fileName = 'receipt-' . $order->order_id . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $fileName);
    }

    private function getOrderDetails($id)
    {
        $order = Order::with('orderProducts', 'orderAddress')->find($id);
        $orderProducts = $order->orderProducts;
        $orderAddress = $order->orderAddress->address;
        $customerDetails = $order->orderAddress->name . ", \n" . $order->orderAddress->phone . ", \n" . $orderAddress;
        // Initialize an empty array to hold the formatted items
        $formattedItems = [];

        // Loop through each order product
        foreach ($orderProducts as $product) {
            // Create a new stdClass object for each item
            $formattedItem = new stdClass();
            $formattedItem->name = $product->product_name;
            $formattedItem->quantity = $product->qty;
            $formattedItem->price = $product->unit_price * $product->qty;
            $formattedItem->category = $product->category_name;
            // Add the formatted item to the array
            $formattedItems[] = $formattedItem;
        }

        // Sort items by receipt_sort_order of their category
        $sortMap = \App\Models\Category::orderBy('receipt_sort_order')->orderBy('id')
            ->pluck('receipt_sort_order', 'name')->toArray();
        usort($formattedItems, function ($a, $b) use ($sortMap) {
            return ($sortMap[$a->category] ?? 9999) <=> ($sortMap[$b->category] ?? 9999);
        });

        // Return the result as an object
        return (object)[
            'id' => $id,
            'items' => $formattedItems,
            'discount' => $order['coupon_price'],
            'delivery' => $order['delivery_charge'],
            'total' => $order['grand_total'],
            'customerDetails' => $customerDetails
        ];
    }

    private function applyOrderTypeFilter($query, $orderType)
    {
        if (empty($orderType)) {
            return $query;
        }

        $filter = strtolower(trim($orderType));

        if ($filter === 'pickup') {
            $query->whereRaw('LOWER(order_type) = ?', ['pickup']);
        } elseif ($filter === 'delivery') {
            $query->whereRaw('LOWER(order_type) = ?', ['delivery']);
        } elseif ($filter === 'dine_in') {
            $query->whereRaw("REPLACE(LOWER(order_type), '-', '') IN ('dinein', 'dine in')");
        }

        return $query;
    }


}
