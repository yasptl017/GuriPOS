<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Models\Setting;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductGallery;
use App\Models\OrderProduct;
use App\Models\ProductReview;
class ContactMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function review()
    {
        $products = Product::all();

        $orderProducts = OrderProduct::all();

        // Initialize an empty array to store product counts
        $productCounts = [];

        // Count occurrences of each product
        foreach ($orderProducts as $orderProduct) {
            $productId = $orderProduct->product_id;

            if (array_key_exists($productId, $productCounts)) {
                $productCounts[$productId]++;
            } else {
                $productCounts[$productId] = 1;
            }
        }

        // Populate counts for all products, including those not ordered
        foreach ($products as $product) {
            $productId = $product->id;

            if (!array_key_exists($productId, $productCounts)) {
                $productCounts[$productId] = 0;
            }
        }

        // Sort products based on their count
        arsort($productCounts);

        // Extract top 5 most ordered products
        $top5 = array_slice($productCounts, 0, 5, true);

        // Extract bottom 5 least ordered products
        $bottom5 = array_slice($productCounts, -5, 5, true);

        // Now you can fetch the details of these products from the Product model
        $mostOrderedProducts = Product::whereIn('id', array_keys($top5))->get();
        $leastOrderedProducts = Product::whereIn('id', array_keys($bottom5))->get();
        
        foreach ($mostOrderedProducts as $product) {
            $productId = $product->id;
            $product->count = isset($top5[$productId]) ? $top5[$productId] : 0;
        }
        
        // Add count attribute to least ordered products
        foreach ($leastOrderedProducts as $product) {
            $productId = $product->id;
            $product->count = isset($bottom5[$productId]) ? $bottom5[$productId] : 0;
        }

        $mostOrderedProducts = $mostOrderedProducts->sortByDesc('count');

        $leastOrderedProducts = $leastOrderedProducts->sortBy('count');

        $setting = Setting::first();
        $frontend_url = $setting->frontend_url;
        $frontend_view = $frontend_url.'single-product?slug=';

        return view('admin.review',compact('leastOrderedProducts','mostOrderedProducts','setting','frontend_view'));
    }

    public function index(){
        $contactMessages = ContactMessage::orderBy('id','desc')->get();
        $contact_setting = Setting::select('enable_save_contact_message','contact_email')->first();
        return view('admin.contact_message',compact('contactMessages','contact_setting'));
    }

    public function show($id){
        $contactMessage = ContactMessage::find($id);
        return view('admin.show_contact_message',compact('contactMessage'));
    }

    public function destroy($id){
        $contactMessage = ContactMessage::find($id);
        $contactMessage->delete();

        $notification = trans('admin_validation.Delete Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function handleSaveContactMessage(Request $request){

        $rules = [
            "contact_email" => "required",
            "enable_save_contact_message" => "required",
        ];

        $customMessages = [
            "contact_email.required" => trans("Email is required"),
        ];

        $this->validate($request, $rules, $customMessages);

        $setting = Setting::first();
        $setting->contact_email = $request->contact_email;
        $setting->enable_save_contact_message = $request->enable_save_contact_message;
        $setting->save();

        $notification = trans('admin_validation.Updated Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }
}
