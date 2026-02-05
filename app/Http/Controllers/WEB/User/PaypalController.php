<?php

namespace App\Http\Controllers\WEB\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\PaymentExecution;

use App\Models\BreadcrumbImage;
use App\Models\Country;
use App\Models\CountryState;
use App\Models\City;
use App\Models\BillingAddress;
use App\Models\ShippingAddress;
use App\Models\Vendor;
use App\Models\ShippingMethod;
use App\Models\DeliveryArea;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderProductVariant;
use App\Models\OrderAddress;
use App\Models\Product;
use App\Models\Setting;
use App\Models\StripePayment;
use App\Mail\OrderSuccessfully;
use App\Helpers\MailHelper;
use App\Models\EmailTemplate;
use App\Models\PaypalPayment;
use App\Models\Coupon;
use App\Models\ShoppingCart;

use App\Models\Shipping;
use App\Models\Address;
use App\Models\ShoppingCartVariant;


use App\Models\RazorpayPayment;
use App\Models\Flutterwave;
use App\Models\PaystackAndMollie;
use App\Models\InstamojoPayment;
use App\Models\ProductVariantItem;
use App\Models\FlashSaleProduct;
use App\Models\FlashSale;

use Str;
use Cart;
use Mail;
use Session;
use Auth;
use Exception;

class PaypalController extends Controller
{
    private $apiContext;
    public function __construct()
    {
        $account = PaypalPayment::first();
        $paypal_conf = \Config::get('paypal');
        $this->apiContext = new ApiContext(new OAuthTokenCredential(
            $account->client_id,
            $account->secret_id,
            )
        );

        $setting=array(
            'mode' => $account->account_mode,
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => storage_path() . '/logs/paypal.log',
            'log.LogLevel' => 'ERROR'
        );
        $this->apiContext->setConfig($setting);
    }

    public function calculate_amount($address_id){
        $sub_total = 0;
        $coupon_price = 0.00;
        $delivery_charge = Session::get('delivery_charge');

        $cart_contents = Cart::content();
        foreach ($cart_contents as $index => $cart_content){
            $item_price = $cart_content->price * $cart_content->qty;
            $item_total = $item_price + $cart_content->options->optional_item_price;
            $sub_total += $item_total;
        }

        if(Session::get('coupon_price') && Session::get('offer_type')){
            if(Session::get('offer_type') == 1) {
                $coupon_price = Session::get('coupon_price');
                $coupon_price = ($coupon_price / 100) * $sub_total;
            }else {
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


    public function payWithPaypal(Request $request){

        if(env('APP_MODE') == 0){
            $notification = trans('user_validation.This Is Demo Version. You Can Not Change Anything');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        $user = Auth::guard('web')->user();
        $cart_contents = Cart::content();

        $calculate_amount = $this->calculate_amount(7);
        Session::put('calculate_amount', $calculate_amount);

        $paypalSetting = PaypalPayment::first();
        $payableAmount = round($calculate_amount['grand_total'] * $paypalSetting->currency_rate,2);

        $name=env('APP_NAME');

        // set payer
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        // set amount total
        $amount = new Amount();
        $amount->setCurrency($paypalSetting->currency_code)
            ->setTotal($payableAmount);

        // transaction
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setDescription(env('APP_NAME'));

        // redirect url
        $redirectUrls = new RedirectUrls();

        $root_url=url('/');
        $redirectUrls->setReturnUrl(route('paypal-payment-success'))
            ->setCancelUrl(route('paypal-payment-cancled'));

        // payment
        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));


        try {
            $payment->create($this->apiContext);
        } catch (\PayPal\Exception\PayPalException $ex) {

            $notification = trans('user_validation.Payment Faild');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('payment')->with($notification);
        }


        // get paymentlink
        $approvalUrl = $payment->getApprovalLink();

        return redirect($approvalUrl);
    }

    public function paypalPaymentSuccess(Request $request){

        if (empty($request->get('PayerID')) || empty($request->get('token'))) {
            $notification = trans('user_validation.Payment Faild');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('payment')->with($notification);
        }

        $payment_id=$request->get('paymentId');
        $payment = Payment::get($payment_id, $this->apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->get('PayerID'));
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->apiContext);

        if ($result->getState() == 'approved') {
            $user = Auth::guard('web')->user();
            $calculate_amount = Session::get('calculate_amount');

            $order_result = $this->orderStore($user, $calculate_amount,  'Paypal', $payment_id, 1, 0, 7);

            $this->sendOrderSuccessMail($user, $order_result, 'Paypal', 1);

            $notification = trans('user_validation.Order submited successfully. please wait for admin approval');
            $notification = array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->route('dashboard')->with($notification);

        }else{
            $notification = trans('user_validation.Payment Faild');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('payment')->with($notification);
        }
    }

    public function paypalPaymentCancled(){
        $notification = trans('user_validation.Payment Faild');
        $notification = array('messege'=>$notification,'alert-type'=>'error');
        return redirect()->route('payment')->with($notification);
    }

    public function orderStore($user, $calculate_amount, $payment_method, $transaction_id, $payment_status, $cash_on_delivery, $address_id){

        $order = new Order();
        $order->order_id = substr(rand(0,time()),0,10);
        $order->user_id = $user->id;
        $order->grand_total = $calculate_amount['grand_total'];
        $order->delivery_charge = $calculate_amount['delivery_charge'];
        $order->coupon_price = $calculate_amount['coupon_price'];
        $order->sub_total = $calculate_amount['sub_total'];
        $order->product_qty = Cart::count();
        $order->payment_method = $payment_method;
        $order->transection_id = $transaction_id;
        $order->payment_status = $payment_status;
        $order->order_status = 0;
        $order->cash_on_delivery = $cash_on_delivery;
        $order->save();

        $cart_contents = Cart::content();
        foreach ($cart_contents as $index => $cart_content){
            $optional_item_arr = array();
            foreach($cart_content->options->optional_items as $index => $optional_item){
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

        // store address
        $address_id = Session::get('delivery_id');
        $find_address = Address::find($address_id);
        $find_delivery_address = DeliveryArea::find($find_address->delivery_area_id);
        $orderAddress = new OrderAddress();
        $orderAddress->order_id = $order->id;
        $orderAddress->name = $find_address->first_name.' '.$find_address->last_name;
        $orderAddress->email = $find_address->email;
        $orderAddress->phone = $find_address->phone;
        $orderAddress->address = $find_address->address;
        $orderAddress->longitude = $find_address->longitude;
        $orderAddress->latitude = $find_address->latitude;
        $orderAddress->delivery_time = $find_delivery_address->min_time.' - '. $find_delivery_address->max_time;
        $orderAddress->save();

        Session::forget('delivery_id');
        Session::forget('delivery_charge');
        Session::forget('coupon_price');
        Session::forget('offer_type');
        Session::forget('coupon_price');
        Session::forget('offer_type');
        Cart::destroy();

        return $order;
    }


    public function sendOrderSuccessMail($user, $order_result, $payment_method, $payment_status){

        $setting = Setting::first();

        MailHelper::setMailConfig();

        $template=EmailTemplate::where('id',6)->first();

        $payment_status = $payment_status == 1 ? 'Success' : 'Pending';
        $subject=$template->subject;
        $message=$template->description;
        $message = str_replace('{{user_name}}',$user->name,$message);
        $message = str_replace('{{total_amount}}',$setting->currency_icon.$order_result->grand_total,$message);
        $message = str_replace('{{payment_method}}',$payment_method,$message);
        $message = str_replace('{{payment_status}}',$payment_status,$message);
        $message = str_replace('{{order_status}}','Pending',$message);
        $message = str_replace('{{order_date}}',$order_result->created_at->format('d F, Y'),$message);
        Mail::to($user->email)->send(new OrderSuccessfully($message,$subject));
    }
}
