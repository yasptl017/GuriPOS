<?php
namespace App\Http\Controllers\WEB\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintainanceText;
use App\Models\Setting;
use App\Models\BannerImage;
use App\Models\SeoSetting;
use Image;
use File;

class ContentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function maintainanceMode()
    {
        $maintainance = MaintainanceText::first();
        return view('admin.maintainance_mode', compact('maintainance'));
    }



    public function maintainanceModeUpdate(Request $request)
    {
        $rules = [
            'description'=> 'required',
        ];

        $customMessages = [
            'description.required' => trans('admin_validation.Description is required'),
            'status.required' => trans('admin_validation.Status is required'),
        ];

        $this->validate($request, $rules,$customMessages);

        $maintainance = MaintainanceText::first();
        if($request->image){
            $old_image=$maintainance->image;
            $image=$request->image;
            $ext=$image->getClientOriginalExtension();
            $image_name= 'maintainance-mode-'.date('Y-m-d-h-i-s-').rand(999,9999).'.'.$ext;
            $image_name='uploads/website-images/'.$image_name;
            Image::make($image)
                ->save(public_path().'/'.$image_name);
            $maintainance->image=$image_name;
            $maintainance->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        $maintainance->status = $request->maintainance_mode ? 1 : 0;
        $maintainance->description = $request->description;
        $maintainance->save();

        $notification= trans('admin_validation.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function loginPage(){
        $login_page_image = Setting::first();

        return view('admin.login_page', compact('login_page_image'));
    }

    public function updateloginPage(Request $request){
        $banner = Setting::first();
        if($request->image){
            $existing_banner = $banner->login_page_image;
            $extention = $request->image->getClientOriginalExtension();
            $banner_name = 'banner'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $banner_name = 'uploads/website-images/'.$banner_name;
            Image::make($request->image)
                ->save(public_path().'/'.$banner_name);
            $banner->login_page_image = $banner_name;
            $banner->save();
            if($existing_banner){
                if(File::exists(public_path().'/'.$existing_banner))unlink(public_path().'/'.$existing_banner);
            }
        }

        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function seoSetup(){
        $pages = SeoSetting::all();
        return view('admin.seo_setup', compact('pages'));
    }

    public function getSeoSetup($id){
        $page = SeoSetting::find($id);
        return response()->json(['page' => $page], 200);
    }

    public function updateSeoSetup(Request $request, $id){
        $rules = [
            'seo_title' => 'required',
            'seo_description' => 'required'
        ];

        $customMessages = [
            'seo_title.required' => trans('admin_validation.Seo title is required'),
            'seo_description.required' => trans('admin_validation.Seo description is required'),
        ];

        $this->validate($request, $rules,$customMessages);

        $page = SeoSetting::find($id);
        $page->seo_title = $request->seo_title;
        $page->seo_description = $request->seo_description;
        $page->save();

        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function defaultAvatar(){
        $default_avatar = Setting::select('default_avatar')->first();
        return view('admin.default_profile_image', compact('default_avatar'));
    }

    public function updateDefaultAvatar(Request $request){

        $setting = Setting::first();
        if($request->avatar){
            $existing_avatar = $setting->default_avatar;
            $extention = $request->avatar->getClientOriginalExtension();
            $default_avatar = 'default-avatar'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $default_avatar = 'uploads/website-images/'.$default_avatar;
            Image::make($request->avatar)
                ->save(public_path().'/'.$default_avatar);
            $setting->default_avatar = $default_avatar;
            $setting->save();

            if($existing_avatar){
                if(File::exists(public_path().'/'.$existing_avatar))unlink(public_path().'/'.$existing_avatar);
            }
        }

        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function app_section(){
        $app_section = Setting::first();
        return view('admin.app_section', compact('app_section'));
    }

    public function update_app_section(Request $request){
        $rules = [
            'app_title' => 'required',
            'app_description' => 'required',
            'play_store_link' => 'required',
            'app_store_link' => 'required',
        ];
        $customMessages = [
            'app_title.required' => trans('admin_validation.Title is required'),
            'app_description.required' => trans('admin_validation.Description is required'),
            'play_store_link.required' => trans('admin_validation.Play store link is required'),
            'app_store_link.required' => trans('admin_validation.App store link is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $setting = Setting::first();
        $setting->app_title = $request->app_title;
        $setting->app_description = $request->app_description;
        $setting->play_store_link = $request->play_store_link;
        $setting->app_store_link = $request->app_store_link;
        $setting->save();

        if($request->image){
            $existing_bg = $setting->app_image;
            $extention = $request->image->getClientOriginalExtension();
            $bg_image = 'app-image'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $bg_image = 'uploads/website-images/'.$bg_image;
            $request->image->move(public_path('uploads/website-images/'),$bg_image);
            $setting->app_image = $bg_image;
            $setting->save();
            if($existing_bg){
                if(File::exists(public_path().'/'.$existing_bg))unlink(public_path().'/'.$existing_bg);
            }
        }

        if($request->app_background_one){
            $existing_bg = $setting->app_background_one;
            $extention = $request->app_background_one->getClientOriginalExtension();
            $bg_image = 'app_background_one'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $bg_image = 'uploads/website-images/'.$bg_image;
            $request->app_background_one->move(public_path('uploads/website-images/'),$bg_image);
            $setting->app_background_one = $bg_image;
            $setting->save();
            if($existing_bg){
                if(File::exists(public_path().'/'.$existing_bg))unlink(public_path().'/'.$existing_bg);
            }
        }

        if($request->app_background_two){
            $existing_bg = $setting->app_background_two;
            $extention = $request->app_background_two->getClientOriginalExtension();
            $bg_image = 'app_background_two'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $bg_image = 'uploads/website-images/'.$bg_image;
            $request->app_background_two->move(public_path('uploads/website-images/'),$bg_image);
            $setting->app_background_two = $bg_image;
            $setting->save();
            if($existing_bg){
                if(File::exists(public_path().'/'.$existing_bg))unlink(public_path().'/'.$existing_bg);
            }
        }

        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function breadcrumb_image(){
        $setting = Setting::first();
        $breadcrumb_image = $setting->breadcrumb_image;
        return view('admin.breadcrumb_image', compact('breadcrumb_image'));
    }

    public function update_breadcrumb_image(Request $request){
        $setting = Setting::first();
        if($request->image){
            $existing_bg = $setting->breadcrumb_image;
            $extention = $request->image->getClientOriginalExtension();
            $bg_image = 'breadcrumb_image'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $bg_image = 'uploads/website-images/'.$bg_image;
            $request->image->move(public_path('uploads/website-images/'),$bg_image);
            $setting->breadcrumb_image = $bg_image;
            $setting->save();
            if($existing_bg){
                if(File::exists(public_path().'/'.$existing_bg))unlink(public_path().'/'.$existing_bg);
            }
        }

        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function appointment_bg(){
        $setting = Setting::first();

        return view('admin.appointment_bg', compact('setting'));
    }

    public function update_appointment_bg(Request $request){

        $setting = Setting::first();
        if($request->appointment_bg){
            $existing_bg = $setting->appointment_bg;
            $extention = $request->appointment_bg->getClientOriginalExtension();
            $bg_image = 'appointment_bg'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $bg_image = 'uploads/website-images/'.$bg_image;
            $request->appointment_bg->move(public_path('uploads/website-images/'),$bg_image);
            $setting->appointment_bg = $bg_image;
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

