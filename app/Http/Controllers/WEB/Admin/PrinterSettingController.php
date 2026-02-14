<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PrinterSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $setting = Setting::first();

        return view('admin.printer_setting', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'kitchen_printer' => 'nullable|string|max:255',
            'desk_printer'    => 'nullable|string|max:255',
            'print_mode'      => 'nullable|in:poll,push',
            'agent_port'      => 'nullable|integer|min:1024|max:65535',
        ]);

        $setting = Setting::first();
        if (!$setting) {
            $notification = array(
                'messege' => 'Settings row not found.',
                'alert-type' => 'error',
            );

            return redirect()->back()->with($notification);
        }

        $setting->kitchen_printer = $request->kitchen_printer;
        $setting->desk_printer    = $request->desk_printer;
        $setting->print_mode      = $request->input('print_mode', 'poll');
        $setting->agent_port      = (int) $request->input('agent_port', 5757);
        $setting->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');

        return redirect()->back()->with($notification);
    }
}
