<?php

namespace App\Http\Controllers;

use App\Models\BannerImage;
use App\Models\BreadcrumbImage;
use App\Models\Coupon;
use App\Models\OrderControl;
use App\Models\Product;
use Auth;
use Cart;
use Illuminate\Http\Request;
use Session;

class CartController extends Controller
{

    public function cart()
    {
        $cart_contents = Cart::content();
        Session::forget('coupon_price');
        $cart_banner = BannerImage::where('id', 5)->first();
        $orderControl = OrderControl::first() ?? new OrderControl(['pickup_enabled' => 1, 'delivery_enabled' => 1]);
        return view('cart')->with(['cart_contents' => $cart_contents, 'cart_banner' => $cart_banner, 'orderControl' => $orderControl]);
    }

    public function add_to_cart(Request $request)
    {
        $product = Product::find($request->product_id);

        $optional_items = array();
        $optional_item_price = 0;
        if ($request->optional_items) {
            foreach ($request->optional_items as $index => $optional_item) {
                $arr = explode('(::)', $request->optional_items[$index]);
                $single_item = array(
                    'optional_name' => $arr[0],
                    'optional_price' => $arr[1]
                );
                $optional_items[] = $single_item;
                $optional_item_price += $arr[1];
            }
        }

        // Split variant array correctly
        $variant_array = explode('(::)', $request->size_variant);
        $size_variant = isset($variant_array[0]) ? $variant_array[0] : null;
        $variant_price = isset($variant_array[1]) ? $variant_array[1] : $product->price;

        $cart_contents = Cart::content();
        $item_exist = false;

        foreach ($cart_contents as $index => $cart_content) {
            if ($cart_content->id == $request->product_id) {
                if ($cart_content->options->size == $size_variant) {
                    $item_exist = true;
                }
            }
        }

        if ($item_exist) {
            $notification = trans('user_validation.Item already added');
            return response()->json(['message' => $notification], 403);
        }
        session()->forget('coupon_price');

        $data = array();
        $data['id'] = $product->id;
        $data['name'] = $product->name;
        $data['qty'] = $request->qty;
        $data['price'] = $variant_price;
        $data['weight'] = 1;
        $data['options']['image'] = $product->thumb_image;
        $data['options']['slug'] = $product->slug;
        $data['options']['size'] = $size_variant; // Use the actual size variant or null
        $data['options']['size_price'] = $variant_price;
        $data['options']['optional_items'] = $optional_items;
        $data['options']['optional_item_price'] = $optional_item_price;
        Cart::add($data);


        return response()->json(['message' => 'success']);
    }


    public function cart_quantity_update(Request $request)
    {

        Cart::update($request->rowid, ['qty' => $request->quantity]);

        $notification = trans('user_validation.Item updated successfully');
        return response()->json(['message' => $notification]);

    }

    public function remove_cart_item($rowId)
    {

        Cart::remove($rowId);
        $notification = trans('user_validation.Remove successfully');
        return response()->json(['message' => $notification]);
    }

    public function cart_clear()
    {
        Cart::destroy();
        Session::forget('coupon_price');
        Session::forget('offer_type');

        $notification = trans('user_validation.Cart clear successfully');
        return response()->json(['message' => $notification]);
    }


    public function load_cart_item()
    {
        return view('mini_single_item');
    }

    public function apply_coupon(Request $request)
    {
        if ($request->coupon == null) {
            $notification = trans('user_validation.Coupon field is required');
            return response()->json(['message' => $notification], 403);
        }

        $user = Auth::guard('web')->user();

        $coupon = Coupon::where(['code' => $request->coupon, 'status' => 1])->first();

        if (!$coupon) {
            $notification = trans('user_validation.Invalid Coupon');
            return response()->json(['message' => $notification], 403);
        }

        if ($coupon->expired_date < date('Y-m-d')) {
            $notification = trans('user_validation.Coupon already expired');
            return response()->json(['message' => $notification], 403);
        }

        if ($coupon->apply_qty >= $coupon->max_quantity) {
            $notification = trans('user_validation.Sorry! You can not apply this coupon');
            return response()->json(['message' => $notification], 403);
        }

        if ($coupon->offer_type == 1) {
            $coupon_price = $coupon->discount;
            Session::put('coupon_price', $coupon_price);
            Session::put('offer_type', 1);
            Session::put('coupon_name', $request->coupon);
        } else {
            $coupon_price = $coupon->discount;
            Session::put('coupon_price', $coupon_price);
            Session::put('offer_type', 2);
            Session::put('coupon_name', $request->coupon);
        }

        return response()->json(['message' => trans('user_validation.Coupon applied successfully'), 'discount' => $coupon->discount, 'offer_type' => $coupon->offer_type]);

    }

    public function apply_coupon_from_checkout(Request $request)
    {
        if ($request->coupon == null) {
            $notification = trans('user_validation.Coupon field is required');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }

        $user = Auth::guard('web')->user();

        $coupon = Coupon::where(['code' => $request->coupon, 'status' => 1])->first();

        if (!$coupon) {
            $notification = trans('user_validation.Invalid Coupon');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }

        if ($coupon->expired_date < date('Y-m-d')) {
            $notification = trans('user_validation.Coupon already expired');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }

        if ($coupon->apply_qty >= $coupon->max_quantity) {
            $notification = trans('user_validation.Sorry! You can not apply this coupon');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }

        if ($coupon->offer_type == 1) {
            $coupon_price = $coupon->discount;
            Session::put('coupon_price', $coupon_price);
            Session::put('offer_type', 1);
            Session::put('coupon_name', $request->coupon);
        } else {
            $coupon_price = $coupon->discount;
            Session::put('coupon_price', $coupon_price);
            Session::put('offer_type', 2);
            Session::put('coupon_name', $request->coupon);
        }

        $notification = array('messege' => trans('user_validation.Coupon applied successfully'), 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }
}
