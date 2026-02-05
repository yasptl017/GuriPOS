<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Counter;
use App\Models\Setting;
use File;

class CounterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $counters = Counter::all();
        $setting = Setting::first();
        return view('admin.counter', compact('counters','setting'));
    }

    public function create(){
        return view('admin.create_counter');
    }

    public function store(Request $request){
        $rules = [
            'title' => 'required',
            'icon' => 'required',
            'quantity' => 'required',
        ];
        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'icon.required' => trans('admin_validation.Icon is required'),
            'quantity.required' => trans('admin_validation.Quantity is required'),

        ];
        $this->validate($request, $rules,$customMessages);

        $counter = new Counter();
        $counter->title = $request->title;
        $counter->icon = $request->icon;
        $counter->quantity = $request->quantity;
        $counter->save();

        $notification= trans('admin_validation.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.counter.index')->with($notification);
    }

    public function edit($id){
        $counter = Counter::find($id);

        return view('admin.edit_counter', compact('counter'));
    }

    public function update(Request $request, $id){
        $rules = [
            'title' => 'required',
            'icon' => 'required',
            'quantity' => 'required',
        ];
        $customMessages = [
            'title.required' => trans('admin_validation.Title is required'),
            'icon.required' => trans('admin_validation.Icon is required'),
            'quantity.required' => trans('admin_validation.Quantity is required'),

        ];
        $this->validate($request, $rules,$customMessages);

        $counter = Counter::find($id);
        $counter->title = $request->title;
        $counter->icon = $request->icon;
        $counter->quantity = $request->quantity;
        $counter->save();

        $notification= trans('admin_validation.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.counter.index')->with($notification);
    }

    public function destroy($id){
        $counter = Counter::find($id);
        $counter->delete();

        $notification= trans('admin_validation.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function update_counter_image(Request $request){

        $setting = Setting::first();
        if($request->background_image){
            $existing_bg = $setting->counter_background;
            $extention = $request->background_image->getClientOriginalExtension();
            $bg_image = 'counter-bg'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $bg_image = 'uploads/website-images/'.$bg_image;
            $request->background_image->move(public_path('uploads/website-images/'),$bg_image);
            $setting->counter_background = $bg_image;
            $setting->save();
            if($existing_bg){
                if(File::exists(public_path().'/'.$existing_bg))unlink(public_path().'/'.$existing_bg);
            }
        }

        $notification= trans('admin_validation.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }
}
