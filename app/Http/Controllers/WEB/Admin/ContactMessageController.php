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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
class ContactMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function review(Request $request)
    {
        $period = $request->get('period', 'week');
        if (!in_array($period, ['week', 'month', 'overall'], true)) {
            $period = 'week';
        }

        $categoryId = $request->filled('category_id') ? (int) $request->get('category_id') : null;
        $fromDate = null;
        if ($period === 'week') {
            $fromDate = Carbon::now()->subDays(7);
        } elseif ($period === 'month') {
            $fromDate = Carbon::now()->subDays(30);
        }

        $rankingQuery = DB::table('order_products')
            ->join('products', 'products.id', '=', 'order_products.product_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->leftJoin('orders', 'orders.id', '=', 'order_products.order_id')
            ->select(
                'products.id',
                'products.name',
                'products.price',
                'categories.name as category_name',
                DB::raw('SUM(COALESCE(order_products.qty, 1)) as total_qty'),
                DB::raw('COUNT(order_products.id) as total_orders')
            );

        if (!empty($categoryId)) {
            $rankingQuery->where('products.category_id', $categoryId);
        }

        if ($fromDate) {
            $rankingQuery->where(function ($query) use ($fromDate) {
                $query->where('orders.created_at', '>=', $fromDate)
                    ->orWhere(function ($subQuery) use ($fromDate) {
                        $subQuery->whereNull('order_products.order_id')
                            ->where('order_products.created_at', '>=', $fromDate);
                    });
            });
        }

        $topOrderedProducts = $rankingQuery
            ->groupBy('products.id', 'products.name', 'products.price', 'categories.name')
            ->orderByDesc('total_qty')
            ->orderByDesc('total_orders')
            ->limit(30)
            ->get();

        $categories = Category::where('status', 1)->orderBy('name')->get();

        $setting = Setting::first();
        $frontend_url = $setting->frontend_url;
        $frontend_view = $frontend_url.'single-product?slug=';

        return view('admin.review', compact(
            'topOrderedProducts',
            'categories',
            'categoryId',
            'period',
            'setting',
            'frontend_view'
        ));
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
