<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Footer;
use Image;
use File;
class FooterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $footer = Footer::first();
        return view('admin.website_footer', compact('footer'));
    }

    public function update(Request $request, $id){
        $rules = [
            'email' =>'required',
            'phone' =>'required',
            'address' =>'required',
            'copyright' =>'required',
            'about_us' =>'required',
        ];
        $customMessages = [
            'email.required' => trans('admin_validation.Email is required'),
            'phone.required' => trans('admin_validation.Phone is required'),
            'address.required' => trans('admin_validation.Address is required'),
            'copyright.required' => trans('admin_validation.Copyright is required'),
            'about_us.required' => trans('admin_validation.About us is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $footer = Footer::first();
        $footer->email = $request->email;
        $footer->phone = $request->phone;
        $footer->address = $request->address;
        $footer->copyright = $request->copyright;
        $footer->about_us = $request->about_us;
        $footer->save();

        if($request->footer_background){
            $old_logo=$footer->footer_background;
            $image=$request->footer_background;
            $ext=$image->getClientOriginalExtension();
            $logo_name= 'footer_background-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$ext;
            $logo_name='uploads/website-images/'.$logo_name;
            $logo=Image::make($image)
                    ->save(public_path().'/'.$logo_name);
            $footer->footer_background=$logo_name;
            $footer->save();
            if($old_logo){
                if(File::exists(public_path().'/'.$old_logo))unlink(public_path().'/'.$old_logo);
            }
        }

        if($request->footer_background_2){
            $old_logo=$footer->footer_background_2;
            $image=$request->footer_background_2;
            $ext=$image->getClientOriginalExtension();
            $logo_name= 'footer_background_2-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$ext;
            $logo_name='uploads/website-images/'.$logo_name;
            $logo=Image::make($image)
                    ->save(public_path().'/'.$logo_name);
            $footer->footer_background_2=$logo_name;
            $footer->save();
            if($old_logo){
                if(File::exists(public_path().'/'.$old_logo))unlink(public_path().'/'.$old_logo);
            }
        }



        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }
}
