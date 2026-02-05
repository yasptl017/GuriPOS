<?php

namespace App\Http\Controllers\WEB\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\CompareProduct;
use App\Models\DeliveryArea;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReport;
use App\Models\ProductReview;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Wishlist;
use App\Rules\Captcha;
use Auth;
use File;
use Hash;
use Illuminate\Http\Request;
use Image;
use Session;
use Slug;
use Str;

class UserProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function success()
    {
        $order = Session::get('order-success');
        $orderProduct = Session::get('orderProduct-success');
        $orderAddress = Session::get('orderAddress-success');


        return view('success', compact('order', 'orderProduct', 'orderAddress'));

    }

    public function dashboard()
    {

        $user = Auth::guard('web')->user();
        $orders = Order::where('user_id', $user->id)->orderBy('id', 'desc')->get();
        $total_order = $orders->count();
        $complete_order = $orders->where('order_status', 3)->count();
        $pending_order = $orders->where('order_status', 0)->count();
        $declined_order = $orders->where('order_status', 4)->count();

        $personal_info = User::select('id', 'name', 'phone', 'email', 'image', 'address')->find($user->id);

        $addresses = Address::with('deliveryArea')->where(['user_id' => $user->id])->get();

        $wishlists = Wishlist::where(['user_id' => $user->id])->get();
        $wishlist_products = array();

        foreach ($wishlists as $wishlist) {
            $wishlist_products[] = $wishlist->product_id;
        }
        $products = Product::whereIn('id', $wishlist_products)->get();

        $reviews = ProductReview::with('product')->orderBy('id', 'desc')->where(['user_id' => $user->id])->get();
        $delivery_areas = DeliveryArea::where('status', 1)->get();

        $reservations = Reservation::with('user')->where('user_id', $user->id)->orderBy('id', 'desc')->get();

        return view('user.dashboard')->with([
            'personal_info' => $personal_info,
            'total_order' => $total_order,
            'complete_order' => $complete_order,
            'pending_order' => $pending_order,
            'declined_order' => $declined_order,
            'addresses' => $addresses,
            'products' => $products,
            'reviews' => $reviews,
            'orders' => $orders,
            'delivery_areas' => $delivery_areas,
            'reservations' => $reservations,
        ]);
    }

    public function edit_profile()
    {

        $user = Auth::guard('web')->user();

        $personal_info = User::select('id', 'name', 'phone', 'email', 'image', 'address')->find($user->id);

        return view('user.edit_profile')->with([
            'personal_info' => $personal_info
        ]);
    }


    public function update_profile(Request $request)
    {
        $user = Auth::guard('web')->user();
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $user->id,
            'phone' => 'required',
        ];
        $customMessages = [
            'name.required' => trans('user_validation.Name is required'),
            'email.required' => trans('user_validation.Email is required'),
            'email.unique' => trans('user_validation.Email already exist'),
            'phone.required' => trans('user_validation.Phone is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->save();

        if ($request->file('image')) {
            $old_image = $user->image;
            $user_image = $request->image;
            $extention = $user_image->getClientOriginalExtension();
            $image_name = Str::slug($request->name) . date('-Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $extention;
            $image_name = 'uploads/custom-images/' . $image_name;

            Image::make($user_image)
                ->save(public_path() . '/' . $image_name);

            $user->image = $image_name;
            $user->save();
            if ($old_image) {
                if (File::exists(public_path() . '/' . $old_image)) unlink(public_path() . '/' . $old_image);
            }
        }

        $notification = trans('user_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('dashboard')->with($notification);
    }

    public function change_password()
    {

        $user = Auth::guard('web')->user();

        $personal_info = User::select('id', 'name', 'phone', 'email', 'image', 'address')->find($user->id);

        return view('user.change_password')->with(['personal_info' => $personal_info]);
    }

    public function update_password(Request $request)
    {
        $rules = [
            'current_password' => 'required',
            'password' => 'required|min:4|confirmed',
        ];
        $customMessages = [
            'current_password.required' => trans('user_validation.Current password is required'),
            'password.required' => trans('user_validation.Password is required'),
            'password.min' => trans('user_validation.Password minimum 4 character'),
            'password.confirmed' => trans('user_validation.Confirm password does not match'),
        ];
        $this->validate($request, $rules, $customMessages);

        $user = Auth::guard('web')->user();
        if (Hash::check($request->current_password, $user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();
            $notification = trans('user_validation.Password change successfully');
            $notification = array('messege' => $notification, 'alert-type' => 'success');
            return redirect()->back()->with($notification);
        } else {
            $notification = trans('user_validation.Current password does not match');
            $notification = array('messege' => $notification, 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }
    }

    public function review_list()
    {
        $user = Auth::guard('web')->user();
        $reviews = ProductReview::with('product')->orderBy('id', 'desc')->where(['user_id' => $user->id])->get();

        $personal_info = User::select('id', 'name', 'phone', 'email', 'image', 'address')->find($user->id);

        return view('user.review_list')->with(['personal_info' => $personal_info, 'reviews' => $reviews]);
    }

    public function wishlists()
    {
        $user = Auth::guard('web')->user();
        $wishlists = Wishlist::where(['user_id' => $user->id])->get();
        $wishlist_products = array();

        foreach ($wishlists as $wishlist) {
            $wishlist_products[] = $wishlist->product_id;
        }
        $products = Product::whereIn('id', $wishlist_products)->get();

        $personal_info = User::select('id', 'name', 'phone', 'email', 'image', 'address')->find($user->id);

        return view('user.wishlists')->with(['personal_info' => $personal_info, 'products' => $products]);
    }

    public function orders()
    {
        $user = Auth::guard('web')->user();

        $personal_info = User::select('id', 'name', 'phone', 'email', 'image', 'address')->find($user->id);
        $orders = Order::where('user_id', $user->id)->orderBy('id', 'desc')->get();

        return view('user.orders')->with(['personal_info' => $personal_info, 'orders' => $orders]);
    }

    public function single_order($id)
    {

        $user = Auth::guard('web')->user();

        $personal_info = User::select('id', 'name', 'phone', 'email', 'image', 'address')->find($user->id);

        $order = Order::with('orderProducts', 'orderAddress')->where('order_id', $id)->first();

        return view('user.order_show', compact('order', 'personal_info'));
    }

    public function upload_user_avatar(Request $request)
    {

        $user = Auth::guard('web')->user();
        if ($request->file('image')) {
            $old_image = $user->image;
            $user_image = $request->image;
            $extention = $user_image->getClientOriginalExtension();
            $image_name = Str::slug($request->name) . date('-Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $extention;
            $image_name = 'uploads/custom-images/' . $image_name;

            Image::make($user_image)
                ->save(public_path() . '/' . $image_name);

            $user->image = $image_name;
            $user->save();
            if ($old_image) {
                if (File::exists(public_path() . '/' . $old_image)) unlink(public_path() . '/' . $old_image);
            }
        }

        $notification = trans('user_validation.Update Successfully');
        return response()->json(['message' => $notification]);
    }

    public function add_to_wishlist($id)
    {
        $user = Auth::guard('web')->user();

        $is_exist = Wishlist::where(['product_id' => $id, 'user_id' => $user->id])->count();
        if ($is_exist == 0) {
            $wishlist = new Wishlist();
            $wishlist->user_id = $user->id;
            $wishlist->product_id = $id;
            $wishlist->save();
            return response()->json(['message' => trans('user_validation.Wishlist added successfully')]);
        } else {
            return response()->json(['message' => trans('user_validation.Item already added on the wishlist')], 403);
        }


    }

    public function remove_to_wishlist($id)
    {
        $user = Auth::guard('web')->user();
        Wishlist::where(['product_id' => $id, 'user_id' => $user->id])->delete();

        $notification = trans('user_validation.Wishlist removed successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);

    }

    public function store_review(Request $request)
    {
        $rules = [
            'rating' => 'required',
            'review' => 'required',
            'product_id' => 'required',
            'g-recaptcha-response' => new Captcha()
        ];
        $customMessages = [
            'rating.required' => trans('user_validation.Rating is required'),
            'review.required' => trans('user_validation.Review is required'),
            'product_id.required' => trans('user_validation.Product is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $user = Auth::guard('web')->user();
        $isExistOrder = false;
        $orders = Order::where(['user_id' => $user->id])->get();
        foreach ($orders as $key => $order) {
            foreach ($order->orderProducts as $key => $orderProduct) {
                if ($orderProduct->product_id == $request->product_id) {
                    $isExistOrder = true;
                }
            }
        }

        if (!$isExistOrder) {
            $message = trans('user_validation.You have to purchase first');
            return response()->json(['message' => $message], 403);
        }

        $isReview = ProductReview::where(['product_id' => $request->product_id, 'user_id' => $user->id])->count();
        if ($isReview > 0) {
            $message = trans('user_validation.You have already submited review');
            return response()->json(['message' => $message], 403);
        }

        $product = Product::find($request->product_id);
        $review = new ProductReview();
        $review->user_id = $user->id;
        $review->rating = $request->rating;
        $review->review = $request->review;
        $review->product_id = $request->product_id;
        $review->save();

        $message = trans('user_validation.Review added successfully');
        return response()->json(['message' => $message]);
    }


    public function store_reservation(Request $request)
    {
        // For non-authenticated users, save the reservation without a user ID
        $reservation = new Reservation();
        $reservation->reserve_date = $request->reserve_date;
        $reservation->reserve_time = $request->reserve_time;
        $reservation->person_qty = $request->person;
        $reservation->name = $request->name;
        $reservation->email = $request->email;
        $reservation->phone = $request->phone;
        $reservation->save();
    
        $notification = trans('user_validation.Reservation request submitted');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    public function reservation()
    {
        // Retrieve reservations with the specified fields and order by the latest created
        $reservations = Reservation::select(
            'id', 
            'name', 
            'email', 
            'phone', 
            'reserve_date', 
            'reserve_time', 
            'reserve_status', 
            'person_qty', 
            'created_at', 
            'updated_at'
        )->orderBy('created_at', 'desc')->get();
    
        return view('user.reservation')->with([
            'reservations' => $reservations,
        ]);
    }
    
    
}
