<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderControl;
use Illuminate\Http\Request;

class OrderControlController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $orderControl = OrderControl::first();

        if (!$orderControl) {
            $orderControl = OrderControl::create([
                'pickup_enabled'           => 1,
                'pickup_disabled_message'  => '',
                'delivery_enabled'         => 1,
                'delivery_disabled_message' => '',
            ]);
        }

        return view('admin.order_control', compact('orderControl'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'pickup_disabled_message'   => 'nullable|string|max:500',
            'delivery_disabled_message' => 'nullable|string|max:500',
        ]);

        $orderControl = OrderControl::first();

        if (!$orderControl) {
            $orderControl = new OrderControl();
        }

        $orderControl->pickup_enabled           = $request->has('pickup_enabled') ? 1 : 0;
        $orderControl->pickup_disabled_message  = $request->pickup_disabled_message;
        $orderControl->delivery_enabled         = $request->has('delivery_enabled') ? 1 : 0;
        $orderControl->delivery_disabled_message = $request->delivery_disabled_message;
        $orderControl->save();

        $notification = trans('admin_validation.Updated Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }
}
