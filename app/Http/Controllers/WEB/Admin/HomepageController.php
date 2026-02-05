<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Homepage;
use Image;
use File;
class HomepageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function homepage(){
        $homepage = Homepage::first();
        return view('admin.homepage', compact('homepage'));
    }

    public function update_homepage(Request $request){
        $homepage = Homepage::first();
        $homepage->today_special_short_title = $request->today_special_short_title;
        $homepage->today_special_long_title = $request->today_special_long_title;
        $homepage->today_special_description = $request->today_special_description;
        $homepage->today_special_item = $request->today_special_item;
        $homepage->today_special_status = $request->today_special_status ? 1 : 0;
        $homepage->menu_short_title = $request->menu_short_title;
        $homepage->menu_long_title = $request->menu_long_title;
        $homepage->menu_description = $request->menu_description;
        $homepage->menu_item = $request->menu_item;
        $homepage->menu_status = $request->menu_status ? 1 : 0;
        $homepage->total_advertisement_item = $request->total_advertisement_item;
        $homepage->advertisement_status = $request->advertisement_status ? 1 : 0;
        $homepage->chef_short_title = $request->chef_short_title;
        $homepage->chef_long_title = $request->chef_long_title;
        $homepage->chef_description = $request->chef_description;
        $homepage->chef_item = $request->chef_item;
        $homepage->chef_status = $request->chef_status ? 1 : 0;
        $homepage->mobile_app_status = $request->mobile_app_status ? 1 : 0;
        $homepage->counter_status = $request->counter_status ? 1 : 0;
        $homepage->testimonial_short_title = $request->testimonial_short_title;
        $homepage->testimonial_long_title = $request->testimonial_long_title;
        $homepage->testimonial_description = $request->testimonial_description;
        $homepage->testimonial_item = $request->testimonial_item;
        $homepage->testimonial_status = $request->testimonial_status ? 1 : 0;
        $homepage->blog_short_title = $request->blog_short_title;
        $homepage->blog_long_title = $request->blog_long_title;
        $homepage->blog_description = $request->blog_description;
        $homepage->blog_item = $request->blog_item;
        $homepage->blog_status = $request->blog_status ? 1 : 0;
        $homepage->why_choose_us_status = $request->why_choose_us_status ? 1 : 0;
        $homepage->video_section_status = $request->video_section_status ? 1 : 0;
        $homepage->service_status = $request->service_status ? 1 : 0;
        $homepage->save();

        if($request->today_special_image){
            $existing_bg = $homepage->today_special_image;
            $extention = $request->today_special_image->getClientOriginalExtension();
            $bg_image = 'today_special_image'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $bg_image = 'uploads/website-images/'.$bg_image;
            $request->today_special_image->move(public_path('uploads/website-images/'),$bg_image);
            $homepage->today_special_image = $bg_image;
            $homepage->save();
            if($existing_bg){
                if(File::exists(public_path().'/'.$existing_bg))unlink(public_path().'/'.$existing_bg);
            }
        }

        if($request->menu_left_image){
            $existing_bg = $homepage->menu_left_image;
            $extention = $request->menu_left_image->getClientOriginalExtension();
            $bg_image = 'menu_left_image'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $bg_image = 'uploads/website-images/'.$bg_image;
            $request->menu_left_image->move(public_path('uploads/website-images/'),$bg_image);
            $homepage->menu_left_image = $bg_image;
            $homepage->save();
            if($existing_bg){
                if(File::exists(public_path().'/'.$existing_bg))unlink(public_path().'/'.$existing_bg);
            }
        }

        if($request->menu_right_image){
            $existing_bg = $homepage->menu_right_image;
            $extention = $request->menu_right_image->getClientOriginalExtension();
            $bg_image = 'menu_right_image'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $bg_image = 'uploads/website-images/'.$bg_image;
            $request->menu_right_image->move(public_path('uploads/website-images/'),$bg_image);
            $homepage->menu_right_image = $bg_image;
            $homepage->save();
            if($existing_bg){
                if(File::exists(public_path().'/'.$existing_bg))unlink(public_path().'/'.$existing_bg);
            }
        }

        if($request->chef_left_image){
            $existing_bg = $homepage->chef_left_image;
            $extention = $request->chef_left_image->getClientOriginalExtension();
            $bg_image = 'chef_left_image'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $bg_image = 'uploads/website-images/'.$bg_image;
            $request->chef_left_image->move(public_path('uploads/website-images/'),$bg_image);
            $homepage->chef_left_image = $bg_image;
            $homepage->save();
            if($existing_bg){
                if(File::exists(public_path().'/'.$existing_bg))unlink(public_path().'/'.$existing_bg);
            }
        }

        if($request->chef_right_image){
            $existing_bg = $homepage->chef_right_image;
            $extention = $request->chef_right_image->getClientOriginalExtension();
            $bg_image = 'chef_right_image'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $bg_image = 'uploads/website-images/'.$bg_image;
            $request->chef_right_image->move(public_path('uploads/website-images/'),$bg_image);
            $homepage->chef_right_image = $bg_image;
            $homepage->save();
            if($existing_bg){
                if(File::exists(public_path().'/'.$existing_bg))unlink(public_path().'/'.$existing_bg);
            }
        }

        if($request->blog_background){
            $existing_bg = $homepage->blog_background;
            $extention = $request->blog_background->getClientOriginalExtension();
            $bg_image = 'blog_background'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $bg_image = 'uploads/website-images/'.$bg_image;
            $request->blog_background->move(public_path('uploads/website-images/'),$bg_image);
            $homepage->blog_background = $bg_image;
            $homepage->save();
            if($existing_bg){
                if(File::exists(public_path().'/'.$existing_bg))unlink(public_path().'/'.$existing_bg);
            }
        }

        if($request->blog_background_2){
            $existing_bg = $homepage->blog_background_2;
            $extention = $request->blog_background_2->getClientOriginalExtension();
            $bg_image = 'blog_background_2'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $bg_image = 'uploads/website-images/'.$bg_image;
            $request->blog_background_2->move(public_path('uploads/website-images/'),$bg_image);
            $homepage->blog_background_2 = $bg_image;
            $homepage->save();
            if($existing_bg){
                if(File::exists(public_path().'/'.$existing_bg))unlink(public_path().'/'.$existing_bg);
            }
        }

        $notification= trans('admin_validation.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

}
