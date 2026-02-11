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

    public function index()
    {
        $orders = Order::with('user', 'orderAddress')
               ->orderBy('id', 'desc')
               ->where('order_status', 3)
               ->paginate(50);
        $title = trans('POS Orders');
        $setting = Setting::first();
        $orderStatus = 3;
        return view('admin.posorder', compact('orders', 'title', 'orderStatus', 'setting'));
    }

    public function webOrder()
    {
        $orders = Order::with('user', 'orderAddress') // Load the 'orderAddress' relationship
                     ->orderBy('id', 'desc')
                     ->where('order_status', 0)
                     ->paginate(50);
        $title = trans('Web Orders');
        $setting = Setting::first();
        $orderStatus = 0;
    
        return view('admin.order', compact('orders', 'title', 'orderStatus', 'setting'));
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

    public function printOrder($id)
    {
        $order = $this->getOrderDetails($id);
        try {
            // Print to kitchen
            // $this->printerService->printToKitchen($order);

            // Print to desk
            $this->printerService->printToDesk($order);

            $notification = trans('Print successfully');
            $notification = array('messege' => $notification . $id, 'alert-type' => 'success');
            return redirect()->route('admin.all-order')->with($notification);
        } catch (Exception $e) {
            return response()->json(['message' => 'Printing failed: ' . $e->getMessage()], 500);
        }
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


}
