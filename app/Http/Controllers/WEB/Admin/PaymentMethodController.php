<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaypalPayment;
use App\Models\ewayPayment;
use App\Models\StripePayment;
use App\Models\RazorpayPayment;
use App\Models\Flutterwave;
use App\Models\BankPayment;
use App\Models\PaystackAndMollie;
use App\Models\InstamojoPayment;
use App\Models\CurrencyCountry;
use App\Models\Currency;
use App\Models\Setting;
use App\Models\PaymongoPayment;
use App\Models\SslcommerzPayment;
use Image;
use File;
class PaymentMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $paypal = PaypalPayment::first();
        $eway = ewayPayment::first();
        $stripe = StripePayment::first();
        $razorpay = RazorpayPayment::first();
        $flutterwave = Flutterwave::first();
        $bank = BankPayment::first();
        $paystackAndMollie = PaystackAndMollie::first();
        $instamojo = InstamojoPayment::first();
        $paymongo = PaymongoPayment::first();
        $sslcommerz = SslcommerzPayment::first();

        $countires = CurrencyCountry::orderBy('name','asc')->get();
        $currencies = Currency::orderBy('name','asc')->get();
        $setting = Setting::first();
        return view('admin.payment_method', compact('paypal','eway','stripe','razorpay','bank','paystackAndMollie','flutterwave','instamojo','countires','currencies','setting','paymongo','sslcommerz'));

    }

    public function updatePaypal(Request $request){

        $rules = [
            'paypal_client_id' => 'required',
            'paypal_secret_key' => 'required',
            'account_mode' => 'required',
            'country_name' => 'required',
            'currency_name' => 'required',
            'currency_rate' => 'required',
        ];
        $customMessages = [
            'paypal_client_id.required' => trans('admin_validation.Paypal client id is required'),
            'paypal_secret_key.required' => trans('admin_validation.Paypal secret key is required'),
            'account_mode.required' => trans('admin_validation.Account mode is required'),
            'country_name.required' => trans('admin_validation.Country name is required'),
            'currency_name.required' => trans('admin_validation.Currency name is required'),
            'currency_rate.required' => trans('admin_validation.Currency rate is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $paypal = PaypalPayment::first();
        $paypal->client_id = $request->paypal_client_id;
        $paypal->secret_id = $request->paypal_secret_key;
        $paypal->account_mode = $request->account_mode;
        $paypal->country_code = $request->country_name;
        $paypal->currency_code = $request->currency_name;
        $paypal->currency_rate = $request->currency_rate;
        $paypal->status = $request->status ? 1 : 0;
        $paypal->save();

        if($request->payment_page_image){
            $old_image=$paypal->payment_page_image;
            $image=$request->payment_page_image;
            $extention=$image->getClientOriginalExtension();
            $image_name= 'paypal-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name='uploads/website-images/'.$image_name;
            Image::make($image)
                ->save(public_path().'/'.$image_name);
            $paypal->payment_page_image=$image_name;
            $paypal->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }

        }

        $notification=trans('admin_validation.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function updateStripe(Request $request){

        $rules = [
            'stripe_key' => 'required',
            'stripe_secret' => 'required',
            'country_name' => 'required',
            'currency_name' => 'required',
            'currency_rate' => 'required',
        ];
        $customMessages = [
            'stripe_key.required' => trans('admin_validation.Stripe key is required'),
            'stripe_secret.required' => trans('admin_validation.Stripe secret is required'),
            'country_name.required' => trans('admin_validation.Country name is required'),
            'currency_name.required' => trans('admin_validation.Currency name is required'),
            'currency_rate.required' => trans('admin_validation.Currency rate is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $stripe = StripePayment::first();
        $stripe->stripe_key = $request->stripe_key;
        $stripe->stripe_secret = $request->stripe_secret;
        $stripe->country_code = $request->country_name;
        $stripe->currency_code = $request->currency_name;
        $stripe->currency_rate = $request->currency_rate;
        $stripe->status = $request->status ? 1 : 0;
        $stripe->save();

        if($request->payment_page_image){
            $old_image=$stripe->payment_page_image;
            $image=$request->payment_page_image;
            $extention=$image->getClientOriginalExtension();
            $image_name= 'paypal-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name='uploads/website-images/'.$image_name;
            Image::make($image)
                ->save(public_path().'/'.$image_name);
            $stripe->payment_page_image=$image_name;
            $stripe->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }

        }

        $notification=trans('admin_validation.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function updateEway(Request $request){

        $rules = [
            'eway_key' => 'required',
            'eway_pass' => 'required',
            'country_name' => 'required',
            'currency_name' => 'required',
            'currency_rate' => 'required',
        ];
        $customMessages = [
            'eway_key.required' => trans('admin_validation.Eway key is required'),
            'eway_pass.required' => trans('admin_validation.Eway Pass is required'),
            'country_name.required' => trans('admin_validation.Country name is required'),
            'currency_name.required' => trans('admin_validation.Currency name is required'),
            'currency_rate.required' => trans('admin_validation.Currency rate is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $eway = ewayPayment::first();
        $eway->eway_key = $request->eway_key;
        $eway->eway_pass = $request->eway_pass;
        $eway->country_code = $request->country_name;
        $eway->currency_code = $request->currency_name;
        $eway->currency_rate = $request->currency_rate;
        $eway->status = $request->status ? 1 : 0;
        $eway->save();

        if($request->payment_page_image){
            $old_image=$eway->payment_page_image;
            $image=$request->payment_page_image;
            $extention=$image->getClientOriginalExtension();
            $image_name= 'paypal-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name='uploads/website-images/'.$image_name;
            Image::make($image)
                ->save(public_path().'/'.$image_name);
            $eway->payment_page_image=$image_name;
            $eway->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }

        }
        $notification=trans('admin_validation.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function updateRazorpay(Request $request){
        $rules = [
            'razorpay_key' => 'required',
            'razorpay_secret' => 'required',
            'name' => 'required',
            'description' => 'required',
            'currency_rate' => 'required',
            'theme_color' => 'required',
            'currency_name' => 'required',
            'country_name' => 'required',
        ];
        $customMessages = [
            'razorpay_key.required' => trans('admin_validation.Razorpay key is required'),
            'razorpay_secret.required' => trans('admin_validation.Razorpay secret is required'),
            'name.required' => trans('admin_validation.Name is required'),
            'description.required' => trans('admin_validation.Description is required'),
            'country_name.required' => trans('admin_validation.Country name is required'),
            'currency_name.required' => trans('admin_validation.Currency name is required'),
            'currency_rate.required' => trans('admin_validation.Currency rate is required'),
            'theme_color.required' => trans('admin_validation.Theme Color is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $razorpay = RazorpayPayment::first();
        $razorpay->key = $request->razorpay_key;
        $razorpay->secret_key = $request->razorpay_secret;
        $razorpay->name = $request->name;
        $razorpay->currency_rate = $request->currency_rate;
        $razorpay->description = $request->description;
        $razorpay->color = $request->theme_color;
        $razorpay->country_code = $request->country_name;
        $razorpay->currency_code = $request->currency_name;
        $razorpay->status = $request->status ? 1 : 0;
        $razorpay->save();

        if($request->image){
            $old_image=$razorpay->image;
            $image=$request->image;
            $extention=$image->getClientOriginalExtension();
            $image_name= 'razorpay-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name='uploads/website-images/'.$image_name;
            Image::make($image)
                ->save(public_path().'/'.$image_name);
            $razorpay->image=$image_name;
            $razorpay->save();
            if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
        }

        if($request->payment_page_image){
            $old_image=$razorpay->payment_page_image;
            $image=$request->payment_page_image;
            $extention=$image->getClientOriginalExtension();
            $image_name= 'paypal-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name='uploads/website-images/'.$image_name;
            Image::make($image)
                ->save(public_path().'/'.$image_name);
            $razorpay->payment_page_image=$image_name;
            $razorpay->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }

        }

        $notification=trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function updateBank(Request $request){
        $rules = [
            'account_info' => 'required'
        ];
        $customMessages = [
            'account_info.required' => trans('admin_validation.Account information is required'),
        ];
        $this->validate($request, $rules,$customMessages);
        $bank = BankPayment::first();
        $bank->account_info = $request->account_info;
        $bank->status = $request->status ? 1 : 0;
        $bank->save();

        if($request->bank_payment_page_image){
            $old_image=$bank->bank_payment_page_image;
            $image=$request->bank_payment_page_image;
            $extention=$image->getClientOriginalExtension();
            $image_name= 'paypal-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name='uploads/website-images/'.$image_name;
            Image::make($image)
                ->save(public_path().'/'.$image_name);
            $bank->bank_payment_page_image=$image_name;
            $bank->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }

        }

        $notification=trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function updateMollie(Request $request){
        $rules = [
            'mollie_key' => 'required',
            'mollie_currency_rate' => 'required',
            'mollie_country_name' => 'required',
            'mollie_currency_name' => 'required'
        ];

        $customMessages = [
            'mollie_key.required' => trans('admin_validation.Mollie key is required'),
            'mollie_country_name.required' => trans('admin_validation.Country name is required'),
            'mollie_currency_name.required' => trans('admin_validation.Currency name is required'),
            'mollie_currency_rate.required' => trans('admin_validation.Currency rate is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $mollie = PaystackAndMollie::first();
        $mollie->mollie_key = $request->mollie_key;
        $mollie->mollie_currency_rate = $request->mollie_currency_rate;
        $mollie->mollie_currency_code = $request->mollie_currency_name;
        $mollie->mollie_country_code = $request->mollie_country_name;
        $mollie->mollie_status = $request->status ? 1 : 0;
        $mollie->save();

        if($request->mollie_payment_page_image){
            $old_image=$mollie->mollie_payment_page_image;
            $image=$request->mollie_payment_page_image;
            $extention=$image->getClientOriginalExtension();
            $image_name= 'paypal-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name='uploads/website-images/'.$image_name;
            Image::make($image)
                ->save(public_path().'/'.$image_name);
            $mollie->mollie_payment_page_image=$image_name;
            $mollie->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }

        }

        $notification=trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function updatePayStack(Request $request){
        $rules = [
            'paystack_public_key' => 'required',
            'paystack_secret_key' => 'required',
            'paystack_currency_rate' => 'required',
            'paystack_currency_name' => 'required',
            'paystack_country_name' => 'required'
        ];

        $customMessages = [
            'paystack_public_key.required' => trans('admin_validation.Paystack public key is required'),
            'paystack_secret_key.required' => trans('admin_validation.Paystack secret key is required'),
            'paystack_currency_rate.required' => trans('admin_validation.Currency rate is required'),
            'paystack_currency_name.required' => trans('admin_validation.Currency name is required'),
            'paystack_country_name.required' => trans('admin_validation.Country rate is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $paystact = PaystackAndMollie::first();
        $paystact->paystack_public_key = $request->paystack_public_key;
        $paystact->paystack_secret_key = $request->paystack_secret_key;
        $paystact->paystack_currency_code = $request->paystack_currency_name;
        $paystact->paystack_country_code = $request->paystack_country_name;
        $paystact->paystack_currency_rate = $request->paystack_currency_rate;
        $paystact->paystack_status = $request->status ? 1 : 0;
        $paystact->save();

        if($request->paystack_payment_page_image){
            $old_image=$paystact->paystack_payment_page_image;
            $image=$request->paystack_payment_page_image;
            $extention=$image->getClientOriginalExtension();
            $image_name= 'paypal-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name='uploads/website-images/'.$image_name;
            Image::make($image)
                ->save(public_path().'/'.$image_name);
            $paystact->paystack_payment_page_image=$image_name;
            $paystact->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }

        }

        $notification=trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function updateflutterwave(Request $request){
        $rules = [
            'public_key' => 'required',
            'secret_key' => 'required',
            'title' => 'required',
            'currency_rate' => 'required',
            'currency_name' => 'required',
            'country_name' => 'required',
        ];
        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'public_key.required' => trans('admin_validation.Public key is required'),
            'secret_key.required' => trans('admin_validation.Secret key is required'),
            'currency_rate.required' => trans('admin_validation.Currency rate is required'),
            'currency_name.required' => trans('admin_validation.Currency name is required'),
            'country_name.required' => trans('admin_validation.Country name is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $flutterwave = Flutterwave::first();
        $flutterwave->public_key = $request->public_key;
        $flutterwave->secret_key = $request->secret_key;
        $flutterwave->title = $request->title;
        $flutterwave->currency_rate = $request->currency_rate;
        $flutterwave->country_code = $request->country_name;
        $flutterwave->currency_code = $request->currency_name;
        $flutterwave->status = $request->status ? 1 : 0;
        $flutterwave->save();

        if($request->image){
            $old_image=$flutterwave->logo;
            $image=$request->image;
            $extention=$image->getClientOriginalExtension();
            $image_name= 'flutterwave-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name='uploads/website-images/'.$image_name;
            Image::make($image)
                ->save(public_path().'/'.$image_name);
            $flutterwave->logo=$image_name;
            $flutterwave->save();
            if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
        }


        if($request->payment_page_image){
            $old_image=$flutterwave->payment_page_image;
            $image=$request->payment_page_image;
            $extention=$image->getClientOriginalExtension();
            $image_name= 'paypal-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name='uploads/website-images/'.$image_name;
            Image::make($image)
                ->save(public_path().'/'.$image_name);
            $flutterwave->payment_page_image=$image_name;
            $flutterwave->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }

        }

        $notification=trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function updateInstamojo(Request $request){
        $rules = [
            'account_mode' => 'required',
            'api_key' => 'required',
            'auth_token' => 'required',
            'currency_rate' => 'required',
        ];
        $customMessages = [
            'account_mode.required' => trans('admin_validation.Account mode is required'),
            'api_key.required' => trans('admin_validation.Api key is required'),
            'currency_rate.required' => trans('admin_validation.Currency rate is required'),
            'auth_token.required' => trans('admin_validation.Auth token is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $instamojo = InstamojoPayment::first();
        $instamojo->account_mode = $request->account_mode;
        $instamojo->api_key = $request->api_key;
        $instamojo->auth_token = $request->auth_token;
        $instamojo->currency_rate = $request->currency_rate;
        $instamojo->status = $request->status ? 1 : 0;
        $instamojo->save();

        if($request->payment_page_image){
            $old_image=$instamojo->payment_page_image;
            $image=$request->payment_page_image;
            $extention=$image->getClientOriginalExtension();
            $image_name= 'paypal-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name='uploads/website-images/'.$image_name;
            Image::make($image)
                ->save(public_path().'/'.$image_name);
            $instamojo->payment_page_image=$image_name;
            $instamojo->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }

        }

        $notification=trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function updateCashOnDelivery(Request $request){
        $bank = BankPayment::first();
        $bank->cash_on_delivery_status = $request->status ? 1 : 0;
        $bank->save();

        if($request->handcash_payment_page_image){
            $old_image=$bank->handcash_payment_page_image;
            $image=$request->handcash_payment_page_image;
            $extention=$image->getClientOriginalExtension();
            $image_name= 'paypal-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name='uploads/website-images/'.$image_name;
            Image::make($image)
                ->save(public_path().'/'.$image_name);
            $bank->handcash_payment_page_image=$image_name;
            $bank->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }

        }

        $notification=trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);


    }


    public function updateSslcommerz(Request $request){
        $rules = [
            'store_id' => $request->status ? 'required' : '',
            'store_password' => $request->status ? 'required' : '',
            'currency_rate' => $request->status ? 'required|numeric' : '',
            'currency_name' => $request->status ? 'required' : '',
            'country_name' => $request->status ? 'required' : '',
        ];
        $customMessages = [
            'store_id.required' => trans('admin_validation.Store id is required'),
            'store_password.required' => trans('admin_validation.Store password is required'),
            'currency_rate.required' => trans('admin_validation.Currency rate is required'),
            'currency_name.required' => trans('admin_validation.Currency name is required'),
            'country_name.required' => trans('admin_validation.Country name is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $sslcommerz = SslcommerzPayment::first();
        $sslcommerz->mode = $request->account_mode;
        $sslcommerz->store_id = $request->store_id;
        $sslcommerz->store_password = $request->store_password;
        $sslcommerz->currency_rate = $request->currency_rate;
        $sslcommerz->country_code = $request->country_name;
        $sslcommerz->currency_code = $request->currency_name;
        $sslcommerz->status = $request->status ? 1 : 0;
        $sslcommerz->save();

        if($request->payment_page_image){
            $old_image=$sslcommerz->payment_page_image;
            $image=$request->payment_page_image;
            $extention=$image->getClientOriginalExtension();
            $image_name= 'paypal-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name='uploads/website-images/'.$image_name;
            Image::make($image)
                ->save(public_path().'/'.$image_name);
            $sslcommerz->payment_page_image=$image_name;
            $sslcommerz->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }

        }

        $notification=trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }
}
