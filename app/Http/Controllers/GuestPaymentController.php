<?php

namespace App\Http\Controllers;

use App\Helpers\MailHelper;
use App\Models\DeliveryArea;
use App\Models\EmailTemplate;
use App\Models\OrderControl;
use App\Models\ewayPayment;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderProduct;
use App\Models\Setting;
use App\Models\StripePayment;
use App\Services\PrinterService;
use Cart;
use Illuminate\Http\Request;
use Log;
use Session;
use stdClass;
use Stripe;
use App\Jobs\SendOrderSuccessEmail;

class GuestPaymentController extends Controller
{


    public function pickup()
    {
        $orderControl = OrderControl::first();
        if ($orderControl && !$orderControl->pickup_enabled) {
            $notification = array('messege' => $orderControl->pickup_disabled_message ?: 'Pickup is currently unavailable.', 'alert-type' => 'error');
            return redirect()->route('cart')->with($notification);
        }

        if (Cart::count() == 0) {
            $notification = trans('user_validation.Your cart is empty!');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->route('home')->with($notification);
        }
        Session::put('delivery_charge', 0);
        //$addresses = Address::with('deliveryArea')->where(['user_id' => $user->id])->get();
        $cart_contents = Cart::content();
        Session::put('order_type', 1);
        //$delivery_areas = DeliveryArea::where('status', 1)->get();
        $ewayPaymentInfo = \App\Models\ewayPayment::first();
        $stripePaymentInfo = StripePayment::first();

        $calculate_amount = $this->calculate_amount(12);

        return view('pickup')->with([
            //'addresses' => $addresses,
            'cart_contents' => $cart_contents,
            //'delivery_areas' => $delivery_areas,
            'stripePaymentInfo' => $stripePaymentInfo,
            'calculate_amount' => $calculate_amount
        ]);
    }

    public function calculate_amount($address_id)
    {

        $delivery_charge = Session::get('delivery_charge');
        $sub_total = 0;
        $coupon_price = 0.00;

        $cart_contents = Cart::content();
        foreach ($cart_contents as $index => $cart_content) {
            $item_price = $cart_content->price * $cart_content->qty;
            $item_total = $item_price + $cart_content->options->optional_item_price;
            $sub_total += $item_total;
        }

        if (Session::get('coupon_price') && Session::get('offer_type')) {
            if (Session::get('offer_type') == 1) {
                $coupon_price = Session::get('coupon_price');
                $coupon_price = ($coupon_price / 100) * $sub_total;
            } else {
                $coupon_price = Session::get('coupon_price');
            }
        }

        $grand_total = ($sub_total - $coupon_price) + $delivery_charge;

        return array(
            'sub_total' => $sub_total,
            'coupon_price' => $coupon_price,
            'delivery_charge' => $delivery_charge,
            'grand_total' => $grand_total,
        );
    }

    public function delivery()
    {
        $orderControl = OrderControl::first();
        if ($orderControl && !$orderControl->delivery_enabled) {
            $notification = array('messege' => $orderControl->delivery_disabled_message ?: 'Delivery is currently unavailable.', 'alert-type' => 'error');
            return redirect()->route('cart')->with($notification);
        }

        if (Cart::count() == 0) {
            $notification = trans('user_validation.Your cart is empty!');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->route('home')->with($notification);
        }

        $cart_contents = Cart::content();
        Session::put('order_type', 2);
        $delivery_areas = DeliveryArea::where('status', 1)->get();
        $ewayPaymentInfo = ewayPayment::first();
        $stripePaymentInfo = StripePayment::first();

        $calculate_amount = $this->calculate_amount(12);

        return view('checkout')->with([
            'cart_contents' => $cart_contents,
            'delivery_areas' => $delivery_areas,
            'stripePaymentInfo' => $stripePaymentInfo,
            'calculate_amount' => $calculate_amount
        ]);

    }

    public function stripe_payment(Request $request)
    {
        $userName = $request->input('user_name');
        $userEmail = $request->input('user_email');
        $userPhone = $request->input('user_phone');
        $userAddress = $request->input('address') ?? "Pickup Order";
        $inst = $request->input('delivery_instructions');
 
        $cart_contents = Cart::content();
        $calculate_amount = $this->calculate_amount(7);
        $stripe = StripePayment::first();
        $payableAmount = round($calculate_amount['grand_total'] * $stripe->currency_rate, 2);
        Stripe\Stripe::setApiKey($stripe->stripe_secret);

        $result = Stripe\Charge::create([
            "amount" => $payableAmount * 100,
            "currency" => $stripe->currency_code,
            "source" => $request->stripeToken,
            "description" => env('APP_NAME')
        ]);

        $order_result = $this->weborderStore(
            $calculate_amount,
            'Stripe',
            $result->balance_transaction,
            1,
            0,
            7,
            $userName,
            $userEmail,
            $userPhone,
            $userAddress,
            $inst);

        // Dispatch the email job
        $this->sendOrderSuccessMail($userName, $userEmail, $userPhone, $order_result, 'Stripe', 1);

        Session::put([
            'order-success' => $order_result['order'],
            'orderProduct-success' => $order_result['orderProduct'],
            'orderAddress-success' => $order_result['orderAddress'],
        ]);

        // Redirect immediately
        return redirect()->route('success', ['inst' => $inst]);
    }

    public function weborderStore($calculate_amount, $payment_method, $transaction_id, $payment_status, $cash_on_delivery, $address_id, $userName, $userEmail, $userPhone, $address, $inst)
    {

        $orderType = Session::get('order_type');

        $order = new Order();
        $order->order_id = substr(rand(0, time()), 0, 10);
        $order->user_id = 1;
        $order->grand_total = $calculate_amount['grand_total'];
        $order->delivery_charge = ($orderType == 1) ? 0 : $calculate_amount['delivery_charge'];
        $order->coupon_price = $calculate_amount['coupon_price'];
        $order->sub_total = $calculate_amount['sub_total'];
        $order->product_qty = Cart::count();
        $order->payment_method = $payment_method;
        $order->transection_id = $transaction_id;
        $order->payment_status = $payment_status;
        $order->order_status = 0;
        $order->order_type = ($orderType == 1) ? 'Pickup' : 'Delivery';
        $order->cash_on_delivery = $cash_on_delivery;
        $order->coupon_name = Session::get('coupon_name');
        $order->save();

        $cart_contents = Cart::content();
        foreach ($cart_contents as $index => $cart_content) {
            $optional_item_arr = array();
            foreach ($cart_content->options->optional_items as $index => $optional_item) {
                $new_item = array(
                    'item' => $optional_item['optional_name'],
                    'price' => $optional_item['optional_price'],
                );
                $optional_item_arr[] = $new_item;
            }

            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $cart_content->id;
            $orderProduct->product_name = $cart_content->name;
            $orderProduct->unit_price = $cart_content->price;
            $orderProduct->qty = $cart_content->qty;
            $orderProduct->product_size = $cart_content->options->size;
            $orderProduct->optional_price = $cart_content->options->optional_item_price;
            $orderProduct->optional_item = json_encode($optional_item_arr);
            $orderProduct->save();
        }

        // Store address
        // if ($orderType != 1) {
//        $address_id = Session::get('delivery_id');
//        $find_address = Address::find($address_id);

        $orderAddress = new OrderAddress();
        $orderAddress->order_id = $order->id;
        $orderAddress->name = $userName; // Use user-provided name
        $orderAddress->email = $userEmail; // Use user-provided email
        $orderAddress->phone = $userPhone; // Use user-provided phone
        $orderAddress->address = $address ?? "Pickup Order";
        $orderAddress->longitude = Null;
        $orderAddress->latitude = Null;
        $orderAddress->save();
        // }

        $details = $this->buildPrintableOrderDetails($order, $inst);
        $printerService = new PrinterService();
        $receipt = $printerService->getFormattedReceipt($details);
        $order->print_receipt = $receipt;
        $order->save();

        $printerService->printToKitchen($details);
        $printerService->printToDesk($details);

        Session::forget('order_type');
        Session::forget('delivery_id');
        Session::forget('delivery_charge');
        Session::forget('coupon_price');
        Session::forget('offer_type');
        Cart::destroy();


        return [
            'order' => $order,
            'orderProduct' => $orderProduct,
            'orderAddress' => $orderAddress,
        ];
    }


    public function success(Request $request)
    {
        $order = Session::get('order-success');
        $orderProduct = Session::get('orderProduct-success');
        $orderAddress = Session::get('orderAddress-success');
        $inst = $request->input('inst');

        return view('success', compact('order', 'orderProduct', 'orderAddress','inst'));

    }

    public function downloadReceipt(Order $order)
    {
        $successOrder = Session::get('order-success');

        if (!$successOrder || (int)$successOrder->id !== (int)$order->id) {
            abort(403);
        }

        if (empty($order->print_receipt)) {
            $printerService = new PrinterService();
            $details = $this->buildPrintableOrderDetails($order);
            $order->print_receipt = $printerService->getFormattedReceipt($details);
            $order->save();
        }

        $fileName = 'receipt-' . $order->order_id . '.txt';
        return response($order->print_receipt)
            ->header('Content-Type', 'text/plain; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    public function set_delivery_charge(Request $request)
    {
        Session::put('delivery_id', $request->delivery_id);
        Session::put('delivery_charge', $request->charge);
    }

    public function sendOrderSuccessMail($userName, $userEmail, $userPhone, $order_result, $payment_method, $payment_status)
    {
        if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            Log::warning('Order email skipped due to invalid address', [
                'order_id' => $order_result['order']->order_id ?? null,
                'email' => $userEmail,
            ]);
            return;
        }

        try {
            $setting = Setting::first();
            MailHelper::setMailConfig();
            $template = EmailTemplate::where('id', 6)->first();
    
            $payment_status = $payment_status == 1 ? 'Success' : 'Pending';
            $subject = $template->subject ?? ('Order Receipt #' . ($order_result['order']->order_id ?? ''));
            $message = $template->description ?? '';
            $order = $order_result['order'];
            $productsString = '';
            foreach ($order->orderProducts as $product) {
                $productsString .= $product->product_name . ' - ' . $product->qty . ', ';
            }

            $productsString = rtrim($productsString, ', ');
            $message = str_replace('{{products}}', $productsString, $message);
            $message = str_replace('{{user_name}}', $userName, $message);
            $message = str_replace('{{total_amount}}', $setting->currency_icon . $order_result['order']->grand_total, $message);
            $message = str_replace('{{payment_method}}', $payment_method, $message);
            $message = str_replace('{{payment_status}}', $payment_status, $message);
            $message = str_replace('{{order_type}}', $order_result['order']->order_type, $message);
            $current_date_time = date('d/m/Y h:i a');
            $message = str_replace('{{order_date}}', $current_date_time, $message);
            $message = str_replace('{{order_id}}', $order_result['order']->order_id, $message);

            // Dispatch the job
            SendOrderSuccessEmail::dispatch(
                $userEmail,
                $order_result['order']->id,
                $subject,
                $message
            )->afterResponse();

        } catch (\Throwable $e) {
            Log::error('Error preparing order success mail: ' . $e->getMessage());
        }
    }

    private function buildPrintableOrderDetails(Order $order, $instructions = '')
    {
        $order->loadMissing('orderProducts', 'orderAddress');

        $formattedItems = [];
        foreach ($order->orderProducts as $product) {
            $formattedItem = new stdClass();
            $formattedItem->name = $product->product_name;
            $formattedItem->quantity = (int)$product->qty;
            $formattedItem->price = $product->unit_price * $product->qty;
            $formattedItem->size = $product->product_size;
            $formattedItem->category = $product->category_name ?? '';
            $formattedItems[] = $formattedItem;
        }

        // Sort items by receipt_sort_order of their category
        $sortMap = \App\Models\Category::orderBy('receipt_sort_order')->orderBy('id')
            ->pluck('receipt_sort_order', 'name')->toArray();
        usort($formattedItems, function ($a, $b) use ($sortMap) {
            return ($sortMap[$a->category] ?? 9999) <=> ($sortMap[$b->category] ?? 9999);
        });

        $address = $order->orderAddress;
        $customerLines = [];
        if (!empty($address?->name)) {
            $customerLines[] = 'Name: ' . $address->name;
        }
        if (!empty($address?->phone)) {
            $customerLines[] = 'Phone: ' . $address->phone;
        }
        if (!empty($address?->address)) {
            $customerLines[] = 'Address: ' . $address->address;
        }

        return (object)[
            'id' => $order->id,
            'items' => $formattedItems,
            'type' => $order->order_type,
            'discount' => $order->coupon_price,
            'coupon_name' => $order->coupon_name,
            'delivery' => $order->delivery_charge,
            'total' => $order->grand_total,
            'inst' => $instructions ?? '',
            'customerDetails' => implode("\n", $customerLines),
        ];
    }
}
