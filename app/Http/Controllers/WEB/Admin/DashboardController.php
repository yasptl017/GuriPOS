<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Vendor;
use App\Models\Subscriber;
use App\Models\User;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Admin;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function dashobard(){
        $today_orders = Order::orderBy('id', 'desc')
    ->whereDate('created_at', now()->setTimezone('Australia/Sydney')->toDateString())
    ->get();
        $today_total_earning = $today_orders->where('payment_status',1)->sum('grand_total');

        $monthly_orders = Order::orderBy('id','desc')->whereMonth('created_at', now()->month)->get();
        $monthly_total_earning = $monthly_orders->where('payment_status',1)->sum('grand_total');

        $yearly_orders = Order::orderBy('id','desc')->whereYear('created_at', now()->year)->get();
        $yearly_total_earning = $yearly_orders->where('payment_status',1)->sum('grand_total');

        $total_oders = Order::orderBy('id','desc')->get();
        $total_earning = $total_oders->where('payment_status',1)->sum('grand_total');

        $total_users = User::count();
        $total_blog = Blog::count();
        $total_admin = Admin::count();
        $total_subscriber = Subscriber::where('is_verified',1)->count();

        return view('admin.dashboard')->with([
            'today_orders' => $today_orders,
            'today_total_earning' => $today_total_earning,
            'monthly_orders' => $monthly_orders,
            'monthly_total_earning' => $monthly_total_earning,
            'yearly_orders' => $yearly_orders,
            'yearly_total_earning' => $yearly_total_earning,
            'total_oders' => $total_oders,
            'total_earning' => $total_earning,
            'total_users' => $total_users,
            'total_blog' => $total_blog,
            'total_admin' => $total_admin,
            'total_subscriber' => $total_subscriber,
        ]);

    }


}
